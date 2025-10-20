@extends('request::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('request.name') !!}</p>
@endsection
