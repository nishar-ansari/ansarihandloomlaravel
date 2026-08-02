@extends('layouts.app')

@section('title', 'Weaver Stories - Ansari Handloom')

@section('content')
<div class="bg-luxury-beige/25 py-8 border-b border-luxury-gold/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-serif font-bold text-luxury-maroon">Weaver Stories</h1>
        <p class="text-xs text-luxury-charcoal/50 mt-1">Read articles on the rich history of Varanasi handloom crafts and care instructions.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @foreach($posts as $post)
            <div class="bg-white border border-luxury-gold/10 rounded-lg overflow-hidden shadow-sm flex flex-col md:flex-row group transition hover:shadow-md">
                <!-- Image -->
                <div class="md:w-1/3 aspect-[4/3] md:aspect-auto relative bg-luxury-beige/10">
                    <img src="{{ $post->image ? asset('images/' . $post->image) : asset('images/saree_red.jpg') }}" 
                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-102 transition duration-500" 
                         alt="{{ $post->title }}">
                </div>

                <!-- Text -->
                <div class="p-6 md:w-2/3 flex flex-col justify-between">
                    <div>
                        <span class="text-xxs font-bold text-luxury-gold uppercase tracking-wider block mb-1">Weaving Heritage</span>
                        <h3 class="text-lg font-serif font-bold text-luxury-maroon group-hover:text-luxury-gold transition duration-300">
                            <a href="{{ route('blog.show', $post->slug) }}" class="no-underline text-inherit">{{ $post->title }}</a>
                        </h3>
                        <span class="text-[10px] text-gray-400 mt-1 block">Published on {{ $post->created_at->format('M d, Y') }}</span>
                        <p class="text-xs text-luxury-charcoal/70 mt-3 line-clamp-3">{{ Str::limit(strip_tags($post->content), 120) }}</p>
                    </div>

                    <div class="pt-4 border-t border-luxury-gold/5 mt-4">
                        <a href="{{ route('blog.show', $post->slug) }}" class="text-xs font-semibold text-luxury-maroon hover:text-luxury-gold no-underline">Read Full Story &rarr;</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
