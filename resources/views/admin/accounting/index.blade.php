@extends('admin.layouts.admin')

@section('title', 'Accounting & Bank Accounts - Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-serif font-bold text-luxury-maroon">Accounting & Finance</h1>
            <p class="text-xs text-gray-500 mt-1">Manage multiple bank accounts, transactions, and business expenses.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success text-xs rounded border border-green-300 py-2.5 px-4 bg-green-50 text-green-700">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger text-xs rounded border border-red-300 py-2.5 px-4 bg-red-50 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <!-- Bank Accounts Overview Row -->
    <div class="row g-4">
        @foreach($accounts as $acc)
            <div class="col-md-6 col-lg-4">
                <div class="bg-gradient-to-br from-luxury-maroon to-luxury-maroonlight text-white rounded-lg p-6 shadow-sm border border-luxury-gold/20 space-y-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="text-xxs uppercase tracking-wider text-luxury-gold font-bold block">{{ $acc->bank_name }}</span>
                            <h3 class="text-base font-serif font-bold text-luxury-cream mt-1">{{ $acc->account_name }}</h3>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-white/20 text-white">
                            {{ $acc->status }}
                        </span>
                    </div>
                    <div>
                        <span class="text-xxs text-luxury-cream/50 block font-semibold">Account Number:</span>
                        <strong class="text-sm font-mono text-luxury-cream">{{ $acc->account_number }}</strong>
                    </div>
                    <div class="border-t border-white/10 pt-3 flex items-center justify-between">
                        <span class="text-xxs text-luxury-cream/70">Ledger Balance:</span>
                        <strong class="text-lg text-luxury-gold">₹{{ number_format($acc->balance, 2) }}</strong>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4 mt-2">
        <!-- Forms Panel -->
        <div class="col-lg-4 space-y-4">
            <!-- Add Bank Account -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon"><i class="bi bi-bank2 mr-2"></i> Register Bank Account</h3>
                <form action="{{ route('admin.bank.store') }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label class="font-semibold block mb-1">Bank Name *</label>
                        <input type="text" name="bank_name" required placeholder="e.g. HDFC Bank" class="w-full border rounded px-3 py-2 outline-none">
                    </div>
                    <div>
                        <label class="font-semibold block mb-1">Account Holder Name *</label>
                        <input type="text" name="account_name" required placeholder="e.g. Ansari Handloom" class="w-full border rounded px-3 py-2 outline-none">
                    </div>
                    <div>
                        <label class="font-semibold block mb-1">Account Number *</label>
                        <input type="text" name="account_number" required class="w-full border rounded px-3 py-2 outline-none">
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="font-semibold block mb-1">Branch</label>
                            <input type="text" name="branch" class="w-full border rounded px-3 py-2 outline-none">
                        </div>
                        <div class="col-md-6">
                            <label class="font-semibold block mb-1">Opening Balance *</label>
                            <input type="number" name="balance" required min="0" step="0.01" class="w-full border rounded px-3 py-2 outline-none">
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-luxury-maroon text-white font-bold py-2.5 rounded uppercase tracking-wider text-[10px] hover:bg-luxury-maroonlight transition">
                        Register Account
                    </button>
                </form>
            </div>

            <!-- Manual Bank Entry -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon"><i class="bi bi-wallet-fill mr-2"></i> Record Transaction</h3>
                <form action="{{ route('admin.transaction.store') }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label class="font-semibold block mb-1">Bank Account *</label>
                        <select name="bank_account_id" required class="w-full border rounded px-3 py-2 outline-none">
                            <option value="">-- Select Bank Account --</option>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->bank_name }} - {{ $acc->account_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="font-semibold block mb-1">Type *</label>
                            <select name="type" required class="w-full border rounded px-3 py-2 outline-none">
                                <option value="deposit">Deposit</option>
                                <option value="withdrawal">Withdrawal</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="font-semibold block mb-1">Amount *</label>
                            <input type="number" name="amount" required min="1" step="0.01" class="w-full border rounded px-3 py-2 outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="font-semibold block mb-1">Category/Reason *</label>
                        <input type="text" name="category" required placeholder="e.g. Retail Sales, Cash Deposit" class="w-full border rounded px-3 py-2 outline-none">
                    </div>
                    <div>
                        <label class="font-semibold block mb-1">Remarks</label>
                        <input type="text" name="description" class="w-full border rounded px-3 py-2 outline-none">
                    </div>
                    <button type="submit" class="w-full bg-luxury-gold text-luxury-charcoal font-bold py-2.5 rounded uppercase tracking-wider text-[10px] hover:bg-yellow-500 transition">
                        Post Transaction
                    </button>
                </form>
            </div>

            <!-- Record Business Expense -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon"><i class="bi bi-cash-stack mr-2"></i> Log Business Expense</h3>
                <form action="{{ route('admin.expense.store') }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label class="font-semibold block mb-1">Expense Category *</label>
                        <select name="category" required class="w-full border rounded px-3 py-2 outline-none">
                            <option value="Salaries">Staff Salaries</option>
                            <option value="Electricity">Electricity Bills</option>
                            <option value="Loom Maintenance">Loom Maintenance</option>
                            <option value="Office Rent">Office Rent</option>
                            <option value="Weaving Charges">Weaver Wages</option>
                            <option value="Marketing">Marketing / Ads</option>
                        </select>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="font-semibold block mb-1">Payment Account *</label>
                            <select name="bank_account_id" required class="w-full border rounded px-3 py-2 outline-none">
                                <option value="">-- Select Bank --</option>
                                @foreach($accounts as $acc)
                                    <option value="{{ $acc->id }}">{{ $acc->bank_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="font-semibold block mb-1">Expense Amount *</label>
                            <input type="number" name="amount" required min="1" step="0.01" class="w-full border rounded px-3 py-2 outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="font-semibold block mb-1">Expense Details/Remarks</label>
                        <input type="text" name="description" class="w-full border rounded px-3 py-2 outline-none">
                    </div>
                    <button type="submit" class="w-full bg-red-600 text-white font-bold py-2.5 rounded uppercase tracking-wider text-[10px] hover:bg-red-700 transition">
                        Post Expense
                    </button>
                </form>
            </div>
        </div>

        <!-- Ledger Logs Lists -->
        <div class="col-lg-8 space-y-6">
            <!-- Bank transactions ledger -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 space-y-3">
                <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon"><i class="bi bi-file-earmark-spreadsheet-fill mr-2"></i> Bank Transactions Ledger</h3>
                <div class="table-responsive">
                    <table class="table align-middle mb-0 text-xs">
                        <thead class="table-light">
                            <tr>
                                <th class="py-2">Date</th>
                                <th class="py-2">Bank</th>
                                <th class="py-2">Type</th>
                                <th class="py-2">Category</th>
                                <th class="py-2">Amount</th>
                                <th class="py-2">Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($transactions as $tx)
                                <tr>
                                    <td class="py-3 text-gray-500 font-mono">{{ $tx->transaction_date }}</td>
                                    <td class="py-3 text-gray-500">{{ $tx->bankAccount->bank_name }}</td>
                                    <td class="py-3">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold 
                                            {{ $tx->type == 'deposit' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' }}
                                        ">
                                            {{ strtoupper($tx->type) }}
                                        </span>
                                    </td>
                                    <td class="py-3 font-bold text-luxury-maroon">{{ $tx->category }}</td>
                                    <td class="py-3 font-bold 
                                        {{ $tx->type == 'deposit' ? 'text-green-600' : 'text-red-600' }}
                                    ">
                                        {{ $tx->type == 'deposit' ? '+' : '-' }}₹{{ number_format($tx->amount, 2) }}
                                    </td>
                                    <td class="py-3 text-gray-500">{{ $tx->description ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-gray-400">No bank ledger items recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Expense logs -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 space-y-3">
                <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon"><i class="bi bi-clock-history mr-2"></i> Operating Expenses History</h3>
                <div class="table-responsive">
                    <table class="table align-middle mb-0 text-xs">
                        <thead class="table-light">
                            <tr>
                                <th class="py-2">Date</th>
                                <th class="py-2">Category</th>
                                <th class="py-2">Paid From</th>
                                <th class="py-2">Expense Amount</th>
                                <th class="py-2">Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($expenses as $exp)
                                <tr>
                                    <td class="py-3 text-gray-500 font-mono">{{ $exp->expense_date }}</td>
                                    <td class="py-3 font-bold text-red-600">{{ $exp->category }}</td>
                                    <td class="py-3 text-gray-500">{{ $exp->bankAccount->bank_name }}</td>
                                    <td class="py-3 font-bold text-luxury-charcoal">₹{{ number_format($exp->amount, 2) }}</td>
                                    <td class="py-3 text-gray-500">{{ $exp->description ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-gray-400">No expense records logged.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
