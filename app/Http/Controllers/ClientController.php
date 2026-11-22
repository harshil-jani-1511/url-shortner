<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Models\Company;
use App\Models\Invitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;

class ClientController extends Controller
{
    public function index(): View
    {
        $clients = Company::withCount([
            'users',
            'urls',
            'urlClicks',
        ])->latest()->paginate(3);

        return view('super-admin.clients.index', compact('clients'));
    }
    
    public function create(): View
    {
        return view('super-admin.clients.create');
    }

    public function store(StoreClientRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $baseSlug = Str::slug($validated['name']);

        if ($baseSlug === '') {
            $baseSlug = 'client';
        }

        $slug = $baseSlug;
        $counter = 1;

        while (Company::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        // Generate invitation token
        $token = Str::random(64);

        DB::transaction(function () use ($validated, $slug, $token) {
            $company = Company::create([
                'name' => $validated['name'],
                'slug' => $slug,
                'status' => 'active',
            ]);

            Invitation::create([
                'company_id' => $company->id,
                'invited_by' => auth()->id(),
                'email' => $validated['email'],
                'role' => 'admin',
                'token_hash' => hash('sha256', $token),
                'expires_at' => now()->addDays(7),
            ]);
        });

        $invitationUrl = route('invitations.accept', [
            'token' => $token,
        ]);

        return redirect()->back()->with([
            'success' => 'Client created and Admin invitation link generated.',
            'invitation_url' => $invitationUrl,
        ]);
    }
}
