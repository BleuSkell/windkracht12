<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(Auth::user()->roles->roleName === 'instructor')
                        <a href="{{ route('instructor.customers.index') }}">
                            Klanten
                        </a>

                        <a href="{{ route('instructor.schedule.day') }}">
                            Dagoverzicht
                        </a>

                        <a href="{{ route('instructor.schedule.week') }}">
                            Weekoverzicht
                        </a>

                        <a href="{{ route('instructor.schedule.month') }}">
                            Maandoverzicht
                        </a>
                    @elseif(Auth::user()->roles->roleName === 'owner')
                        <a href="{{ route('users.index') }}">
                            Gebruikers
                        </a>

                        <a href="{{ route('owner.customers.index') }}">
                            Klanten
                        </a>

                        <a href="{{ route('owner.instructors.index') }}">
                            Instructeurs
                        </a>

                        <a href="{{ route('owner.unpaid-invoices') }}">
                            Onbetaalde reserveringen
                        </a>
                    @else
                        <a href="{{ route('reservations.index') }}">
                            Reserveringen beheren
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
