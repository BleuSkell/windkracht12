<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Maandoverzicht - {{ \Carbon\Carbon::parse($startDate)->format('F Y') }}
            </h2>
            <div class="flex gap-2 sm:gap-4">
                <a href="{{ route('instructor.schedule.month', ['date' => \Carbon\Carbon::parse($startDate)->subMonth()->toDateString()]) }}" 
                   class="bg-white hover:bg-gray-300 text-[#0e1142] font-bold py-1 sm:py-2 px-2 sm:px-4 rounded text-sm sm:text-base">
                    Vorige Maand
                </a>
                <a href="{{ route('instructor.schedule.month', ['date' => \Carbon\Carbon::parse($startDate)->addMonth()->toDateString()]) }}" 
                   class="bg-white hover:bg-gray-300 text-[#0e1142] font-bold py-1 sm:py-2 px-2 sm:px-4 rounded text-sm sm:text-base">
                    Volgende Maand
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#0e1142] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-3 sm:p-6 text-white">
                    <div class="grid grid-cols-7 gap-2 sm:gap-4">
                        @php
                            $firstDay = \Carbon\Carbon::parse($startDate)->startOfMonth();
                            $lastDay = $firstDay->copy()->endOfMonth();
                            $currentDay = $firstDay->copy()->startOfWeek();
                            $weeks = [];
                            while ($currentDay <= $lastDay) {
                                $weeks[] = $currentDay->copy();
                                $currentDay->addWeek();
                            }
                        @endphp

                        <div class="col-span-7 grid grid-cols-7 gap-2 sm:gap-4 mb-2 sm:mb-4">
                            @foreach(['Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za', 'Zo'] as $day)
                                <div class="text-center font-bold p-1 sm:p-2 bg-[#5b9fe3] rounded-t text-xs sm:text-base">{{ $day }}</div>
                            @endforeach
                        </div>

                        @foreach($weeks as $week)
                            @php
                                $currentDay = $week->copy();
                            @endphp
                            @for($i = 0; $i < 7; $i++)
                                <div class="min-h-[80px] sm:min-h-[100px] border border-[#5b9fe3] rounded-b p-2 sm:p-4 bg-white/10">
                                    <div class="font-bold mb-1 sm:mb-2 text-[#5b9fe3] text-sm sm:text-base">{{ $currentDay->format('j') }}</div>
                                    @if(isset($lessons[$currentDay->format('Y-m-d')]))
                                        @foreach($lessons[$currentDay->format('Y-m-d')]->sortBy('reservationTime') as $lesson)
                                            <div class="mb-2 sm:mb-3 p-2 sm:p-3 bg-white/10 rounded hover:bg-[#5b9fe3]/20 transition-colors">
                                                <p class="font-bold text-[#5b9fe3] text-xs sm:text-base">
                                                    {{ \Carbon\Carbon::parse($lesson->reservationTime)->format('H:i') }}
                                                </p>
                                                <p class="text-xs sm:text-sm font-semibold mb-1">
                                                    {{ $lesson->user->contact->firstName }}
                                                </p>
                                                <p class="text-xs text-gray-300 hidden sm:block">{{ $lesson->package->name }}</p>
                                                <p class="text-xs text-gray-300 hidden sm:block">{{ $lesson->location->name }}</p>
                                                <div class="mt-1 sm:mt-2">
                                                    <span class="text-xs px-1 sm:px-2 py-0.5 sm:py-1 rounded-full 
                                                        {{ $lesson->status === 'confirmed' ? 'bg-green-500/20 text-green-300' : 'bg-gray-500/20 text-gray-300' }}">
                                                        {{ $lesson->status === 'confirmed' ? 'Definitief' : 'Voorlopig' }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                    @php
                                        $currentDay->addDay();
                                    @endphp
                                </div>
                            @endfor
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-end p-4">
                    <a href="{{  route('dashboard') }}">
                        <x-secondary-button class="text-sm sm:text-base">
                            Terug naar dashboard
                        </x-secondary-button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
