<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Maandoverzicht - {{ \Carbon\Carbon::parse($startDate)->format('F Y') }}
            </h2>
            <div class="flex gap-4">
                <a href="{{ route('instructor.schedule.month', ['date' => \Carbon\Carbon::parse($startDate)->subMonth()->toDateString()]) }}" 
                   class="bg-white hover:bg-gray-300 text-[#0e1142] font-bold py-2 px-4 rounded">
                    Vorige Maand
                </a>
                <a href="{{ route('instructor.schedule.month', ['date' => \Carbon\Carbon::parse($startDate)->addMonth()->toDateString()]) }}" 
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
                    <div class="grid grid-cols-7 gap-4">
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

                        <div class="col-span-7 grid grid-cols-7 gap-4 mb-4">
                            @foreach(['Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za', 'Zo'] as $day)
                                <div class="text-center font-bold p-2 bg-[#5b9fe3] rounded-t">{{ $day }}</div>
                            @endforeach
                        </div>

                        @foreach($weeks as $week)
                            @php
                                $currentDay = $week->copy();
                            @endphp
                            @for($i = 0; $i < 7; $i++)
                                <div class="min-h-[100px] border border-[#5b9fe3] rounded-b p-4 bg-white/10">
                                    <div class="font-bold mb-2 text-[#5b9fe3]">{{ $currentDay->format('j') }}</div>
                                    @if(isset($lessons[$currentDay->format('Y-m-d')]))
                                        @foreach($lessons[$currentDay->format('Y-m-d')]->sortBy('reservationTime') as $lesson)
                                            <div class="mb-3 p-3 bg-white/10 rounded hover:bg-[#5b9fe3]/20 transition-colors">
                                                <p class="font-bold text-[#5b9fe3]">
                                                    {{ \Carbon\Carbon::parse($lesson->reservationTime)->format('H:i') }}
                                                </p>
                                                <p class="text-sm font-semibold mb-1">
                                                    {{ $lesson->user->contact->firstName }}
                                                </p>
                                                <p class="text-xs text-gray-300">{{ $lesson->package->name }}</p>
                                                <p class="text-xs text-gray-300">{{ $lesson->location->name }}</p>
                                                <div class="mt-2">
                                                    <span class="text-xs px-2 py-1 rounded-full 
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

                <div class="flex items-center justify-end mb-4">
                    <a href="{{  route('dashboard') }}">
                        <x-secondary-button class="mr-3">
                            Terug naar dashboard
                        </x-secondary-button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
