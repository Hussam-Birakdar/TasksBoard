@if($errors->{ $bag ?? 'default' }->any())
    <ul class="mt-4 list-unstyled">
        @foreach($errors->{ $bag ?? 'default' }->all() as $error)
            <li class="text-red">{{$error}}</li>
        @endforeach
    </ul>

@endif