<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lessen van') }} {{ $customer->user->contact->firstName }} {{ $customer->user->contact->lastName }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-500 text-white p-4 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-[#0e1142] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($lessons->isEmpty())
                        <p class="text-center">Deze klant heeft nog geen lessen.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2">Datum</th>
                                        <th class="px-4 py-2">Tijd</th>
                                        <th class="px-4 py-2">Pakket</th>
                                        <th class="px-4 py-2">Locatie</th>
                                        <th class="px-4 py-2">Acties</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lessons as $lesson)
                                        <tr>
                                            <td class="px-4 py-2">
                                                {{ date('d-m-Y', strtotime($lesson->reservationDate)) }}
                                            </td>
                                            <td class="px-4 py-2">
                                                {{ date('H:i', strtotime($lesson->reservationTime)) }}
                                            </td>
                                            <td class="px-4 py-2">
                                                {{ $lesson->package->name }}
                                            </td>
                                            <td class="px-4 py-2">
                                                {{ $lesson->location->name }}
                                            </td>
                                            <td class="px-4 py-2">
                                                <a href="{{ route('instructor.customers.reservations.edit', [$customer, $lesson]) }}" 
                                                   class="text-blue-600 hover:text-blue-800 mr-2">
                                                    Reservering Bewerken
                                                </a>
                                                <form method="POST" action="{{ route('instructor.reservations.cancel-weather', [$customer, $lesson]) }}" 
                                                      class="inline-block">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-800 mx-2"
                                                            onclick="return confirm('Weet je zeker dat je deze les wilt annuleren wegens weer?')">
                                                        Annuleren (Weer)
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('instructor.reservations.cancel-sick', [$customer, $lesson]) }}" 
                                                      class="inline-block">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-800 ml-2"
                                                            onclick="return confirm('Weet je zeker dat je deze les wilt annuleren wegens ziekte?')">
                                                        Annuleren (Ziek)
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-2" colspan="5">
                                                @if($lesson->cancellationStatus === 'pending')
                                                    <div class="space-y-2">
                                                        <p class="text-yellow-600">Annuleringsverzoek:</p>
                                                        <p class="text-sm">{{ $lesson->cancellationReason }}</p>
                                                        <div class="flex space-x-2">
                                                            <form method="POST" action="{{ route('instructor.reservations.cancel-approve', [$customer, $lesson]) }}" class="inline">
                                                                @csrf
                                                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded text-sm"
                                                                        onclick="return confirm('Weet je zeker dat je deze annulering wilt goedkeuren?')">
                                                                    Goedkeuren
                                                                </button>
                                                            </form>
                                                            <form method="POST" action="{{ route('instructor.reservations.cancel-reject', [$customer, $lesson]) }}" class="inline">
                                                                @csrf
                                                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded text-sm"
                                                                        onclick="return confirm('Weet je zeker dat je deze annulering wilt afwijzen?')">
                                                                    Afwijzen
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <div class="flex items-center justify-end mb-4">
                    <a href="{{  route('instructor.customers.index') }}">
                        <x-secondary-button class="mr-3">
                            Terug naar klanten
                        </x-secondary-button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
