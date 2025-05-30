<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#0e1142] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-[#0e1142]">
                    @if(Auth::user()->roles->roleName === 'instructor')
                        <a href="{{ route('instructor.customers.index') }}">
                            <button class="bg-white p-4 rounded-lg">
                                {{ __('Klanten') }}
                            </button>
                        </a>

                        <a href="{{ route('instructor.schedule.day') }}">
                            <button class="bg-white p-4 rounded-lg">
                                {{ __('Dagoverzicht') }}
                            </button>
                        </a>

                        <a href="{{ route('instructor.schedule.week') }}">
                            <button class="bg-white p-4 rounded-lg">
                                {{ __('Weekoverzicht') }}
                            </button>
                        </a>

                        <a href="{{ route('instructor.schedule.month') }}">
                            <button class="bg-white p-4 rounded-lg">
                                {{ __('Maandoverzicht') }}
                            </button>
                        </a>
                    @elseif(Auth::user()->roles->roleName === 'owner')
                        <a href="{{ route('users.index') }}">
                            <button class="bg-white p-4 rounded-lg">
                                {{ __('Gebruikers beheren') }}
                            </button>
                        </a>

                        <a href="{{ route('owner.customers.index') }}">
                            <button class="bg-white p-4 rounded-lg">
                                {{ __('Klanten') }}
                            </button>
                        </a>

                        <a href="{{ route('owner.instructors.index') }}">
                            <button class="bg-white p-4 rounded-lg">
                                {{ __('Instructeurs') }}
                            </button>
                        </a>

                        <a href="{{ route('owner.unpaid-invoices') }}">
                            <button class="bg-white p-4 rounded-lg">
                                {{ __('Openstaande facturen') }}
                            </button>
                        </a>
                    @else
                        <a href="{{ route('reservations.index') }}">
                            <button class="text-black p-4 rounded-lg bg-white hover:bg-gray-200">
                                {{ __('Reserveringen beheren') }}
                            </button>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
