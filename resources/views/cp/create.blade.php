
@extends('statamic::layout')

@section('title', 'Create Wall')
@section('wrapper_class', 'max-w-3xl')

@section('content')
    <publish-form
            title="Create Wall"
            action="{{ cp_route('walls.store') }}"
            :blueprint='@json($blueprint)'
            :meta='@json($meta)'
            :values='@json($values)'
    ></publish-form>
@endsection