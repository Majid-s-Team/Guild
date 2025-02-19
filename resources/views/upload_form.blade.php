<!DOCTYPE html>
<html>
<head>
    <title>CSV Upload</title>
</head>
<body>

@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="/uploads" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="csv_file">
    <button type="submit">Upload</button>
</form>

</body>
</html>
