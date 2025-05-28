<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gebruikers') }}
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
                    @foreach ($users as $user)
                        <div class="mb-4 flex flex-row justify-between">
                            <div class="flex flex-row">
                                <img src="{{ asset('img/user.png') }}" alt="User Image" class="w-12 h-12 rounded-full mr-4">

                                <div>
                                    <h3 class="text-lg font-semibold">{{ $user->name }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->roles->roleName }}</p>
                                </div>
                            </div>

                            <div>
                                <a href="{{ route('users.edit', $user->id) }}">
                                    <button>
                                        Edit
                                    </button>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
