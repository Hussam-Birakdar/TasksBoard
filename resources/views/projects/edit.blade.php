@extends('layouts.app')
@section('content')

    <div class="  col-lg-6 form card mr-auto ml-auto ">
        <h1 class="justify-content-center text-center">Edit your project</h1>
        <form class="card-body" method="POST" action="{{$project->path()}}">
            @method("PATCH")
            @csrf
            @include('projects.form',['buttonText'=> 'Update Project'])
        </form>
    </div>

@endsection

