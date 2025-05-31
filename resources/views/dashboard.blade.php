<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#0e1142] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-[#0e1142]
                            flex 
                            flex-col items-start justify-center h-full
                            md:flex-row md:items-center md:justify-between
                ">
                    @if(Auth::user()->roles->roleName === 'instructor')
                        <a href="{{ route('instructor.schedule.month') }}">
                            <button class="bg-white p-4 rounded-lg mt-4 md:mt-0">
                                {{ __('Maandoverzicht') }}
                            </button>
                        </a>

                        <a href="{{ route('instructor.schedule.week') }}">
                            <button class="bg-white p-4 rounded-lg mt-4 md:mt-0">
                                {{ __('Weekoverzicht') }}
                            </button>
                        </a>

                        <a href="{{ route('instructor.schedule.day') }}">
                            <button class="bg-white p-4 rounded-lg mt-4 md:mt-0">
                                {{ __('Dagoverzicht') }}
                            </button>
                        </a>

                        <a href="{{ route('instructor.customers.index') }}">
                            <button class="bg-white p-4 rounded-lg mt-4 md:mt-0">
                                {{ __('Klanten') }}
                            </button>
                        </a>
                    @elseif(Auth::user()->roles->roleName === 'owner')
                        <a href="{{ route('owner.unpaid-invoices') }}">
                            <button class="bg-white p-4 rounded-lg mt-4 md:mt-0">
                                {{ __('Openstaande facturen') }}
                            </button>
                        </a>

                        <a href="{{ route('users.index') }}">
                            <button class="bg-white p-4 rounded-lg mt-4 md:mt-0">
                                {{ __('Gebruikers beheren') }}
                            </button>
                        </a>

                        <a href="{{ route('owner.instructors.index') }}">
                            <button class="bg-white p-4 rounded-lg mt-4 md:mt-0">
                                {{ __('Instructeurs') }}
                            </button>
                        </a>

                        <a href="{{ route('owner.customers.index') }}">
                            <button class="bg-white p-4 rounded-lg mt-4 md:mt-0">
                                {{ __('Klanten') }}
                            </button>
                        </a>
                    @else
                        <a href="{{ route('reservations.index') }}">
                            <button class="text-black p-4 rounded-lg mt-4 md:mt-0 bg-white hover:bg-gray-200">
                                {{ __('Reserveringen beheren') }}
                            </button>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
