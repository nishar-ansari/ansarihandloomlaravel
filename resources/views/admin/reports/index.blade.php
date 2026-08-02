@extends('admin.layouts.admin')

@section('title', 'Reports & Analytics - Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-serif font-bold text-luxury-maroon">Reports & Insights</h1>
            <p class="text-xs text-gray-500 mt-1">Daily, monthly, and yearly reports for sales, stock, and profits.</p>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="alert('Exporting PDF...')" class="bg-luxury-maroon text-white font-bold py-2 px-4 rounded text-[10px] uppercase tracking-wider hover:bg-luxury-maroonlight transition"><i class="bi bi-file-pdf mr-1"></i> Export PDF</button>
            <button onclick="alert('Exporting Excel...')" class="bg-luxury-gold text-luxury-charcoal font-bold py-2 px-4 rounded text-[10px] uppercase tracking-wider hover:bg-yellow-500 transition"><i class="bi bi-file-excel mr-1"></i> Export Excel</button>
        </div>
    </div>

    <!-- Metrics overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white border border-gray-100 rounded-lg p-5 shadow-sm space-y-2">
            <span class="text-xxs font-bold text-gray-400 uppercase tracking-wider block">Total Sales (Paid)</span>
            <div class="flex items-baseline space-x-2">
                <span class="text-2xl font-bold text-luxury-maroon">₹{{ number_format($totalSales, 2) }}</span>
            </div>
            <span class="text-[10px] text-green-500 font-semibold block"><i class="bi bi-arrow-up-right"></i> +12% from last month</span>
        </div>

        <div class="bg-white border border-gray-100 rounded-lg p-5 shadow-sm space-y-2">
            <span class="text-xxs font-bold text-gray-400 uppercase tracking-wider block">Tax Liability (GST 5%)</span>
            <div class="flex items-baseline space-x-2">
                <span class="text-2xl font-bold text-luxury-gold">₹{{ number_format($gstCollected, 2) }}</span>
            </div>
            <span class="text-[10px] text-gray-400 block">5% flat tax on all handloom orders</span>
        </div>

        <div class="bg-white border border-gray-100 rounded-lg p-5 shadow-sm space-y-2">
            <span class="text-xxs font-bold text-gray-400 uppercase tracking-wider block">Operating Expenses</span>
            <div class="flex items-baseline space-x-2">
                <span class="text-2xl font-bold text-red-600">₹{{ number_format($totalExpenses, 2) }}</span>
            </div>
            <span class="text-[10px] text-red-400 font-semibold block"><i class="bi bi-arrow-up-right"></i> Weaver wages + loom costs</span>
        </div>

        <div class="bg-white border border-gray-100 rounded-lg p-5 shadow-sm space-y-2">
            <span class="text-xxs font-bold text-gray-400 uppercase tracking-wider block">Estimated Net Profit</span>
            <div class="flex items-baseline space-x-2">
                <span class="text-2xl font-bold text-green-600">₹{{ number_format($netProfit, 2) }}</span>
            </div>
            <span class="text-[10px] text-green-500 font-semibold block">Calculated ledger balance surplus</span>
        </div>
    </div>

    <!-- Reports Charts Mockups -->
    <div class="row g-4 mt-2">
        <div class="col-lg-8">
            <div class="bg-white border border-gray-100 rounded-lg p-6 shadow-sm space-y-4">
                <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon border-b pb-2"><i class="bi bi-graph-up mr-2"></i> Monthly Sales & Revenue Trend</h3>
                
                <!-- Mock Chart using HTML/CSS bars -->
                <div class="flex items-end justify-between h-48 pt-6 border-b border-gray-200 text-xxs font-semibold text-gray-400">
                    <div class="flex flex-col items-center w-1/12 space-y-2">
                        <div class="w-full bg-luxury-maroon/20 hover:bg-luxury-maroon transition rounded-t" style="height: 40px;" title="Jan Sales: ₹40,000"></div>
                        <span>Jan</span>
                    </div>
                    <div class="flex flex-col items-center w-1/12 space-y-2">
                        <div class="w-full bg-luxury-maroon/20 hover:bg-luxury-maroon transition rounded-t" style="height: 60px;" title="Feb Sales: ₹60,000"></div>
                        <span>Feb</span>
                    </div>
                    <div class="flex flex-col items-center w-1/12 space-y-2">
                        <div class="w-full bg-luxury-maroon/20 hover:bg-luxury-maroon transition rounded-t" style="height: 80px;" title="Mar Sales: ₹80,000"></div>
                        <span>Mar</span>
                    </div>
                    <div class="flex flex-col items-center w-1/12 space-y-2">
                        <div class="w-full bg-luxury-maroon/20 hover:bg-luxury-maroon transition rounded-t" style="height: 100px;" title="Apr Sales: ₹100,000"></div>
                        <span>Apr</span>
                    </div>
                    <div class="flex flex-col items-center w-1/12 space-y-2">
                        <div class="w-full bg-luxury-maroon/40 hover:bg-luxury-maroon transition rounded-t" style="height: 120px;" title="May Sales: ₹120,000"></div>
                        <span>May</span>
                    </div>
                    <div class="flex flex-col items-center w-1/12 space-y-2">
                        <div class="w-full bg-luxury-maroon/80 hover:bg-luxury-maroon transition rounded-t" style="height: 160px;" title="Jun Sales: ₹160,000"></div>
                        <span>Jun</span>
                    </div>
                </div>
                <div class="text-center text-[10px] text-gray-400">Values are shown in thousands of rupees.</div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="bg-white border border-gray-100 rounded-lg p-6 shadow-sm space-y-4">
                <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon border-b pb-2"><i class="bi bi-pie-chart mr-2"></i> Category Breakdown</h3>
                <div class="space-y-4 text-xs">
                    <div class="space-y-1">
                        <div class="flex justify-between">
                            <span class="font-semibold text-luxury-charcoal">Sarees (Banarasi / Cotton)</span>
                            <span class="font-bold text-luxury-gold">65%</span>
                        </div>
                        <div class="w-full bg-gray-100 h-2 rounded overflow-hidden">
                            <div class="bg-luxury-gold h-full" style="width: 65%"></div>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <div class="flex justify-between">
                            <span class="font-semibold text-luxury-charcoal">Salwar Suits</span>
                            <span class="font-bold text-luxury-maroon">25%</span>
                        </div>
                        <div class="w-full bg-gray-100 h-2 rounded overflow-hidden">
                            <div class="bg-luxury-maroon h-full" style="width: 25%"></div>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <div class="flex justify-between">
                            <span class="font-semibold text-luxury-charcoal">Unstitched Dress Materials</span>
                            <span class="font-bold text-gray-500">10%</span>
                        </div>
                        <div class="w-full bg-gray-100 h-2 rounded overflow-hidden">
                            <div class="bg-gray-400 h-full" style="width: 10%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
