<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Weekoverzicht - {{ $instructor->user->contact->firstName }} {{ $instructor->user->contact->lastName }}
                (Week {{ \Carbon\Carbon::parse($startDate)->format('W') }})
            </h2>
            <div class="flex gap-4">
                <a href="{{ route('owner.instructors.schedule.week', ['instructor' => $instructor, 'date' => \Carbon\Carbon::parse($startDate)->subWeek()->toDateString()]) }}" 
                   class="bg-white hover:bg-gray-300 text-[#0e1142] font-bold py-2 px-4 rounded">
                    Vorige Week
                </a>
                <a href="{{ route('owner.instructors.schedule.week', ['instructor' => $instructor, 'date' => \Carbon\Carbon::parse($startDate)->addWeek()->toDateString()]) }}" 
                   class="bg-white hover:bg-gray-300 text-[#0e1142] font-bold py-2 px-4 rounded">
                    Volgende Week
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#0e1142] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    <div class="grid grid-cols-7 gap-4">
                        @php
                            $currentDate = \Carbon\Carbon::parse($startDate);
                        @endphp
                        @for($i = 0; $i < 7; $i++)
                            <div class="border border-[#5b9fe3] rounded p-4 bg-white/10">
                                <h3 class="font-bold mb-2 text-[#5b9fe3]">{{ $currentDate->format('D d M') }}</h3>
                                @php
                                    $dayLessons = $lessons->get($currentDate->format('Y-m-d'), collect());
                                @endphp
                                @if($dayLessons->isEmpty())
                                    <p class="text-sm text-gray-300">Geen lessen</p>
                                @else
                                    @foreach($dayLessons->sortBy('reservationTime') as $lesson)
                                        <div class="mb-3 p-3 bg-white/10 rounded hover:bg-[#5b9fe3]/20 transition-colors">
                                            <p class="font-bold text-[#5b9fe3]">
                                                {{ \Carbon\Carbon::parse($lesson->reservationTime)->format('H:i') }}
                                            </p>
                                            <p class="text-sm font-semibold mb-1">
                                                {{ $lesson->user->contact->firstName }} {{ $lesson->user->contact->lastName }}
                                            </p>
                                            <p class="text-xs text-gray-300">{{ $lesson->package->name }}</p>
                                            <p class="text-xs text-gray-300">{{ $lesson->location->name }}</p>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            @php
                                $currentDate->addDay();
                            @endphp
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
