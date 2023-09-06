{{-- {{$contents}}

    <embed src="data:application/pdf;base64,{{base64_encode($contents)}}" type="application/pdf" width="100%" height="600px"> --}}

        @if (strpos($media->mime_type, 'image/') === 0)
        <img src="data:{{$media->mime_type}};base64,{{base64_encode($contents)}}" alt="Image">
    @elseif ($media->mime_type === 'application/pdf')
        <embed src="data:application/pdf;base64,{{base64_encode($contents)}}" type="application/pdf" width="100%" height="600px">
    @else
        <!-- Handle other media types as needed -->
        <p>Unsupported media type</p>
    @endif
    