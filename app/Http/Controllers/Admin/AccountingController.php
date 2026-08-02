<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\BankTransaction;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingController extends Controller
{
    public function index()
    {
        $accounts = BankAccount::latest()->get();
        $transactions = BankTransaction::with('bankAccount')->latest()->take(10)->get();
        $expenses = Expense::with('bankAccount')->latest()->take(10)->get();
        return view('admin.accounting.index', compact('accounts', 'transactions', 'expenses'));
    }

    public function storeBank(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:150',
            'account_name' => 'required|string|max:150',
            'account_number' => 'required|string|unique:bank_accounts,account_number',
            'branch' => 'nullable|string|max:150',
            'balance' => 'required|numeric|min:0',
        ]);

        BankAccount::create($request->all());

        return redirect()->route('admin.accounting.index')->with('success', 'Bank Account registered successfully!');
    }

    public function storeTransaction(Request $request)
    {
        $request->validate([
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'type' => 'required|in:deposit,withdrawal',
            'amount' => 'required|numeric|min:1',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $account = BankAccount::lockForUpdate()->findOrFail($request->bank_account_id);
            
            if ($request->type == 'deposit') {
                $account->balance += $request->amount;
            } else {
                if ($account->balance < $request->amount) {
                    return redirect()->back()->with('error', 'Insufficient funds in bank account!');
                }
                $account->balance -= $request->amount;
            }
            $account->save();

            BankTransaction::create([
                'bank_account_id' => $request->bank_account_id,
                'type' => $request->type,
                'amount' => $request->amount,
                'category' => $request->category,
                'transaction_date' => date('Y-m-d'),
                'description' => $request->description,
            ]);

            DB::commit();
            return redirect()->route('admin.accounting.index')->with('success', 'Bank transaction recorded successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error recording transaction: ' . $e->getMessage());
        }
    }

    public function storeExpense(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
            'bank_account_id' => 'required|exists:bank_accounts,id',
        ]);

        DB::beginTransaction();
        try {
            $account = BankAccount::lockForUpdate()->findOrFail($request->bank_account_id);
            if ($account->balance < $request->amount) {
                return redirect()->back()->with('error', 'Insufficient balance in bank account for this expense!');
            }

            // Deduct from bank account
            $account->balance -= $request->amount;
            $account->save();

            // Record Expense
            Expense::create([
                'category' => $request->category,
                'amount' => $request->amount,
                'description' => $request->description,
                'expense_date' => date('Y-m-d'),
                'bank_account_id' => $request->bank_account_id,
            ]);

            // Record transaction log
            BankTransaction::create([
                'bank_account_id' => $request->bank_account_id,
                'type' => 'withdrawal',
                'amount' => $request->amount,
                'category' => 'Expense: ' . $request->category,
                'transaction_date' => date('Y-m-d'),
                'description' => $request->description,
            ]);

            DB::commit();
            return redirect()->route('admin.accounting.index')->with('success', 'Expense recorded successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error recording expense: ' . $e->getMessage());
        }
    }
}
