<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Maandoverzicht - {{ $instructor->user->contact->firstName }} {{ $instructor->user->contact->lastName }}
                ({{ \Carbon\Carbon::parse($startDate)->format('F Y') }})
            </h2>
            <div class="flex gap-4">
                <a href="{{ route('owner.instructors.schedule.month', ['instructor' => $instructor, 'date' => \Carbon\Carbon::parse($startDate)->subMonth()->toDateString()]) }}" 
                   class="bg-white hover:bg-gray-300 text-[#0e1142] font-bold py-2 px-4 rounded">
                    Vorige Maand
                </a>
                <a href="{{ route('owner.instructors.schedule.month', ['instructor' => $instructor, 'date' => \Carbon\Carbon::parse($startDate)->addMonth()->toDateString()]) }}" 
                   class="bg-white hover:bg-gray-300 text-[#0e1142] font-bold py-2 px-4 rounded">
                    Volgende Maand
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#0e1142] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    <div class="hidden md:grid md:grid-cols-7 gap-4">
                        @php
                            $firstDay = \Carbon\Carbon::parse($startDate)->startOfMonth();
                            $lastDay = $firstDay->copy()->endOfMonth();
                            $currentDay = $firstDay->copy()->startOfWeek();
                            $today = \Carbon\Carbon::today();
                        @endphp

                        <div class="col-span-7 grid grid-cols-7 gap-4 mb-4">
                            @foreach(['Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za', 'Zo'] as $day)
                                <div class="text-center font-bold p-2 bg-[#5b9fe3]/80 rounded-t backdrop-blur-sm">{{ $day }}</div>
                            @endforeach
                        </div>

                        @while($currentDay <= $lastDay)
                            @php 
                                $isCurrentMonth = $currentDay->month === $startDate->month;
                                $isToday = $currentDay->isToday();
                            @endphp
                            <div class="relative min-h-[120px] border {{ $isCurrentMonth ? 'border-[#5b9fe3]' : 'border-gray-600' }} 
                                     rounded-b p-4 {{ $isToday ? 'bg-[#5b9fe3]/20' : ($isCurrentMonth ? 'bg-white/10' : 'bg-white/5') }}
                                     transition-colors duration-200">
                                <div class="font-bold mb-2 {{ $isCurrentMonth ? 'text-[#5b9fe3]' : 'text-gray-400' }}">
                                    {{ $currentDay->format('j') }}
                                </div>
                                @if(isset($lessons[$currentDay->format('Y-m-d')]))
                                    <div class="space-y-2">
                                        @foreach($lessons[$currentDay->format('Y-m-d')]->sortBy('reservationTime') as $lesson)
                                            <div class="p-2 bg-white/10 rounded-lg hover:bg-[#5b9fe3]/20 
                                                        transition-all duration-200 transform hover:scale-[1.02]">
                                                <p class="font-bold text-[#5b9fe3] text-sm">
                                                    {{ \Carbon\Carbon::parse($lesson->reservationTime)->format('H:i') }}
                                                </p>
                                                <p class="text-sm font-semibold mb-1 truncate">
                                                    {{ $lesson->user->contact->firstName }}
                                                </p>
                                                <p class="text-xs text-gray-300 truncate">{{ $lesson->package->name }}</p>
                                                <p class="text-xs text-gray-300 truncate">{{ $lesson->location->name }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            @php $currentDay->addDay(); @endphp
                        @endwhile
                    </div>

                    <!-- Mobile view -->
                    <div class="md:hidden space-y-4">
                        @php
                            $currentDay = \Carbon\Carbon::parse($startDate)->startOfMonth();
                        @endphp
                        @while($currentDay->month === $startDate->month)
                            <div class="border border-[#5b9fe3] rounded-lg p-4 {{ $currentDay->isToday() ? 'bg-[#5b9fe3]/20' : 'bg-white/10' }}">
                                <div class="font-bold mb-2 text-[#5b9fe3]">
                                    {{ $currentDay->format('D j M') }}
                                </div>
                                @if(isset($lessons[$currentDay->format('Y-m-d')]))
                                    <div class="space-y-2">
                                        @foreach($lessons[$currentDay->format('Y-m-d')]->sortBy('reservationTime') as $lesson)
                                            <div class="p-3 bg-white/10 rounded-lg hover:bg-[#5b9fe3]/20 transition-colors">
                                                <p class="font-bold text-[#5b9fe3]">
                                                    {{ \Carbon\Carbon::parse($lesson->reservationTime)->format('H:i') }}
                                                </p>
                                                <p class="text-sm font-semibold mb-1">
                                                    {{ $lesson->user->contact->firstName }}
                                                </p>
                                                <p class="text-xs text-gray-300">{{ $lesson->package->name }}</p>
                                                <p class="text-xs text-gray-300">{{ $lesson->location->name }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-gray-400">Geen lessen</p>
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
