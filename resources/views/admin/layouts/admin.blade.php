<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Console - Ansari Handloom')</title>
    
    <!-- Local Bootstrap 5 CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}">
    <!-- Local Bootstrap Icons -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    
    <!-- Local Tailwind CSS -->
    <script src="{{ asset('vendor/tailwind/tailwind.js') }}"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        luxury: {
                            gold: '#D4AF37',
                            goldhover: '#C59B27',
                            maroon: '#5B1123',
                            maroonlight: '#721D32',
                            charcoal: '#1A1A1A',
                            charcoallight: '#2A2A2A',
                            cream: '#FCF9F2',
                            beige: '#F5E6D3'
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        .admin-sidebar {
            background-color: #1A1A1A;
            border-right: 1px solid rgba(212, 175, 55, 0.15);
        }
        .admin-header {
            background-color: #ffffff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        .gold-gradient-text {
            background: linear-gradient(90deg, #F5E6D3 0%, #D4AF37 50%, #F5E6D3 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.25rem;
            color: rgba(255, 255, 255, 0.7);
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .sidebar-link:hover, .sidebar-link.active {
            color: #D4AF37;
            background-color: rgba(91, 17, 35, 0.2);
            border-left-color: #D4AF37;
            text-decoration: none;
        }
    </style>
    @yield('styles')
</head>
<body class="bg-gray-50 text-luxury-charcoal font-sans min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar Navigation -->
    <aside class="admin-sidebar md:w-64 flex-shrink-0 flex flex-col justify-between">
        <div>
            <!-- Sidebar Header -->
            <div class="h-20 flex items-center justify-center border-b border-luxury-gold/10 px-6">
                <a href="{{ route('admin.dashboard') }}" class="no-underline flex items-center space-x-2">
                    <span class="text-xl font-serif font-bold gold-gradient-text tracking-wider">ANSARI</span>
                    <span class="text-xxs uppercase bg-luxury-gold text-luxury-charcoal px-2 py-0.5 rounded font-bold">Admin</span>
                </a>
            </div>

            <!-- Sidebar Navigation Links -->
            <nav class="mt-6 flex flex-col space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 mr-3 text-lg"></i> Dashboard
                </a>
                <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ Route::is('admin.products.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam mr-3 text-lg"></i> Products Catalog
                </a>
                <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ Route::is('admin.categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tags mr-3 text-lg"></i> Categories
                </a>
                <a href="{{ route('admin.attributes.index') }}" class="sidebar-link {{ Route::is('admin.attributes.*') ? 'active' : '' }}">
                    <i class="bi bi-sliders mr-3 text-lg"></i> Attributes Master
                </a>
                <a href="{{ route('admin.attribute-sets.index') }}" class="sidebar-link {{ Route::is('admin.attribute-sets.*') ? 'active' : '' }}">
                    <i class="bi bi-collection mr-3 text-lg"></i> Attribute Sets
                </a>
                <a href="{{ route('admin.inventory.index') }}" class="sidebar-link {{ Route::is('admin.inventory.*') ? 'active' : '' }}">
                    <i class="bi bi-graph-up-arrow mr-3 text-lg"></i> Inventory Stock
                </a>
                <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ Route::is('admin.orders.*') && !Route::is('admin.customers.*') ? 'active' : '' }}">
                    <i class="bi bi-receipt-cutoff mr-3 text-lg"></i> Customer Orders
                </a>
                <a href="{{ route('admin.customers.index') }}" class="sidebar-link {{ Route::is('admin.customers.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill mr-3 text-lg"></i> Customer Directory
                </a>
                <a href="{{ route('admin.vendors.index') }}" class="sidebar-link {{ Route::is('admin.vendors.*') || Route::is('admin.po.*') ? 'active' : '' }}">
                    <i class="bi bi-truck mr-3 text-lg"></i> Vendors & PO
                </a>
                <a href="{{ route('admin.warehouses.index') }}" class="sidebar-link {{ Route::is('admin.warehouses.*') ? 'active' : '' }}">
                    <i class="bi bi-house-door-fill mr-3 text-lg"></i> Warehouses
                </a>
                <a href="{{ route('admin.accounting.index') }}" class="sidebar-link {{ Route::is('admin.accounting.*') ? 'active' : '' }}">
                    <i class="bi bi-wallet2 mr-3 text-lg"></i> Accounting & Bank
                </a>
                <a href="{{ route('admin.marketing.index') }}" class="sidebar-link {{ Route::is('admin.marketing.*') ? 'active' : '' }}">
                    <i class="bi bi-megaphone mr-3 text-lg"></i> Marketing & CMS
                </a>
                <a href="{{ route('admin.employees.index') }}" class="sidebar-link {{ Route::is('admin.employees.*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge mr-3 text-lg"></i> Staff Directory
                </a>
                <a href="{{ route('admin.reports.index') }}" class="sidebar-link {{ Route::is('admin.reports.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-line-fill mr-3 text-lg"></i> Reports & Insights
                </a>
                <a href="{{ route('home') }}" target="_blank" class="sidebar-link">
                    <i class="bi bi-globe mr-3 text-lg"></i> Visit Storefront <i class="bi bi-box-arrow-up-right ml-1 text-xs"></i>
                </a>
            </nav>
        </div>

        <!-- Sidebar Footer Account Details -->
        <div class="border-t border-luxury-gold/10 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-luxury-maroon text-luxury-gold flex items-center justify-center font-bold shadow-sm">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="text-xs">
                        <span class="text-white font-semibold block leading-none">{{ Auth::user()->name ?? 'Admin Staff' }}</span>
                        <span class="text-luxury-gold/75 text-[10px] mt-1 block uppercase font-bold tracking-wider">{{ Auth::user()->role->name ?? 'Staff' }}</span>
                    </div>
                </div>
                <a href="{{ route('admin.logout') }}" class="text-luxury-gold hover:text-red-500 transition text-lg" title="Log Out">
                    <i class="bi bi-box-arrow-right"></i>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content Container -->
    <div class="flex-grow flex flex-col min-w-0">
        <!-- Top Header Navigation -->
        <header class="admin-header h-20 px-8 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold font-serif text-luxury-maroon">@yield('page_title', 'System Console')</h2>
            </div>
            
            <div class="flex items-center space-x-6 text-sm">
                <!-- Date Display -->
                <span class="text-xs text-gray-500 font-semibold hidden md:inline-block">
                    <i class="bi bi-calendar-event mr-1 text-luxury-gold"></i>
                    {{ date('l, d F Y') }}
                </span>
                
                <!-- Status Bar -->
                <div class="flex items-center space-x-2 text-xxs font-bold uppercase tracking-wider text-green-700 bg-green-50 px-3 py-1.5 rounded-full border border-green-200">
                    <span class="w-2 h-2 rounded-full bg-green-600 animate-pulse"></span>
                    <span>System Online</span>
                </div>
            </div>
        </header>

        <!-- Main Body Workspace -->
        <main class="p-8 flex-grow">
            @if(session('success'))
                <div class="alert alert-success text-sm rounded border border-green-300 py-3 px-4 bg-green-50 text-green-800 mb-6 flex items-center shadow-sm">
                    <i class="bi bi-check-circle-fill text-lg mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger text-sm rounded border border-red-300 py-3 px-4 bg-red-50 text-red-800 mb-6 flex items-center shadow-sm">
                    <i class="bi bi-exclamation-triangle-fill text-lg mr-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Local Bootstrap JS -->
    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <!-- Local jQuery -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    
    @yield('scripts')
</body>
</html>
