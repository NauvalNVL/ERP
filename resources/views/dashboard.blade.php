@extends('layouts.app')

@section('title', 'Dashboard - ERP System')

@section('header', 'Dashboard')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <h3 class="text-lg font-medium mb-4">Selamat Datang, {{ Auth::user()->name }}</h3>
    <p class="text-gray-600">Silakan pilih menu di sidebar untuk memulai.</p>
</div>
@endsection 