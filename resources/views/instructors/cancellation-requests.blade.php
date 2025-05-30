<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Annuleringsverzoeken') }}
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
                <div class="p-6 text-white text-center">
                    @if($requests->isEmpty())
                        <p class="text-center py-4">Er zijn geen openstaande annuleringsverzoeken.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2">Klant</th>
                                        <th class="px-4 py-2">Datum</th>
                                        <th class="px-4 py-2">Tijd</th>
                                        <th class="px-4 py-2">Pakket</th>
                                        <th class="px-4 py-2">Reden</th>
                                        <th class="px-4 py-2">Acties</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $request)
                                        <tr>
                                            <td class="px-4 py-2">
                                                {{ $request->user->contact->firstName }}
                                                {{ $request->user->contact->lastName }}
                                            </td>
                                            <td class="px-4 py-2">{{ $request->reservationDate }}</td>
                                            <td class="px-4 py-2">{{ $request->reservationTime }}</td>
                                            <td class="px-4 py-2">{{ $request->package->name }}</td>
                                            <td class="px-4 py-2">{{ $request->cancellationReason }}</td>
                                            <td class="px-4 py-2">
                                                <div class="flex space-x-2">
                                                    <form method="POST" action="{{ route('instructor.reservations.cancel-approve', [$request->user->customer, $request]) }}">
                                                        @csrf
                                                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
                                                            Goedkeuren
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('instructor.reservations.cancel-reject', [$request->user->customer, $request]) }}">
                                                        @csrf
                                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded text-sm">
                                                            Afwijzen
                                                        </button>
                                                    </form>
                                                </div>
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
