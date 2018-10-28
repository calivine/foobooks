@extends('layouts.master')

@section('content')
    <h1>Welcome to {{ config('app.name') }}</h1>

    <p>
        @include('modules.description')
    </p>

@endsection
