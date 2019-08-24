@extends('layouts.app')
@section('content')

    <header class="d-flex align-items-end justify-content-between py-4">
        <h2 class="no-underline text-grey text-md">My Projects</h2>
        <a href="/projects/create" class="button py-2 px-4 no-underline">New Project</a>
    </header>

    <main class="d-flex flex-wrap -mx-3">
        @forelse($projects as $project)
            <div class="col-lg-4 px-3 pb-5">
                @include('projects.card')
            </div>
        @empty
            <div>
                No Projects Yet.
            </div>
        @endforelse
    </main>

@endsection