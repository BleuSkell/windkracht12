<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Lessen van {{ $customer->user->contact->firstName }} {{ $customer->user->contact->lastName }}
            </h2>
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Instructeur(s): 
                @foreach($customer->instructors as $instructor)
                    {{ $instructor->user->contact->firstName }} {{ $instructor->user->contact->lastName }}
                    @if(!$loop->last), @endif
                @endforeach
            </div>
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
                    @if($lessons->isEmpty())
                        <p class="text-center py-4">Deze klant heeft nog geen lessen.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2">Datum</th>
                                        <th class="px-4 py-2">Tijd</th>
                                        <th class="px-4 py-2">Pakket</th>
                                        <th class="px-4 py-2">Locatie</th>
                                        <th class="px-4 py-2">Status</th>
                                        <th class="px-4 py-2">Acties</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lessons as $lesson)
                                        <tr class="border-t">
                                            <td class="px-4 py-2">
                                                {{ \Carbon\Carbon::parse($lesson->reservationDate)->format('d-m-Y') }}
                                            </td>
                                            <td class="px-4 py-2">
                                                {{ \Carbon\Carbon::parse($lesson->reservationTime)->format('H:i') }}
                                            </td>
                                            <td class="px-4 py-2">
                                                {{ $lesson->package->name }}
                                            </td>
                                            <td class="px-4 py-2">
                                                {{ $lesson->location->name }}
                                            </td>
                                            <td class="px-4 py-2">
                                                <span class="px-2 py-1 rounded-full text-xs
                                                    @if($lesson->status === 'confirmed') 
                                                        bg-green-100 text-green-800
                                                    @elseif($lesson->invoice && $lesson->invoice->status === 'paid')
                                                        bg-yellow-100 text-yellow-800
                                                    @else
                                                        bg-gray-100 text-gray-800
                                                    @endif">
                                                    @if($lesson->status === 'confirmed')
                                                        Definitief
                                                    @elseif($lesson->invoice && $lesson->invoice->status === 'paid')
                                                        Betaald - Wacht op bevestiging
                                                    @else
                                                        Voorlopig
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 space-x-2">
                                                @if($lesson->invoice && $lesson->invoice->status === 'paid' && $lesson->status !== 'confirmed')
                                                    <form method="POST" 
                                                          action="{{ route('owner.reservations.confirm', $lesson) }}"
                                                          class="inline-block">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm"
                                                                onclick="return confirm('Weet je zeker dat je deze reservering definitief wilt maken?')">
                                                            Definitief maken
                                                        </button>
                                                    </form>
                                                @endif
                                                <form method="POST" 
                                                      action="{{ route('owner.reservations.cancel-weather', [$customer, $lesson]) }}"
                                                      class="inline-block">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="text-blue-600 hover:text-blue-800"
                                                            onclick="return confirm('Weet je zeker dat je deze les wilt annuleren wegens weer?')">
                                                        Annuleren (Weer)
                                                    </button>
                                                </form>
                                                <form method="POST" 
                                                      action="{{ route('owner.reservations.cancel-sick', [$customer, $lesson]) }}"
                                                      class="inline-block">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-800"
                                                            onclick="return confirm('Weet je zeker dat je deze les wilt annuleren wegens ziekte?')">
                                                        Annuleren (Ziek)
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
