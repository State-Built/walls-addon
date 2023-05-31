@extends('statamic::layout')
@section('title', 'Walls')

@section('content')

    <div class="flex mb-3">
        <h1 class="flex-1">Walls</h1>

        <a href="{{ cp_route('walls.create') }}" class="btn-primary">Create</a>
    </div>

    {{-- todo: add list --}}

    {{--
        @include('statamic::partials.create-first', [
            'resource' => 'Wall',
            'description' => 'Creates Wall',
            'svg' => 'empty/collection',
            'route' => cp_route('walls.create'),
            'can' => auth()->user()->can('create', '')
        ])
      --}}

@endsection