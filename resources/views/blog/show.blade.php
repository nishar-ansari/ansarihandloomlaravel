@extends('layouts.app')

@section('title', $post->title . ' - Ansari Handloom')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="space-y-6">
        <!-- Back to blogs link -->
        <a href="{{ route('blog.index') }}" class="text-xs font-semibold text-luxury-gold hover:text-luxury-maroon no-underline transition">
            &larr; Back to Weaver Stories
        </a>

        <!-- Title block -->
        <div class="space-y-2">
            <span class="text-xs uppercase tracking-widest text-luxury-gold font-bold block">Apparel & Weaving Legacy</span>
            <h1 class="text-3xl md:text-5xl font-serif font-bold text-luxury-maroon leading-tight">{{ $post->title }}</h1>
            <div class="flex items-center space-x-4 text-xs text-gray-400 font-semibold border-t border-b border-luxury-gold/15 py-3">
                <span>By: {{ $post->author->name ?? 'Ansari Handloom' }}</span>
                <span>&bull;</span>
                <span>{{ $post->created_at->format('d F Y') }}</span>
            </div>
        </div>

        <!-- Featured Image -->
        @if($post->image)
            <div class="rounded-lg overflow-hidden aspect-[16/9] shadow-sm border border-luxury-gold/10">
                <img src="{{ asset('images/' . $post->image) }}" class="w-full h-full object-cover" alt="{{ $post->title }}">
            </div>
        @endif

        <!-- Content -->
        <article class="prose max-w-none text-luxury-charcoal/80 text-justify text-sm leading-relaxed space-y-4 font-serif">
            {!! nl2br(e($post->content)) !!}
        </article>
    </div>
</div>
@endsection
