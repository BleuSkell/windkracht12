<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Dagoverzicht - {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}
            </h2>
            <div class="flex gap-4">
                <a href="{{ route('instructor.schedule.day', ['date' => \Carbon\Carbon::parse($date)->subDay()->toDateString()]) }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Vorige Dag
                </a>
                <a href="{{ route('instructor.schedule.day', ['date' => \Carbon\Carbon::parse($date)->addDay()->toDateString()]) }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Volgende Dag
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($lessons->isEmpty())
                        <p class="text-center">Geen lessen gepland op deze dag.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2">Tijd</th>
                                        <th class="px-4 py-2">Klant</th>
                                        <th class="px-4 py-2">Pakket</th>
                                        <th class="px-4 py-2">Locatie</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lessons->sortBy('reservationTime') as $lesson)
                                        <tr>
                                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($lesson->reservationTime)->format('H:i') }}</td>
                                            <td class="px-4 py-2">
                                                {{ $lesson->user->contact->firstName }} {{ $lesson->user->contact->lastName }}
                                            </td>
                                            <td class="px-4 py-2">{{ $lesson->package->name }}</td>
                                            <td class="px-4 py-2">{{ $lesson->location->name }}</td>
                                            <td class="px-4 py-2">
                                                <p class="font-bold">{{ \Carbon\Carbon::parse($lesson->reservationTime)->format('H:i') }}</p>
                                                <p>{{ $lesson->user->contact->firstName }} {{ $lesson->user->contact->lastName }}</p>
                                                <p class="text-sm">{{ $lesson->package->name }}</p>
                                                <p class="text-sm">{{ $lesson->location->name }}</p>
                                                <p class="text-xs mt-1">
                                                    <span class="px-2 py-1 rounded-full
                                                        @if($lesson->status === 'confirmed') 
                                                            bg-green-100 text-green-800
                                                        @else
                                                            bg-gray-100 text-gray-800
                                                        @endif">
                                                        {{ $lesson->status === 'confirmed' ? 'Definitief' : 'Voorlopig' }}
                                                    </span>
                                                </p>
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
