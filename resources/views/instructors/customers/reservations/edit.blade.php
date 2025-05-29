<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Reservering Bewerken') }} - {{ $customer->user->contact->firstName }} {{ $customer->user->contact->lastName }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('instructor.customers.reservations.update', [$customer, $reservation]) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="packageId" value="Pakket" />
                            <select id="packageId" name="packageId" class="mt-1 block w-full" required>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" {{ $reservation->packageId == $package->id ? 'selected' : '' }}>
                                        {{ $package->name }} - €{{ number_format($package->price, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="locationId" value="Locatie" />
                            <select id="locationId" name="locationId" class="mt-1 block w-full" required>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ $reservation->locationId == $location->id ? 'selected' : '' }}>
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="reservationDate" value="Datum" />
                            <x-text-input id="reservationDate" name="reservationDate" type="date" class="mt-1 block w-full"
                                :value="old('reservationDate', $reservation->reservationDate)" required />
                        </div>

                        <div>
                            <x-input-label for="reservationTime" value="Tijd" />
                            <x-text-input id="reservationTime" name="reservationTime" type="time" class="mt-1 block w-full"
                                :value="old('reservationTime', $reservation->reservationTime)" required />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-secondary-button onclick="window.history.back()" type="button" class="mr-3">
                                Annuleren
                            </x-secondary-button>
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
