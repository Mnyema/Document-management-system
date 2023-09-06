<!DOCTYPE html>

<body>
    <form method="POST" action="{{route('posts.update', $post->id)}}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="file" name="file" value="{{$post->file}}" required />
        <button type="submit"> Update</button>
    </form>

    
</body>

<html>