<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Ansari Handloom</title>
    <!-- Local Bootstrap 5 CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}">
    <!-- Local Tailwind CSS -->
    <script src="{{ asset('vendor/tailwind/tailwind.js') }}"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        luxury: {
                            gold: '#D4AF37',
                            maroon: '#5B1123',
                            maroonlight: '#721D32',
                            charcoal: '#1A1A1A',
                            cream: '#FCF9F2'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .gold-gradient-text {
            background: linear-gradient(90deg, #F5E6D3 0%, #D4AF37 50%, #F5E6D3 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="bg-luxury-charcoal min-h-screen flex items-center justify-center px-4">

    <div class="max-w-md w-full bg-[#222] border border-luxury-gold/20 rounded-lg p-8 shadow-2xl space-y-6">
        <div class="text-center space-y-2">
            <span class="text-3xl font-serif font-bold gold-gradient-text tracking-widest block">ANSARI</span>
            <span class="text-xxs uppercase tracking-widest text-luxury-gold font-bold block border-t border-b border-luxury-gold/20 py-1.5 mx-auto w-40">Admin Panel</span>
        </div>

        @if(session('error'))
            <div class="alert alert-danger text-xs rounded border border-red-500/30 py-2.5 px-4 bg-red-950/20 text-red-400">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="text-xs text-luxury-gold uppercase tracking-wider font-semibold mb-1.5 block">Admin Email</label>
                <input type="email" name="email" required placeholder="admin@ansarihandloom.com" value="admin@ansarihandloom.com" class="w-full bg-[#1A1A1A] border border-luxury-gold/20 text-white rounded px-3 py-2 text-sm outline-none focus:border-luxury-gold transition">
            </div>

            <div>
                <label class="text-xs text-luxury-gold uppercase tracking-wider font-semibold mb-1.5 block">Password</label>
                <input type="password" name="password" required placeholder="••••••••" value="admin123" class="w-full bg-[#1A1A1A] border border-luxury-gold/20 text-white rounded px-3 py-2 text-sm outline-none focus:border-luxury-gold transition">
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-luxury-maroon hover:bg-luxury-maroonlight text-luxury-cream font-bold py-3 rounded-full uppercase tracking-wider text-xs transition shadow-lg border border-luxury-gold/20">
                    Authenticate
                </button>
            </div>
        </form>

        <div class="text-center">
            <a href="{{ route('home') }}" class="text-xxs text-luxury-gold/50 hover:text-luxury-gold transition no-underline">&larr; Return to main store</a>
        </div>
    </div>

</body>
</html>
