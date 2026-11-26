<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = $request->input('month', date('Y-m'));

        // ดึงเฉพาะรายการของ user ที่ล็อกอินอยู่
        $transactions = Transaction::where('user_id', Auth::id())
            ->where('transaction_date', 'like', $selectedMonth . '%')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        return view('pocketlog', compact(
            'transactions',
            'selectedMonth',
            'totalIncome',
            'totalExpense',
            'balance'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
        ]);

        // ผูก user_id ของคนที่กำลังล็อกอิน
        $validated['user_id'] = Auth::id();

        Transaction::create($validated);

        return redirect()->back()->with('success', 'บันทึกรายการเรียบร้อย!');
    }

    public function destroy(Transaction $transaction)
    {
        // ป้องกันไม่ให้ลบรายการของคนอื่น
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $transaction->delete();
        return redirect()->back()->with('success', 'ลบรายการเรียบร้อย!');
    }
}
