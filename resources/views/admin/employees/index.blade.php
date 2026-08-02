@extends('admin.layouts.admin')

@section('title', 'Staff Directory - Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-serif font-bold text-luxury-maroon">Staff Directory</h1>
            <p class="text-xs text-gray-500 mt-1">Manage system administrators, store managers, and weavers.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success text-xs rounded border border-green-300 py-2.5 px-4 bg-green-50 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        <!-- Add Staff Member Form -->
        <div class="col-lg-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon"><i class="bi bi-person-plus-fill mr-2"></i> Register Staff Member</h3>
                <form action="{{ route('admin.employees.store') }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label class="font-semibold block mb-1">Full Name *</label>
                        <input type="text" name="name" required class="w-full border rounded px-3 py-2 outline-none">
                    </div>
                    <div>
                        <label class="font-semibold block mb-1">Email Address *</label>
                        <input type="email" name="email" required class="w-full border rounded px-3 py-2 outline-none">
                    </div>
                    <div>
                        <label class="font-semibold block mb-1">Phone Number *</label>
                        <input type="text" name="phone" required class="w-full border rounded px-3 py-2 outline-none">
                    </div>
                    <div>
                        <label class="font-semibold block mb-1">Temporary Password *</label>
                        <input type="password" name="password" required class="w-full border rounded px-3 py-2 outline-none">
                    </div>
                    <div>
                        <label class="font-semibold block mb-1">Assigned Role *</label>
                        <select name="role_id" required class="w-full border rounded px-3 py-2 outline-none">
                            <option value="">-- Select Role --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-luxury-maroon text-white font-bold py-2.5 rounded uppercase tracking-wider text-[10px] hover:bg-luxury-maroonlight transition mt-2">
                        Register Employee
                    </button>
                </form>
            </div>
        </div>

        <!-- Staff List Grid -->
        <div class="col-lg-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon"><i class="bi bi-people-fill mr-2"></i> Active Staff Directory</h3>
                <div class="table-responsive">
                    <table class="table align-middle mb-0 text-xs">
                        <thead class="table-light">
                            <tr>
                                <th class="py-2">Emp Code</th>
                                <th class="py-2">Name</th>
                                <th class="py-2">Contacts</th>
                                <th class="py-2">Role</th>
                                <th class="py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($users as $user)
                                <tr>
                                    <td class="py-3 font-bold font-mono text-luxury-gold">{{ $user->employee_code }}</td>
                                    <td class="py-3 font-bold text-luxury-maroon">{{ $user->name }}</td>
                                    <td class="py-3 text-gray-500">
                                        <span>{{ $user->email }}</span><br>
                                        <span>{{ $user->phone }}</span>
                                    </td>
                                    <td class="py-3 text-gray-500">
                                        <span class="font-semibold text-luxury-charcoal">{{ $user->role->name }}</span>
                                    </td>
                                    <td class="py-3">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-green-50 text-green-700 border border-green-200">
                                            {{ $user->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
