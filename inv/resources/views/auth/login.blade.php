<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Login</title>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-200 h-screen flex items-center justify-center p-4">
    <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-sm md:max-w-md transition-all">
        <div class="text-center">
            <img src="/img/Favicon akti.png" alt="logo" class="w-16 h-16 rounded-full mx-auto object-cover shadow-md" />
            <p class="text-gray-500 mt-2 font-medium">Dashboard</p>
            <p class="font-bold text-2xl text-gray-700 mt-2">AKTI INVENTORY</p>
        </div>
        
        <div class="mt-6">
            @if (session()->has('error'))
                <div class="bg-red-500 text-white text-sm p-2 rounded text-center">
                    {{ session()->get('error') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="post" class="mt-4 space-y-4">
                @csrf

                <div>
                    <label class="text-gray-600 font-medium text-sm" for="email">Email</label>
                    <input id="email" class="w-full p-3 border rounded-lg text-sm focus:ring-2 focus:ring-blue-400 outline-none" 
                        type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email" />
                    @error('email')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-gray-600 font-medium text-sm" for="password">Password</label>
                    <input id="password" class="w-full p-3 border rounded-lg text-sm focus:ring-2 focus:ring-blue-400 outline-none" 
                        type="password" name="password" placeholder="Masukkan password" />
                    @error('password')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button class="bg-blue-600 hover:bg-blue-700 text-white text-center w-full py-3 text-sm font-semibold rounded-lg shadow-md transition-all">
                    Log in
                </button>
            </form>
        </div>
    </div>
</body>
</html>
