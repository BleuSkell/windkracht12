<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gebruikersrollen beheren') }}
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
            <div class="bg-[#0e1142] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    @foreach ($users as $user)
                        <div class="mb-4 flex flex-row justify-between border-b border-gray-700 pb-2">
                            <div class="flex flex-row">
                                <div>
                                    <p class="text-md text-white">{{ $user->email }}</p>
                                    <p class="text-md text-white">{{ $user->roles->roleName }}</p>
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
