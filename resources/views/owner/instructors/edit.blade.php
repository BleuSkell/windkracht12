<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Instructeur Bewerken') }}
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

            <div class="bg-[#0e1142] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('owner.instructors.update', $instructor) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="email" value="Email" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" 
                                :value="old('email', $instructor->user->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="firstName" value="Voornaam" />
                            <x-text-input id="firstName" name="firstName" type="text" class="mt-1 block w-full"
                                :value="old('firstName', $instructor->user->contact->firstName)" required />
                            <x-input-error :messages="$errors->get('firstName')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="lastName" value="Achternaam" />
                            <x-text-input id="lastName" name="lastName" type="text" class="mt-1 block w-full"
                                :value="old('lastName', $instructor->user->contact->lastName)" required />
                            <x-input-error :messages="$errors->get('lastName')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="adress" value="Adres" />
                            <x-text-input id="adress" name="adress" type="text" class="mt-1 block w-full"
                                :value="old('adress', $instructor->user->contact->adress)" required />
                            <x-input-error :messages="$errors->get('adress')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="city" value="Woonplaats" />
                            <x-text-input id="city" name="city" type="text" class="mt-1 block w-full"
                                :value="old('city', $instructor->user->contact->city)" required />
                            <x-input-error :messages="$errors->get('city')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="dateOfBirth" value="Geboortedatum" />
                            <x-text-input id="dateOfBirth" name="dateOfBirth" type="date" class="mt-1 block w-full"
                                :value="old('dateOfBirth', $instructor->user->contact->dateOfBirth)" required />
                            <x-input-error :messages="$errors->get('dateOfBirth')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="bsnNumber" value="BSN" />
                            <x-text-input id="bsnNumber" name="bsnNumber" type="text" class="mt-1 block w-full"
                                :value="old('bsnNumber', $instructor->user->contact->bsnNumber)" required />
                            <x-input-error :messages="$errors->get('bsnNumber')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="mobile" value="Mobiel" />
                            <x-text-input id="mobile" name="mobile" type="text" class="mt-1 block w-full"
                                :value="old('mobile', $instructor->user->contact->mobile)" required />
                            <x-input-error :messages="$errors->get('mobile')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('owner.instructors.index') }}" class="mr-3">
                                <x-secondary-button type="button">
                                    Annuleren
                                </x-secondary-button>
                            </a>
                            <x-primary-button>
                                Wijzigingen Opslaan
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
