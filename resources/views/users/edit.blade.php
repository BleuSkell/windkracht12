<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Wijzig gebruiker') }}
        </h2>
    </x-slot>

    <div class="flex justify-center">
        @if (session('error'))
            <div class="w-4/5 bg-red-500 text-white p-2 mb-4 mt-4 rounded text-center">
                {{ session('error') }}
            </div>
        @elseif (session('success'))
            <div class="w-4/5 bg-green-500 text-white p-2 mb-4 mt-4 rounded text-center">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('users.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">E-mail</label>
                            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('email')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gebruikers rol</label>
                            <select id="role" name="role" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ (old('role', $user->roles->id ?? '') == $role->id) ? 'selected' : '' }}>
                                        {{ $role->roleName }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Wijzigingen opslaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
