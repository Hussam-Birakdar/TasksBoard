<div class="card project-card" style="height: 200px;">
    <h3 class="font-weight-normal mb-3 py-4">
        <a href="{{$project->path()}}" class="text-black no-underline">{{$project->title}}</a>
    </h3>
    <div class="text-grey mb-4">
        {{$project->description}}
    </div>

    <footer>
        <form method="POST" action="{{$project->path()}}" class="text-right">
            @method('DELETE')
            @csrf
            <button type="submit" class="text-sm-right">Delete</button>
        </form>
    </footer>
</div>
