
@extends('statamic::layout')

@section('title', 'Create Gate')
@section('wrapper_class', 'max-w-3xl')

@section('content')
    <publish-form
            title="Create Gate"
            action="{{ cp_route('gated.store') }}"
            :blueprint='@json($blueprint)'
            :meta='@json($meta)'
            :values='@json($values)'
    ></publish-form>
@endsection