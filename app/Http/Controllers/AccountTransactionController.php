<?php

namespace App\Http\Controllers;

use App\Models\AccountTransaction;
use Illuminate\Http\Request;

class AccountTransactionController extends Controller
{
    /**
     * Display a listing of account transactions with summaries and filters.
     */
    public function index(Request $request)
    {
        $query = AccountTransaction::query();

        if ($request->filled('transaction_type')) {
            $query->where('transaction_type', $request->transaction_type);
        }

        if ($request->filled('category')) {
            $query->where('category', 'like', "%{$request->category}%");
        }

        if ($request->filled('search')) {
            $query->where('description', 'like', "%{$request->search}%");
        }

        // Calculate summary figures
        $totalIncome = AccountTransaction::where('transaction_type', 'income')->sum('amount');
        $totalExpense = AccountTransaction::where('transaction_type', 'expense')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        $transactions = $query->orderBy('id', 'desc')->paginate(10);

        return view('account_transactions.index', compact('transactions', 'totalIncome', 'totalExpense', 'netBalance'));
    }

    /**
     * Show the form for creating a new transaction.
     */
    public function create()
    {
        return view('account_transactions.create');
    }

    /**
     * Store a newly created transaction in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'transaction_type' => 'required|in:income,expense',
            'category'         => 'required|string|max:100',
            'amount'           => 'required|numeric|min:0.01',
            'description'      => 'nullable|string',
        ]);

        AccountTransaction::create([
            'transaction_type' => $request->transaction_type,
            'category'         => $request->category,
            'amount'           => $request->amount,
            'description'      => $request->description,
            'created_at'       => now(),
        ]);

        return redirect()->route('account-transactions.index')->with('success', 'Transaction recorded successfully.');
    }

    /**
     * Show the form for editing the specified transaction.
     */
    public function edit(AccountTransaction $accountTransaction)
    {
        return view('account_transactions.edit', compact('accountTransaction'));
    }

    /**
     * Update the specified transaction in storage.
     */
    public function update(Request $request, AccountTransaction $accountTransaction)
    {
        $request->validate([
            'transaction_type' => 'required|in:income,expense',
            'category'         => 'required|string|max:100',
            'amount'           => 'required|numeric|min:0.01',
            'description'      => 'nullable|string',
        ]);

        $accountTransaction->update([
            'transaction_type' => $request->transaction_type,
            'category'         => $request->category,
            'amount'           => $request->amount,
            'description'      => $request->description,
        ]);

        return redirect()->route('account-transactions.index')->with('success', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified transaction from storage.
     */
    public function destroy(AccountTransaction $accountTransaction)
    {
        $accountTransaction->delete();

        return redirect()->route('account-transactions.index')->with('success', 'Transaction deleted successfully.');
    }
}