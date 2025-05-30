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

            <div class="bg-[#0e1142] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    @if($reservations->isEmpty())
                        <p class="text-center py-4">Je hebt nog geen reserveringen.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($reservations as $reservation)
                                <div class="rounded-lg p-4 bg-[#5b9fe3]">
                                    <h3 class="font-bold text-lg mb-2">{{ $reservation->package->name }}</h3>
                                    
                                    <div class="space-y-2 mb-4">
                                        <p><span class="font-semibold">Locatie:</span> {{ $reservation->location->name }}</p>
                                        <p><span class="font-semibold">Datum:</span>
                                            {{ \Carbon\Carbon::parse($reservation->reservationDate)->format('d-m-Y') }}
                                        </p>
                                        <p><span class="font-semibold">Tijd:</span>
                                            {{ \Carbon\Carbon::parse($reservation->reservationTime)->format('H:i') }}
                                        </p>
                                        <p><span class="font-semibold">Aantal lessen:</span> {{ $reservation->package->numberOfLessons }}</p>
                                        <p><span class="font-semibold">Status:</span>
                                            <span class="px-2 py-1 rounded-full text-xs
                                                @if($reservation->status === 'confirmed') 
                                                    bg-green-100 text-green-800
                                                @elseif($reservation->invoice && $reservation->invoice->status === 'paid')
                                                    bg-yellow-100 text-yellow-800
                                                @else
                                                    bg-gray-100 text-gray-800
                                                @endif">
                                                @if($reservation->status === 'confirmed')
                                                    Definitief
                                                @elseif($reservation->invoice && $reservation->invoice->status === 'paid')
                                                    Betaald
                                                @else
                                                    Voorlopig
                                                @endif
                                            </span>
                                        </p>
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

                                    @if(!$reservation->cancellationStatus)
                                        <div class="mt-4 mb-4">
                                            <button onclick="showCancelModal({{ $reservation->id }})" 
                                                    class="bg-red-700 hover:bg-red-500 text-white font-bold py-2 px-4 rounded text-sm">
                                                Les annuleren
                                            </button>
                                        </div>
                                    @elseif($reservation->cancellationStatus === 'pending')
                                        <div class="mt-4 p-2 bg-yellow-100 rounded">
                                            <p class="text-yellow-700">Annuleringsverzoek in behandeling</p>
                                        </div>
                                    @elseif($reservation->cancellationStatus === 'approved')
                                        <div class="mt-4">
                                            <button onclick="showRescheduleModal({{ $reservation->id }})" 
                                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                Nieuwe datum kiezen
                                            </button>
                                        </div>
                                    @endif

                                    <!-- Cancel Modal -->
                                    <div id="cancelModal{{ $reservation->id }}" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
                                        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                            <form method="POST" action="{{ route('reservations.cancel', $reservation) }}">
                                                @csrf
                                                <h3 class="text-lg font-medium mb-4">Les annuleren</h3>
                                                <div class="mb-4">
                                                    <label for="reason" class="block text-sm font-medium text-gray-700">Reden voor annulering</label>
                                                    <textarea name="reason" id="reason" rows="4" 
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea>
                                                </div>
                                                <div class="flex justify-end space-x-2">
                                                    <button type="button" onclick="hideCancelModal({{ $reservation->id }})"
                                                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                                        Annuleren
                                                    </button>
                                                    <button type="submit"
                                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                                        Bevestigen
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Reschedule Modal -->
                                    <div id="rescheduleModal{{ $reservation->id }}" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
                                        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                            <form method="POST" action="{{ route('reservations.reschedule', $reservation) }}">
                                                @csrf
                                                <h3 class="text-lg font-medium mb-4">Nieuwe datum kiezen</h3>
                                                <div class="mb-4">
                                                    <label for="reservationDate" class="block text-sm font-medium text-gray-700">Nieuwe datum</label>
                                                    <input type="date" name="reservationDate" id="reservationDate" 
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                                </div>
                                                <div class="mb-4">
                                                    <label for="reservationTime" class="block text-sm font-medium text-gray-700">Nieuwe tijd</label>
                                                    <input type="time" name="reservationTime" id="reservationTime" 
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                                </div>
                                                <div class="flex justify-end space-x-2">
                                                    <button type="button" onclick="hideRescheduleModal({{ $reservation->id }})"
                                                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                                        Annuleren
                                                    </button>
                                                    <button type="submit"
                                                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                                        Bevestigen
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="border-t border-gray-200 dark:border-gray-600 pt-4">
                                        @if($reservation->invoice)
                                            <p><span class="font-semibold">Factuurnummer:</span> {{ $reservation->invoice->invoiceNumber }}</p>
                                            <p><span class="font-semibold">Bedrag:</span> €{{ number_format($reservation->invoice->amount, 2) }}</p>
                                            <p class="mb-2">
                                                <span class="font-semibold">Status:</span>
                                                <span class="@if($reservation->invoice->status === 'paid') text-white @else text-red-500 @endif">
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

    <script>
        function showCancelModal(id) {
            document.getElementById('cancelModal' + id).classList.remove('hidden');
        }

        function hideCancelModal(id) {
            document.getElementById('cancelModal' + id).classList.add('hidden');
        }

        function showRescheduleModal(id) {
            document.getElementById('rescheduleModal' + id).classList.remove('hidden');
        }

        function hideRescheduleModal(id) {
            document.getElementById('rescheduleModal' + id).classList.add('hidden');
        }
    </script>
</x-app-layout>