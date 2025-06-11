<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topup;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TopupController extends Controller
{
    public function index()
    {
        $topups = Topup::with('user')->latest()->paginate();
        return view('admin.topups.index', compact('topups'));
    }

    public function update(Request $request, Topup $topup)
    {
        $data = $request->validate([
            'status' => 'required|string',
        ]);

        if ($topup->status !== Topup::STATUS_PAID && $data['status'] === Topup::STATUS_PAID) {
            $user = $topup->user;
            $user->balance += $topup->amount;
            $user->save();

            Transaction::create([
                'user_id' => $user->id,
                'amount' => $topup->amount,
                'description' => 'Пополнение баланса, заказ #' . $topup->id,
            ]);
            $user->notify(new \App\Notifications\BalanceChanged('Пополнение баланса', $topup->amount));
        }

        $topup->status = $data['status'];
        $topup->save();

        return back();
    }
}
