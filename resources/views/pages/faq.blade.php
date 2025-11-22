@extends('layouts.app')

@section('title', $faq->question . ' | Unloqit FAQ')
@section('meta_description', strip_tags(substr($faq->answer, 0, 160)))
@section('canonical', route('faq.show', ['slug' => $faq->slug]))

@section('meta_extra')
<meta property="og:title" content="{{ $faq->question }} | Unloqit FAQ">
<meta property="og:description" content="{{ strip_tags(substr($faq->answer, 0, 160)) }}">
<meta property="og:url" content="{{ route('faq.show', ['slug' => $faq->slug]) }}">
<meta property="og:type" content="article">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="{{ $faq->question }} | Unloqit FAQ">
<meta name="twitter:description" content="{{ strip_tags(substr($faq->answer, 0, 160)) }}">
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

<!-- FAQ Content -->
<article class="py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-4xl font-bold mb-6">{{ $faq->question }}</h1>
            <div class="prose prose-lg max-w-none">
                <p class="text-lg text-brand-dark-60 leading-relaxed">
                    {!! nl2br(e($faq->answer)) !!}
                </p>
            </div>
        </div>
    </div>
</article>
@endsection

