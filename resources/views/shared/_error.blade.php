@if(count($errors) > 0)
    <div class="alert alert-danger">
        <div>有错误发生：</div>
        <ul>
            @foreach($errors->all() as $error)
            <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
@endif