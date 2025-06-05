<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function balance(): View
    {
        return view('account.balance');
    }

    public function transactions(): View
    {
        $transactions = Auth::user()->transactions()->latest()->get();
        return view('account.transactions', compact('transactions'));
    }

    public function participations(): View
    {
        $user = Auth::user();
        $viewMode = request('view', 'cards');
        $skladchinas = $user->skladchinas()->with('category')->get();
        return view('account.participations', compact('skladchinas', 'viewMode'));
    }
}
