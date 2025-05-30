<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nieuwe Reservering') }}
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
                    <form method="POST" action="{{ route('reservations.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="packageId" value="Kies een pakket" />
                            <select id="packageId" name="packageId" class="mt-1 block w-full bg-white border-[#5b9fe3] text-[#0e1142] rounded-lg" required>
                                <option value="">Selecteer een pakket</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" data-is-duo="{{ $package->isDuo ? 1 : 0 }}">
                                        {{ $package->name }} - €{{ number_format($package->price, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('packageId')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="locationId" value="Kies een locatie" />
                            <select id="locationId" name="locationId" class="mt-1 block w-full bg-white border-[#5b9fe3] text-[#0e1142] rounded-lg" required>
                                <option value="">Selecteer een locatie</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('locationId')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="reservationDate" value="Kies een datum" />
                            <input type="date" name="reservationDate" id="reservationDate" class="mt-1 block w-full mt-1 block w-full bg-white border-[#5b9fe3] text-[#0e1142] rounded-lg" required>
                            <x-input-error :messages="$errors->get('reservationDate')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="reservationTime" value="Kies een tijd" />
                            <input type="time" name="reservationTime" id="reservationTime" class="mt-1 block w-full bg-white border-[#5b9fe3] text-[#0e1142] rounded-lg" required>
                            <x-input-error :messages="$errors->get('reservationTime')" class="mt-2" />
                        </div>

                        <!-- Add duo partner fields section -->
                        <div id="duoPartnerFields" class="space-y-6 hidden">
                            <h3 class="font-semibold text-lg">Gegevens Duo Partner</h3>
                            
                            <div>
                                <x-input-label for="duoPartnerName" value="Naam" />
                                <x-text-input id="duoPartnerName" name="duoPartnerName" type="text" class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('duoPartnerName')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="duoPartnerEmail" value="E-mail" />
                                <x-text-input id="duoPartnerEmail" name="duoPartnerEmail" type="email" class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('duoPartnerEmail')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="duoPartnerAddress" value="Adres" />
                                <x-text-input id="duoPartnerAddress" name="duoPartnerAddress" type="text" class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('duoPartnerAddress')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="duoPartnerCity" value="Woonplaats" />
                                <x-text-input id="duoPartnerCity" name="duoPartnerCity" type="text" class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('duoPartnerCity')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="duoPartnerPhone" value="Telefoonnummer" />
                                <x-text-input id="duoPartnerPhone" name="duoPartnerPhone" type="text" class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('duoPartnerPhone')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                Reserveer
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const packageSelect = document.getElementById('packageId');
            const duoPartnerFields = document.getElementById('duoPartnerFields');
            const duoInputs = duoPartnerFields.querySelectorAll('input');

            function toggleDuoFields() {
                const selectedOption = packageSelect.options[packageSelect.selectedIndex];
                const isDuo = selectedOption.dataset.isDuo === '1';
                
                duoPartnerFields.classList.toggle('hidden', !isDuo);
                
                // Toggle required attribute on duo partner fields
                duoInputs.forEach(input => {
                    input.required = isDuo;
                });
            }

            packageSelect.addEventListener('change', toggleDuoFields);
            // Run once on page load in case of form validation errors
            toggleDuoFields();
        });
    </script>
</x-app-layout>