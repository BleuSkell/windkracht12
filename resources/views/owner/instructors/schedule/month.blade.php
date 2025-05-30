<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Maandoverzicht - {{ $instructor->user->contact->firstName }} {{ $instructor->user->contact->lastName }}
                ({{ \Carbon\Carbon::parse($startDate)->format('F Y') }})
            </h2>
            <div class="flex gap-4">
                <a href="{{ route('owner.instructors.schedule.month', ['instructor' => $instructor, 'date' => \Carbon\Carbon::parse($startDate)->subMonth()->toDateString()]) }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Vorige Maand
                </a>
                <a href="{{ route('owner.instructors.schedule.month', ['instructor' => $instructor, 'date' => \Carbon\Carbon::parse($startDate)->addMonth()->toDateString()]) }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Volgende Maand
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-7 gap-4">
                        @php
                            $firstDay = \Carbon\Carbon::parse($startDate)->startOfMonth();
                            $lastDay = $firstDay->copy()->endOfMonth();
                            $currentDay = $firstDay->copy()->startOfWeek();
                        @endphp

                        <div class="col-span-7 grid grid-cols-7 gap-4 mb-4">
                            @foreach(['Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za', 'Zo'] as $day)
                                <div class="text-center font-bold">{{ $day }}</div>
                            @endforeach
                        </div>

                        @while($currentDay <= $lastDay)
                            @php $isCurrentMonth = $currentDay->month === $startDate->month; @endphp
                            <div class="min-h-[100px] border p-2 {{ $isCurrentMonth ? 'bg-white dark:bg-gray-700' : 'bg-gray-100 dark:bg-gray-800' }}">
                                <div class="font-bold mb-1">{{ $currentDay->format('j') }}</div>
                                @if(isset($lessons[$currentDay->format('Y-m-d')]))
                                    @foreach($lessons[$currentDay->format('Y-m-d')]->sortBy('reservationTime') as $lesson)
                                        <div class="text-xs mb-1">
                                            <span class="font-semibold">{{ \Carbon\Carbon::parse($lesson->reservationTime)->format('H:i') }}</span>
                                            <br>
                                            {{ $lesson->user->contact->firstName }}
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            @php $currentDay->addDay(); @endphp
                        @endwhile
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
