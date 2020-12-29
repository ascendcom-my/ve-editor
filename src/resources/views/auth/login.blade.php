<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>VE Editor {{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('vendor/ve/css/app.css') }}">

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.0/dist/alpine.js" defer></script>
        @stack('script')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <!-- Page Heading -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        VE Editor - {{ config('app.name', 'Laravel') }}
                    </h2>
                </div>
            </header>
            <!-- Page Content -->
            <main>
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white shadow-xl sm:rounded-lg">
                            <x-veeditor::status-flash></x-veeditor::status-flash>
                            <div class="px-4 py-4">
                                <form action="{{ route('ve-editor.postLogin') }}" method="POST">
                                    @csrf
                                    <div class="flex items-center">
                                        <label for="email" class="w-28">Email</label>
                                        <input id="email" type="email" name="email" placeholder="Email Address" class="mx-4 flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline block my-2 w-full">
                                    </div>
                                    <div class="flex items-center">
                                        <label for="password" class="w-28">Password</label>
                                        <input type="password" name="password" placeholder="Password" class="mx-4 flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline block my-2 w-full">
                                    </div>
                                    <button type="submit" class="my-2 px-4 py-2 bg-gray-300 rounded">Log in</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
