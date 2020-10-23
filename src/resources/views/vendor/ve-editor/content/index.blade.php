<!DOCTYPE html>
<html>
    <head><title>Pull</title></head>
    <body>
        <form action="{{ route('ve-editor.pull') }}" method="POST">
            @csrf
            <button type="submit">Pull</button>
        </form>
        @if(session('success') || session('error')) {{ session('message') }} @endif
    </body>
</html>
