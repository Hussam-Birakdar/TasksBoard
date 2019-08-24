
    <div class="form-group">
        <label for="title">Title</label>
        <input id="title" class="form-control" name="title" type="text" placeholder="Project title" required value="{{$project->title}}">
    </div>

    <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" class="form-control" name="description" placeholder="Project description" required type="text" >{{$project->description}}</textarea>
    </div>

    <div class="form-group">
        <button class="btn btn-primary" type="submit">{{$buttonText}}</button>
        <a href="/projects" class="">Cancel</a>
    </div>

    @if($errors->any())
    <div class="alert">
            @foreach($errors->all() as $error)
                <li class="text-red">{{$error}}</li>
            @endforeach
    </div>
    @endif