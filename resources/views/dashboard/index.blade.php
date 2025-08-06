@extends('dashboard.partials.master')

@section('title')
@endsection

@section('css')
@endsection

@section('titlepage')
@endsection

@section('breadcumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('dashboard.home') }}</a></li>
@endsection

@section('breadcumbactive')
    <li class="breadcrumb-item active" aria-current="page"></li>
@endsection

@section('content')
    <div class="bg-white p-4">
        <h2 class="mb-2 page-title">{{ trans('general.welcome') }} {{ auth('admin')->user()->name }}</h2>

        <div class="container my-5">






        </div>


        <!-- End Counter -->



    </div>
@endsection

@section('js')
@endsection
