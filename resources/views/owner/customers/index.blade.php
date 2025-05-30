<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Klantenbeheer') }}
            </h2>
            <a href="{{ route('owner.customers.create') }}" 
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Nieuwe Klant
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-500 text-white p-4 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($customers->isEmpty())
                        <p class="text-center py-4">Er zijn nog geen klanten.</p>
                    @else
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left">Naam</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left">Email</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left">Adres</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left">Instructeur(s)</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-right">Acties</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $customer)
                                    <tr class="border-t">
                                        <td class="px-6 py-4">
                                            {{ $customer->user->contact->firstName }}
                                            {{ $customer->user->contact->lastName }}
                                        </td>
                                        <td class="px-6 py-4">{{ $customer->user->email }}</td>
                                        <td class="px-6 py-4">
                                            {{ $customer->user->contact->adress }}, 
                                            {{ $customer->user->contact->city }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @foreach($customer->instructors as $instructor)
                                                {{ $instructor->user->contact->firstName }}
                                                {{ $instructor->user->contact->lastName }}
                                                @if(!$loop->last), @endif
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('owner.customers.lessons', $customer) }}" 
                                               class="text-blue-500 hover:text-blue-700 mr-2">
                                                Lessen
                                            </a>
                                            <a href="{{ route('owner.customers.edit', $customer) }}" 
                                               class="text-blue-500 hover:text-blue-700 mr-2">
                                                Bewerken
                                            </a>
                                            <form class="inline-block" method="POST" 
                                                  action="{{ route('owner.customers.destroy', $customer) }}"
                                                  onsubmit="return confirm('Weet je zeker dat je deze klant wilt verwijderen?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    Verwijderen
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
