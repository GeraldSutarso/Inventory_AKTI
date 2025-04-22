@extends('layouts.main')

@section('container')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg p-6 max-w-lg mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-gray-700 font-bold text-xl">Add New User</h2>
            <a href="{{ route('users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-600 transition">Back</a>
        </div>

        <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="text-sm text-gray-700">Full Name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="off"
                    class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none @error('name') border-red-400 @enderror">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="text-sm text-gray-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="off"
                    class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none @error('email') border-red-400 @enderror">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="text-sm text-gray-700">Password</label>
                <input id="password" name="password" type="password" autocomplete="new-password"
                    class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none @error('password') border-red-400 @enderror">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="role" class="text-sm text-gray-700">Role</label>
                <select id="role" name="role"
                    class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none @error('role') border-red-400 @enderror">
                    <option value="">-- Select Role --</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>
                            {{ ucfirst($role) }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">Save User</button>
            </div>
        </form>
    </div>
</div>
@endsection
