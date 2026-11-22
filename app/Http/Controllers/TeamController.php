<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use App\Models\User;
use App\Models\Invitation;

class TeamController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        // Get members
        $members = User::where('company_id', $user->company_id)
            ->where('id', '!=', $user->id)
            ->latest()
            ->paginate(3);

        return view('team.index', compact('members'));
    }
}
