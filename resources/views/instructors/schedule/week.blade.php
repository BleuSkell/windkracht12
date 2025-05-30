<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Weekoverzicht - Week {{ \Carbon\Carbon::parse($startDate)->format('W') }}
            </h2>
            <div class="flex gap-4">
                <a href="{{ route('instructor.schedule.week', ['date' => \Carbon\Carbon::parse($startDate)->subWeek()->toDateString()]) }}" 
                   class="bg-white hover:bg-gray-300 text-[#0e1142] font-bold py-2 px-4 rounded">
                    Vorige Week
                </a>
                <a href="{{ route('instructor.schedule.week', ['date' => \Carbon\Carbon::parse($startDate)->addWeek()->toDateString()]) }}" 
                   class="bg-white hover:bg-gray-300 text-[#0e1142] font-bold py-2 px-4 rounded">
                    Volgende Week
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#0e1142] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-7 gap-4">
                        @foreach($lessons->sortKeys() as $date => $dayLessons)
                            <div class="border p-4 rounded">
                                <h3 class="font-bold mb-2">{{ \Carbon\Carbon::parse($date)->format('D d M') }}</h3>
                                @if($dayLessons->isEmpty())
                                    <p class="text-sm text-gray-500">Geen lessen</p>
                                @else
                                    @foreach($dayLessons->sortBy('reservationTime') as $lesson)
                                        <div class="mb-2 p-2 bg-gray-100 dark:bg-gray-700 rounded">
                                            <p class="font-bold">{{ \Carbon\Carbon::parse($lesson->reservationTime)->format('H:i') }}</p>
                                            <p>{{ $lesson->user->contact->firstName }} {{ $lesson->user->contact->lastName }}</p>
                                            <p class="text-sm">{{ $lesson->package->name }}</p>
                                            <p class="text-sm">{{ $lesson->location->name }}</p>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
