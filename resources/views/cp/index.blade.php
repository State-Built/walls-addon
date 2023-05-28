@extends('statamic::layout')
@section('title', 'Gates')

@section('content')

    <div class="flex mb-3">
        <h1 class="flex-1">Gates</h1>

        <a href="{{ cp_route('gated.create') }}" class="btn-primary">Create</a>
    </div>

    {{-- todo: add list --}}

    {{--
        @include('statamic::partials.create-first', [
            'resource' => 'Gate',
            'description' => 'Creates Gate',
            'svg' => 'empty/collection',
            'route' => cp_route('gated.create'),
            'can' => auth()->user()->can('create', '')
        ])
      --}}

@endsection