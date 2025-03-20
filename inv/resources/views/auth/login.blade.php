<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Login</title>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-200 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-xl shadow-lg w-96 md:w-1/3 transition-all">
        <div class="text-center">
            <img src="/img/Favicon akti.png" alt="logo" class="w-14 h-14 rounded-full mx-auto object-cover shadow-md" />
            <p class="text-gray-500 mt-3 font-medium">Dashboard</p>
            <p class="font-bold text-2xl text-gray-700 mt-3">AKTI INVENTORY</p>
        </div>
        <div class="mt-8">
            @if (session()->has('error'))
                <div class="bg-red-500 text-white text-xs p-2 rounded text-center">
                    {{ session()->get('error') }}
                </div>
            @endif
            <form action="{{ route('login') }}" method="post" class="mt-5">
                @csrf
                <label class="text-gray-500 font-semibold text-sm" for="email">Email</label>
                <div class="border rounded-lg mt-2 p-3 focus-within:ring-2 focus-within:ring-blue-400">
                    <input id="email" class="w-full text-sm focus:outline-none" type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" />
                </div>
                @error('email')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror

                <label class="text-gray-500 font-semibold text-sm mt-4 inline-block" for="password">Password</label>
                <div class="border rounded-lg mt-2 p-3 focus-within:ring-2 focus-within:ring-blue-400">
                    <input id="password" class="w-full text-sm focus:outline-none" type="password" name="password" placeholder="Enter your password" />
                </div>
                @error('password')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
                
                
                <button class="bg-blue-600 hover:bg-blue-700 text-white text-center w-full mt-5 rounded-lg py-2 text-sm font-semibold shadow-md transition-all">
                    Log in
                </button>
            </form>
        </div>
    </div>
</body>
</html>