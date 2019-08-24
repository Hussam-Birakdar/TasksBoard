@extends('layouts.app')
@section('content')
    <div class="  col-lg-6 form card mr-auto ml-auto ">
        <h1 class="justify-content-center text-center">Lets' start something new!</h1>
        <form class="card-body" method="POST" action="/projects">
            @csrf
    @include('projects.form',['project' => new \App\Project(),'buttonText'=> 'Create Project'])
        </form>
    </div>



@endsection