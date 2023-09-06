<!DOCTYPE html>

<body>
    <form method="POST" action="{{route('posts.store')}}" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required />
        <button type="submit"> Upload</button>
    </form>
    @foreach($posts as $post)
    @if($post->hasMedia('docs'))
    {{-- <div>{{$post->getFirstMediaUrl('docs')}}</div> --}}
    <div>{{ pathinfo($post->getFirstMedia('docs')->file_name, PATHINFO_FILENAME) }}</div>
    <p>File Size: {{ formatBytes($post->getFirstMedia('docs')->size) }}</p>
    <p>Uploaded At: {{ $post->getFirstMedia('docs')->created_at->format('Y-m-d H:i:s') }}</p>
    <button class="btn btn-primary"><a href="{{route('posts.show', $post->id)}}">Open</a></button>
    <button class="btn btn-primary"><a href="{{route('posts.edit', $post->id)}}">Edit</a></button>
    <form method="POST" action="{{route('posts.destroy', $post->id)}}" onsubmit="return confirm('Are you sure');" class="btn btn-primary">
        @csrf
        @method('DELETE')
        <button type="submit">Delete</button>
        </form>
        <button class="btn btn-primary"><a href="">Download</a></button>
        @endif
    @endforeach
    {{-- {{$post->data}} --}}
    
</body>

<html>