<!DOCTYPE html>
<title>Search</title>
<body>

<div class="container">
    <form action="{{ route('search') }}" method="GET">
        <div class="input-group">
            <input type="text" class="form-control" name="q" placeholder="Search here" required>
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </div>
    </form>

    @if (isset($results))
       

        <select>
            <option>Search Results for "{{ $query }}"</option>
            @foreach ($results['fileNameResults'] as $result)
                <option>{{ $result->file_name }}</option>
            @endforeach

            @foreach ($results['docxResults'] as $result)
                <option>{{ $result['file_name'] }}: {!! str_replace($query, "<strong>{$query}</strong>", htmlspecialchars_decode($result['sentence'])) !!}</option>
            @endforeach

            @foreach ($results['xlsxResults'] as $result)
                <option>{{ $result['file_name'] }}: {!! str_replace($query, "<strong>{$query}</strong>", htmlspecialchars_decode($result['cell_value'])) !!}</option>
            @endforeach

            @foreach ($results['pptxResults'] as $result)
                <option>{{ $result['file_name'] }}: {!! str_replace($query, "<strong>{$query}</strong>", htmlspecialchars_decode($result['sentence'])) !!}</option>
            @endforeach

            @foreach ($results['txtResults'] as $result)
                <option>{{ $result['file_name'] }}: {!! str_replace($query, "<strong>{$query}</strong>", htmlspecialchars_decode($result['sentence'])) !!}</option>
            @endforeach

            @foreach ($results['pdfResults'] as $result)
                <option>{{ $result['file_name'] }}: {!! str_replace($query, "<strong>{$query}</strong>", htmlspecialchars_decode($result['sentence'])) !!}</option>
            @endforeach
        </select>
    @endif
</div>

</body>
</html>

<!DOCTYPE html>
<title>Search</title>
<body>

<div class="container">
    <form action="{{ route('search') }}" method="GET">
        <div class="input-group">
            <input type="text" class="form-control" name="q" placeholder="Search here" required>
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </div>
    </form>

    @if (session()->has('search_results'))
        @php
            $results = session('search_results');
            session()->forget('search_results');
        @endphp

        <h2>Search Results for "{{ request()->input('q') }}"</h2>

        <select>
            @foreach ($results['fileNameResults'] as $result)
                <option>{{ $result->file_name }}</option>
            @endforeach

            @foreach ($results['docxResults'] as $result)
                <option>{{ $result['file_name'] }}: {!! str_replace(request()->input('q'), "<strong>" . request()->input('q') . "</strong>", htmlspecialchars_decode($result['sentence'])) !!}</option>
            @endforeach

            @foreach ($results['xlsxResults'] as $result)
                <option>{{ $result['file_name'] }}: {!! str_replace(request()->input('q'), "<strong>" . request()->input('q') . "</strong>", htmlspecialchars_decode($result['cell_value'])) !!}</option>
            @endforeach

            @foreach ($results['pptxResults'] as $result)
                <option>{{ $result['file_name'] }}: {!! str_replace(request()->input('q'), "<strong>" . request()->input('q') . "</strong>", htmlspecialchars_decode($result['sentence'])) !!}</option>
            @endforeach

            @foreach ($results['txtResults'] as $result)
                <option>{{ $result['file_name'] }}: {!! str_replace(request()->input('q'), "<strong>" . request()->input('q') . "</strong>", htmlspecialchars_decode($result['sentence'])) !!}</option>
            @endforeach

            @foreach ($results['pdfResults'] as $result)
                <option>{{ $result['file_name'] }}: {!! str_replace(request()->input('q'), "<strong>" . request()->input('q') . "</strong>", htmlspecialchars_decode($result['sentence'])) !!}</option>
            @endforeach
        </select>
    @endif
</div>

</body>
</html>
