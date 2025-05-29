<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nieuwe Klant') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('instructor.customers.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="email" value="Email" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" 
                                :value="old('email')" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="firstName" value="Voornaam" />
                            <x-text-input id="firstName" name="firstName" type="text" class="mt-1 block w-full" 
                                :value="old('firstName')" required />
                            <x-input-error :messages="$errors->get('firstName')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="lastName" value="Achternaam" />
                            <x-text-input id="lastName" name="lastName" type="text" class="mt-1 block w-full" 
                                :value="old('lastName')" required />
                            <x-input-error :messages="$errors->get('lastName')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="adress" value="Adres" />
                            <x-text-input id="adress" name="adress" type="text" class="mt-1 block w-full" 
                                :value="old('adress')" required />
                            <x-input-error :messages="$errors->get('adress')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="city" value="Woonplaats" />
                            <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" 
                                :value="old('city')" required />
                            <x-input-error :messages="$errors->get('city')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="dateOfBirth" value="Geboortedatum" />
                            <x-text-input id="dateOfBirth" name="dateOfBirth" type="date" class="mt-1 block w-full" 
                                :value="old('dateOfBirth')" required />
                            <x-input-error :messages="$errors->get('dateOfBirth')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="mobile" value="Mobiel" />
                            <x-text-input id="mobile" name="mobile" type="text" class="mt-1 block w-full" 
                                :value="old('mobile')" required />
                            <x-input-error :messages="$errors->get('mobile')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('instructor.customers.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mr-3">
                                Annuleren
                            </a>
                            <x-primary-button>
                                Klant Aanmaken
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>