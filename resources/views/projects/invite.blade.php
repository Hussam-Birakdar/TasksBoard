<div class="card d-flex flex-column project-card mt-3">
    <h3 class="font-weight-normal mb-3 py-4">
        Invite a User
    </h3>

    <footer>
        <form method="POST" action="{{$project->path().'/invitations'}}" >
            @csrf
            <div class="mb-3">
                <input type="email" name="email" class="rounded border w-100 py-2 px-3" placeholder="Email address">

            </div>
            <button type="submit" class="btn-primary btn">Invite</button>
        </form>

        @include('errors',['bag' => 'invitations'])
    </footer>
</div>