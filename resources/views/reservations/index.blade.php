<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Mijn Reserveringen') }}
            </h2>
            <a href="{{ route('reservations.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Nieuwe Reservering
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-500 text-white p-4 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($reservations->isEmpty())
                        <p class="text-center py-4">Je hebt nog geen reserveringen.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($reservations as $reservation)
                                <div class="border rounded-lg p-4 bg-gray-50 dark:bg-gray-700">
                                    <h3 class="font-bold text-lg mb-2">{{ $reservation->package->name }}</h3>
                                    
                                    <div class="space-y-2 mb-4">
                                        <p><span class="font-semibold">Locatie:</span> {{ $reservation->location->name }}</p>
                                        <p><span class="font-semibold">Datum:</span> {{ $reservation->date }}</p>
                                        <p><span class="font-semibold">Aantal lessen:</span> {{ $reservation->package->numberOfLessons }}</p>
                                    </div>

                                    @if($reservation->package->isDuo)
                                        <div class="border-t border-gray-200 dark:border-gray-600 py-4 mb-4">
                                            <h4 class="font-semibold mb-2">Duo Partner Gegevens:</h4>
                                            <p>{{ $reservation->duoPartnerName }}</p>
                                            <p>{{ $reservation->duoPartnerEmail }}</p>
                                            <p>{{ $reservation->duoPartnerAddress }}</p>
                                            <p>{{ $reservation->duoPartnerCity }}</p>
                                        </div>
                                    @endif

                                    <div class="border-t border-gray-200 dark:border-gray-600 pt-4">
                                        @if($reservation->invoice)
                                            <p><span class="font-semibold">Factuurnummer:</span> {{ $reservation->invoice->invoiceNumber }}</p>
                                            <p><span class="font-semibold">Bedrag:</span> €{{ number_format($reservation->invoice->amount, 2) }}</p>
                                            <p class="mb-2">
                                                <span class="font-semibold">Status:</span>
                                                <span class="@if($reservation->invoice->status === 'paid') text-green-500 @else text-red-500 @endif">
                                                    {{ $reservation->invoice->status === 'paid' ? 'Betaald' : 'Niet betaald' }}
                                                </span>
                                            </p>
                                            
                                            @if($reservation->invoice->status === 'unpaid')
                                                <form method="POST" action="{{ route('reservations.update-payment', $reservation) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm w-full">
                                                        Markeer als betaald
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <p class="text-red-500">Geen factuur gevonden</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>