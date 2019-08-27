@extends('layouts.app')
@section('content')
    {{--<div>--}}


    {{--</div>--}}

    <header class="d-flex align-items-end justify-content-between py-4 mb-3">
        <h2 class="no-underline text-grey text-md">
            <a class="no-underline text-grey" href="/projects">My Projects</a> / {{$project->title}}
        </h2>

        <div class="d-flex align-items-center">

            @foreach($project->members as $member)
                <img
                        src="{{gravatar_url($member->email)}}"
                        alt="{{$member->name}}'s avatar"
                        class="rounded-circle w-40 mr-2">

            @endforeach
                <img
                        src="{{gravatar_url($project->owner->email)}}"
                        alt="{{$project->owner->name}}'s avatar"
                        class="rounded-circle w-40 mr-2">

            <a href="{{$project->path().'/edit'}}" class="button py-2 px-4 no-underline ml-4">Edit Project</a>

        </div>
    </header>

    <main>
        <div class="d-flex -mx-3 row">
            <div class="col-sm-12 col-lg-8 px-3 mb-5">
                <div class="mb-5">
                    <h2 class="no-underline text-grey text-md">Tasks</h2>
                    @foreach($project->tasks as $task)
                        <div class="card mb-3">
                            <form method="POST" action="{{$task->path()}}">
                                @method('PATCH')
                                @csrf
                                <div class="d-flex align-items-center">
                                    <input name="body" class="form-control-plaintext w-100  {{$task->completed ? 'text-grey line-through' : ''}}" value="{{$task->body}}"/>
                                    <input type="checkbox" class="form-check-inline" name="completed" onchange="this.form.submit()" {{$task->completed ? 'checked' : ''}}/>
                                </div>

                            </form>
                        </div>
                    @endforeach
                    <div class="card mb-3">
                        <form method="POST" action="{{$project->path() . '/tasks'}}">
                            @csrf
                            <input class="form-control-plaintext w-100" placeholder="Add a new task..." name="body" />
                        </form>
                    </div>



                </div>
                <!-- Tasks -->

                <!-- General Notes -->
                <div>
                    <h2 class="no-underline text-grey text-md">General Notes</h2>

                    <form action="{{$project->path()}}" method="POST">
                        @method("PUT")
                        @csrf
                        <textarea
                                name="notes"
                                class="card w-100 h-150 mb-3"
                        placeholder="Anything spacial you want to make note of?">{{$project->notes}}</textarea>
                        <button class="btn-primary btn" type="submit">Save</button>
                    </form>

                    @include('errors')
                </div>


            </div>
            <div class="col-sm-12 col-lg-4  px-3" >
                @include('projects.card')
                @include('projects.activity.card')

                @can('manage',$project)
                    @include('projects.invite')
                @endcan



            </div>
        </div>
    </main>

@endsection