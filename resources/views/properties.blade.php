@extends('layouts.master')

@section('title', 'Properties')

@section('content')
    @include('partials.properties.hero-map')
    @include('partials.properties.listings-grid')
    
    @include('partials.layout.footer')
@endsection
