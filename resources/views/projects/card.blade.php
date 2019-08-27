<div class="card d-flex flex-column project-card">
    <h3 class="font-weight-normal mb-3 py-4">
        <a href="{{$project->path()}}" class="text-black no-underline">{{$project->title}}</a>
    </h3>
    <div class="text-grey mb-4 flex-grow-1">
        {{$project->description}}
    </div>


    @can ('manage',$project)
    <footer>
        <form method="POST" action="{{$project->path()}}" class="text-right">
            @method('DELETE')
            @csrf
            <button type="submit" class="text-sm-right btn btn-danger">Delete</button>
        </form>
    </footer>
    @endcan
</div>
