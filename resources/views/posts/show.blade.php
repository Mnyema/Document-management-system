 {{-- {!! $contents !!}  --}}
 <!DOCTYPE html>
<html>
<head>
    <title>Document Contents</title>
</head>
<body>
    

 
@if ($media->mime_type === 'application/pdf')
    <embed src="data:application/pdf;base64,{{base64_encode($contents)}}" type="application/pdf" width="100%" height="600px">
@elseif (strpos($media->mime_type, 'image/') === 0)
    <img src="data:{{$media->mime_type}};base64,{{base64_encode($contents)}}" alt="Image">
@elseif ($media->mime_type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
    {!! $contents !!}
@elseif($media->mime_type==='text/plain')
    {{$contents}}
@elseif($media->mime_type==='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
    {!! $htmlContent !!}
 @elseif ($media->mime_type === 'application/vnd.openxmlformats-officedocument.presentationml.presentation')
    {!! $htmlContent !!}

@else
    <p>Unsupported media type</p>
@endif
</body>
</html>