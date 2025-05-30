<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Klant Bewerken') }}
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
                    <form method="POST" action="{{ route('owner.customers.update', $customer) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="email" value="Email" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" 
                                :value="old('email', $customer->user->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="firstName" value="Voornaam" />
                            <x-text-input id="firstName" name="firstName" type="text" class="mt-1 block w-full"
                                :value="old('firstName', $customer->user->contact->firstName)" required />
                            <x-input-error :messages="$errors->get('firstName')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="lastName" value="Achternaam" />
                            <x-text-input id="lastName" name="lastName" type="text" class="mt-1 block w-full"
                                :value="old('lastName', $customer->user->contact->lastName)" required />
                            <x-input-error :messages="$errors->get('lastName')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="adress" value="Adres" />
                            <x-text-input id="adress" name="adress" type="text" class="mt-1 block w-full"
                                :value="old('adress', $customer->user->contact->adress)" required />
                            <x-input-error :messages="$errors->get('adress')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="city" value="Woonplaats" />
                            <x-text-input id="city" name="city" type="text" class="mt-1 block w-full"
                                :value="old('city', $customer->user->contact->city)" required />
                            <x-input-error :messages="$errors->get('city')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="dateOfBirth" value="Geboortedatum" />
                            <x-text-input id="dateOfBirth" name="dateOfBirth" type="date" class="mt-1 block w-full"
                                :value="old('dateOfBirth', $customer->user->contact->dateOfBirth)" required />
                            <x-input-error :messages="$errors->get('dateOfBirth')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="mobile" value="Mobiel" />
                            <x-text-input id="mobile" name="mobile" type="text" class="mt-1 block w-full"
                                :value="old('mobile', $customer->user->contact->mobile)" required />
                            <x-input-error :messages="$errors->get('mobile')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="instructors" value="Instructeurs" />
                            <select name="instructors[]" id="instructors" multiple class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-100" required>
                                @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->id }}"
                                        {{ in_array($instructor->id, $customer->instructors->pluck('id')->toArray()) ? 'selected' : '' }}>
                                        {{ $instructor->user->contact->firstName }}
                                        {{ $instructor->user->contact->lastName }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('instructors')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('owner.customers.index') }}" class="mr-3">
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
