@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                <a href="{{ url('/admin/dashboard') }}">{{ __('Admin Dashboard') }}</a>
                |
                <a href="{{ url('/admin/posts') }}">{{ __('Posts') }}</a>
                </div>

                <div class="card-body">
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection