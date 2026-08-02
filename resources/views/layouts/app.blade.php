<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ansari Handloom - Handwoven Sarees & Suits')</title>
    
    <!-- Local Bootstrap 5 CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}">
    <!-- Local Bootstrap Icons -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    
    <!-- Local Tailwind CSS Standalone -->
    <script src="{{ asset('vendor/tailwind/tailwind.js') }}"></script>
    
    <!-- Configure Tailwind for custom colors & design system -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        luxury: {
                            gold: '#D4AF37',      /* Metallic Gold */
                            goldhover: '#C59B27',
                            maroon: '#5B1123',    /* Deep Maroon */
                            maroonlight: '#721D32',
                            cream: '#FCF9F2',     /* Soft Silk Cream */
                            charcoal: '#1A1A1A',  /* Dark Charcoal */
                            beige: '#F5E6D3'      /* Warm Linen Beige */
                        }
                    },
                    fontFamily: {
                        sans: ['system-ui', '-apple-system', 'Segoe UI', 'Roboto', 'sans-serif'],
                        serif: ['Georgia', 'ui-serif', 'serif']
                    }
                }
            }
        }
    </script>
    
    <style>
        /* Glassmorphism custom styling */
        .glass-header {
            background: rgba(91, 17, 35, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .luxury-gradient {
            background: linear-gradient(135deg, #5B1123 0%, #1A1A1A 100%);
        }
        .gold-gradient-text {
            background: linear-gradient(90deg, #F5E6D3 0%, #D4AF37 50%, #F5E6D3 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .nav-link-custom {
            color: #FCF9F2;
            transition: all 0.3s ease;
        }
        .nav-link-custom:hover {
            color: #D4AF37;
            transform: translateY(-1px);
        }
    </style>
    @yield('styles')
</head>
<body class="bg-luxury-cream text-luxury-charcoal font-sans min-h-screen flex flex-col">

    <!-- Top Announcement Bar -->
    <div class="bg-luxury-gold text-luxury-charcoal text-center py-2 px-4 text-xs font-semibold uppercase tracking-wider">
        <i class="bi bi-gift-fill mr-1"></i> Special Launch Offer: Get 10% Off on all handwoven Banarasi Sarees! Code: <span class="underline">ANSARI10</span>
    </div>

    <!-- Main Navigation Header -->
    <header class="glass-header text-luxury-cream sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2 no-underline">
                        <span class="text-3xl font-serif font-bold gold-gradient-text tracking-wide">ANSARI</span>
                        <span class="text-xs uppercase border-l border-luxury-gold pl-2 tracking-widest text-luxury-gold hidden sm:inline-block font-sans">Handloom<br>Est. 2026</span>
                    </a>
                </div>

                <!-- Main Menu Links -->
                <nav class="hidden md:flex space-x-6 text-sm font-medium">
                    <a href="{{ route('home') }}" class="nav-link-custom no-underline">Home</a>
                    <a href="{{ route('products.index') }}" class="nav-link-custom no-underline">Shop All</a>
                    <a href="{{ route('products.index', ['category' => 'sarees']) }}" class="nav-link-custom no-underline">Sarees</a>
                    <a href="{{ route('products.index', ['category' => 'suits']) }}" class="nav-link-custom no-underline">Suits</a>
                    <a href="{{ route('blog.index') }}" class="nav-link-custom no-underline">Weaver Stories</a>
                    <a href="{{ route('about') }}" class="nav-link-custom no-underline">Our Story</a>
                    <a href="{{ route('contact') }}" class="nav-link-custom no-underline">Contact Us</a>
                </nav>

                <!-- Search and User Panel Actions -->
                <div class="flex items-center space-x-6">
                    <form action="{{ route('products.index') }}" method="GET" class="hidden lg:flex items-center bg-luxury-maroonlight border border-luxury-gold/30 rounded-full px-3 py-1 text-sm">
                        <input type="text" name="search" placeholder="Search sarees, suits..." class="bg-transparent text-luxury-cream placeholder-luxury-cream/50 outline-none w-48 px-2 py-1">
                        <button type="submit" class="text-luxury-gold hover:text-luxury-cream transition"><i class="bi bi-search"></i></button>
                    </form>

                    <!-- Cart and Auth Links -->
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('cart.index') }}" class="relative text-luxury-cream hover:text-luxury-gold transition text-xl no-underline">
                            <i class="bi bi-bag-fill"></i>
                            <span class="absolute -top-2 -right-2 bg-luxury-gold text-luxury-charcoal rounded-full text-xs font-bold w-5 h-5 flex items-center justify-content-center shadow" id="cart-badge">
                                {{ session('cart') ? count(session('cart')) : 0 }}
                            </span>
                        </a>

                        @auth('customer')
                            <div class="dropdown">
                                <a class="text-luxury-cream hover:text-luxury-gold transition text-xl no-underline cursor-pointer" id="userMenu" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end bg-luxury-maroon border border-luxury-gold/30 mt-2 rounded shadow">
                                    <li><span class="dropdown-item-text text-luxury-cream/70 text-xs">Signed in as: <strong class="text-luxury-cream">{{ auth('customer')->user()->name }}</strong></span></li>
                                    <li><hr class="dropdown-divider border-luxury-gold/10"></li>
                                    <li><a class="dropdown-item text-luxury-cream hover:bg-luxury-maroonlight hover:text-luxury-gold py-2" href="{{ route('customer.profile') }}"><i class="bi bi-person-lines-fill mr-2"></i> My Dashboard</a></li>
                                    <li><a class="dropdown-item text-luxury-cream hover:bg-luxury-maroonlight hover:text-luxury-gold py-2" href="{{ route('customer.logout') }}"><i class="bi bi-box-arrow-right mr-2"></i> Logout</a></li>
                                </ul>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold border border-luxury-gold hover:bg-luxury-gold hover:text-luxury-charcoal px-4 py-1.5 rounded-full transition no-underline text-luxury-gold">
                                Login
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer Section -->
    <footer class="bg-luxury-charcoal text-luxury-cream/80 border-t border-luxury-gold/20 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- About Info -->
            <div class="space-y-4">
                <a href="{{ route('home') }}" class="no-underline"><span class="text-2xl font-serif font-bold gold-gradient-text">ANSARI</span></a>
                <p class="text-xs text-justify">
                    Ansari Handloom is committed to preserving the heritage of traditional weavers. We create and curate exquisite handwoven sarees, suits, and dress materials representing centuries of craftsmanship and weaving elegance.
                </p>
                <div class="flex space-x-4 pt-2">
                    <a href="#" class="text-luxury-gold hover:text-luxury-cream transition"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-luxury-gold hover:text-luxury-cream transition"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-luxury-gold hover:text-luxury-cream transition"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>

            <!-- Shop Categories Link -->
            <div class="space-y-4">
                <h4 class="text-sm font-semibold uppercase tracking-wider text-luxury-gold font-serif">Quick Shop</h4>
                <ul class="space-y-2 text-xs list-none p-0">
                    <li><a href="{{ route('products.index', ['category' => 'sarees']) }}" class="hover:text-luxury-gold transition no-underline text-luxury-cream/80">Banarasi Silk Sarees</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'suits']) }}" class="hover:text-luxury-gold transition no-underline text-luxury-cream/80">Designer Salwar Suits</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'dress-materials']) }}" class="hover:text-luxury-gold transition no-underline text-luxury-cream/80">Silk Dress Materials</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'dupattas']) }}" class="hover:text-luxury-gold transition no-underline text-luxury-cream/80">Fine Dupattas</a></li>
                </ul>
            </div>

            <!-- Customer Service -->
            <div class="space-y-4">
                <h4 class="text-sm font-semibold uppercase tracking-wider text-luxury-gold font-serif">Our Policies</h4>
                <ul class="space-y-2 text-xs list-none p-0">
                    <li><a href="#" class="hover:text-luxury-gold transition no-underline text-luxury-cream/80">About Our Weavers</a></li>
                    <li><a href="#" class="hover:text-luxury-gold transition no-underline text-luxury-cream/80">Shipping & Delivery</a></li>
                    <li><a href="#" class="hover:text-luxury-gold transition no-underline text-luxury-cream/80">Returns & Exchanges</a></li>
                    <li><a href="#" class="hover:text-luxury-gold transition no-underline text-luxury-cream/80">Privacy & Terms</a></li>
                </ul>
            </div>

            <!-- Contact and Newsletter -->
            <div class="space-y-4">
                <h4 class="text-sm font-semibold uppercase tracking-wider text-luxury-gold font-serif">Contact Info</h4>
                <p class="text-xs">
                    <i class="bi bi-geo-alt-fill text-luxury-gold mr-2"></i> Ansari Handloom, Weaver Colony, Varanasi, UP
                </p>
                <p class="text-xs">
                    <i class="bi bi-envelope-fill text-luxury-gold mr-2"></i> support@ansarihandloom.com
                </p>
                <p class="text-xs">
                    <i class="bi bi-telephone-fill text-luxury-gold mr-2"></i> +91 98765 43210
                </p>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 border-t border-luxury-cream/10 mt-12 pt-8 text-center text-xs">
            <p>&copy; 2026 Ansari Handloom ERP + eCommerce. Designed by Technexes. All rights reserved.</p>
        </div>
    </footer>

    <!-- Local Bootstrap JS -->
    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <!-- Local jQuery -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    
    @yield('scripts')
</body>
</html>
