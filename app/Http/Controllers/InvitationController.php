<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Response;

class InvitationController extends Controller
{
    public function show(string $token): View|Response
    {
        $tokenHash = hash('sha256', $token);

        $invitation = Invitation::where('token_hash', $tokenHash)->first();

        if (!$invitation) {
            return response()->view('invitations.error', [
                'message' => 'This invitation link is invalid.',
            ], 404);
        }

        if ($invitation->accepted_at !== null) {
            return response()->view('invitations.error', [
                'message' => 'This invitation has already been accepted. Please log in.',
            ], 410);
        }

        if ($invitation->expires_at->isPast()) {
            return response()->view('invitations.error', [
                'message' => 'This invitation link has expired. Please contact the administrator.',
            ], 410);
        }

        return view('invitations.accept', compact('invitation', 'token'));
    }

    public function store(Request $request, string $token): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8),
            ],
        ]);

        $tokenHash = hash('sha256', $token);

        $result = DB::transaction(function () use ($tokenHash, $validated) {

            $invitation = Invitation::where('token_hash', $tokenHash)
                ->lockForUpdate()
                ->first();

            if (!$invitation) {
                return 'invalid';
            }

            if ($invitation->accepted_at !== null) {
                return 'accepted';
            }

            if ($invitation->expires_at->isPast()) {
                return 'expired';
            }

            if (!in_array($invitation->role, [
                User::ROLE_ADMIN,
                User::ROLE_MEMBER,
            ], true)) {
                return 'invalid';
            }

            User::create([
                'name' => $validated['name'],
                'email' => $invitation->email,
                'password' => $validated['password'],
                'company_id' => $invitation->company_id,
                'role' => $invitation->role,
            ]);

            $invitation->update([
                'accepted_at' => now(),
            ]);

            return 'success';
        });

        if ($result !== 'success') {
            return redirect()->route('invitations.accept', [
                'token' => $token,
            ])->withErrors([
                'invitation' => match ($result) {
                    'accepted' => 'This invitation has already been accepted.',
                    'expired' => 'This invitation has expired.',
                    default => 'This invitation link is invalid.',
                },
            ]);
        }

        return redirect()->route('login')->with(
            'success',
            'Your account has been created successfully. You can now log in.'
        );
    }
}