<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\View\View;

class TeamInvitationController extends Controller
{
    public function index(): View
    {
        return view('team.invite');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            'role' => [
                'required',
                Rule::in([User::ROLE_ADMIN, User::ROLE_MEMBER]),
            ],
        ]);

        $companyId = auth()->user()->company_id;

        // Prevent duplicate active invitations.
        $existingInvitation = Invitation::where('company_id', $companyId)
            ->where('email', $validated['email'])
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->exists();

        if ($existingInvitation) {
            return back()->withErrors([
                'email' => 'An active invitation already exists for this email.',
            ])->withInput();
        }

        $token = Str::random(64);

        Invitation::create([
            'company_id' => $companyId,
            'invited_by' => auth()->id(),
            'email' => $validated['email'],
            'name' => $validated['name'],
            'role' => $validated['role'],
            'token_hash' => hash('sha256', $token),
            'expires_at' => now()->addDays(7),
        ]);

        $invitationUrl = route('invitations.accept', [
            'token' => $token,
        ]);

        return back()->with([
            'success' => 'Invitation link generated successfully.',
            'invitation_url' => $invitationUrl,
        ]);
    }
}
