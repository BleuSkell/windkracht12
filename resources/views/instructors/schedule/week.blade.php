<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center sm:text-left">
                Weekoverzicht - Week {{ \Carbon\Carbon::parse($startDate)->format('W') }}
            </h2>
            <div class="flex gap-2 sm:gap-4">
                <a href="{{ route('instructor.schedule.week', ['date' => \Carbon\Carbon::parse($startDate)->subWeek()->toDateString()]) }}" 
                   class="bg-white hover:bg-gray-300 text-[#0e1142] font-bold py-1 sm:py-2 px-2 sm:px-4 rounded text-sm sm:text-base">
                    Vorige Week
                </a>
                <a href="{{ route('instructor.schedule.week', ['date' => \Carbon\Carbon::parse($startDate)->addWeek()->toDateString()]) }}" 
                   class="bg-white hover:bg-gray-300 text-[#0e1142] font-bold py-1 sm:py-2 px-2 sm:px-4 rounded text-sm sm:text-base">
                    Volgende Week
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="bg-[#0e1142] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-3 sm:p-6 text-white overflow-x-auto">
                    <!-- Week Header -->
                    <div class="grid grid-cols-7 gap-2 sm:gap-4 mb-2 sm:mb-4 min-w-[700px]">
                        @php
                            $currentDate = \Carbon\Carbon::parse($startDate);
                        @endphp
                        @for($i = 0; $i < 7; $i++)
                            <div class="text-center font-bold p-1 sm:p-2 bg-[#5b9fe3] rounded-t">
                                <div class="text-xs sm:text-base">{{ $currentDate->format('D') }}</div>
                                <div class="text-xs sm:text-base">{{ $currentDate->format('d M') }}</div>
                            </div>
                            @php
                                $currentDate->addDay();
                            @endphp
                        @endfor
                    </div>

                    <!-- Week Grid -->
                    <div class="grid grid-cols-7 gap-2 sm:gap-4 min-h-[400px] sm:min-h-[600px] min-w-[700px]">
                        @php
                            $currentDate = \Carbon\Carbon::parse($startDate);
                        @endphp
                        @for($i = 0; $i < 7; $i++)
                            <div class="border border-[#5b9fe3] rounded-b p-2 sm:p-4 bg-white/10">
                                @php
                                    $dayLessons = $lessons->get($currentDate->format('Y-m-d'), collect());
                                @endphp
                                
                                @if($dayLessons->isEmpty())
                                    <p class="text-xs sm:text-sm text-gray-300">Geen lessen</p>
                                @else
                                    @foreach($dayLessons->sortBy('reservationTime') as $lesson)
                                        <div class="mb-2 sm:mb-3 p-2 sm:p-3 bg-white/10 rounded hover:bg-[#5b9fe3]/20 transition-colors">
                                            <p class="font-bold text-[#5b9fe3] text-xs sm:text-base">
                                                {{ \Carbon\Carbon::parse($lesson->reservationTime)->format('H:i') }}
                                            </p>
                                            <p class="text-xs sm:text-sm font-semibold mb-1">
                                                {{ $lesson->user->contact->firstName }}
                                                <span class="hidden sm:inline">{{ $lesson->user->contact->lastName }}</span>
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
                                    $currentDate->addDay();
                                @endphp
                            </div>
                        @endfor
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
