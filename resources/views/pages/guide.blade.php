@extends('layouts.app')

@section('title', $guide->title . ' | Unloqit')
@section('meta_description', strip_tags(substr($guide->content, 0, 160)))
@section('canonical', route('guide.show', ['slug' => $guide->slug]))

@section('meta_extra')
<meta property="og:title" content="{{ $guide->title }} | Unloqit">
<meta property="og:description" content="{{ strip_tags(substr($guide->content, 0, 160)) }}">
<meta property="og:url" content="{{ route('guide.show', ['slug' => $guide->slug]) }}">
<meta property="og:type" content="article">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $guide->title }} | Unloqit">
<meta name="twitter:description" content="{{ strip_tags(substr($guide->content, 0, 160)) }}">
@endsection

@section('jsonld')
<script type="application/ld+json">{!! $jsonld !!}</script>
@endsection

@section('content')
<!-- Breadcrumbs -->
<nav class="bg-brand-light py-3">
    <div class="container mx-auto px-4">
        <ol class="flex items-center space-x-2 text-sm">
            @foreach($breadcrumbs as $crumb)
            <li>
                <a href="{{ $crumb['url'] }}" class="text-brand-accent hover:text-brand-accent-80">{{ $crumb['name'] }}</a>
            </li>
            @if(!$loop->last)
            <li class="text-brand-gray">/</li>
            @endif
            @endforeach
        </ol>
    </div>
</nav>

<!-- Guide Content -->
<article class="py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-4xl font-bold mb-6">{{ $guide->title }}</h1>
            <div class="prose prose-lg max-w-none">
                {!! nl2br(e($guide->content)) !!}
            </div>
        </div>
    </div>
</article>
@endsection

