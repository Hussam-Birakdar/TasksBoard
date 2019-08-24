<div class="card mt-3">
    <ul class="list-unstyled">
        @foreach($project->activity as $activity)
            <li class="{{$loop->last? '': 'mb-1'}}">
                @include("projects.activity.{$activity->description}")
                <span class="text-grey">{{$activity->created_at->diffForHumans()}}</span>
            </li>
        @endforeach
    </ul>

</div>