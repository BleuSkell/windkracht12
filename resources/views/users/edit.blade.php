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
            <div class="bg-[#0e1142] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('users.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <x-input-label for="email" value="Email" />
                            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                                   class="bg-white w-full rounded-lg text-[#0e1142] mt-1">
                            @error('email')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <x-input-label for="role" value="Gebruikersrol" />
                            <select id="role" name="role" required
                                    class="bg-white w-full rounded-lg text-[#0e1142] mt-1">
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
                                    class="inline-flex items-center px-4 py-2 bg-white text-[#0e1142] border border-transparent rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0e1142] transition ease-in-out duration-150">
                                Wijzigingen opslaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
