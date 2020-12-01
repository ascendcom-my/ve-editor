@if (session('success'))
    <div class="text-green-500 px-4 py-2">
        {{ session('success') }}
    </div>
@endif
@if ($errors->any())
    <div class="text-red-600 px-4 py-2">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif