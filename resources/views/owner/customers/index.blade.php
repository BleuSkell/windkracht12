<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Klantenbeheer') }}
            </h2>
            <a href="{{ route('owner.customers.create') }}" 
               class="bg-white text-[#0e1142] hover:bg-gray-300 transition-all duration-300 ease-in-out font-bold py-2 px-4 rounded">
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

            <div class="bg-[#0e1142] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($customers->isEmpty())
                        <p class="text-center py-4">Er zijn nog geen klanten.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-center">
                                <thead class="hidden md:table-header-group">
                                    <tr>
                                        <th class="px-6 py-3 bg-[#5b9fe3] text-center">Naam</th>
                                        <th class="px-6 py-3 bg-[#5b9fe3] text-center">Email</th>
                                        <th class="px-6 py-3 bg-[#5b9fe3] text-center">Adres</th>
                                        <th class="px-6 py-3 bg-[#5b9fe3] text-center">Instructeur(s)</th>
                                        <th class="px-6 py-3 bg-[#5b9fe3] text-center">Acties</th>
                                    </tr>
                                </thead>
                                <tbody class="block md:table-row-group">
                                    @foreach($customers as $customer)
                                        <tr class="block md:table-row border-b border-gray-700 md:border-none">
                                            <td class="block md:table-cell px-6 py-4 text-left md:text-center">
                                                <span class="font-bold inline-block md:hidden">Naam: </span>
                                                {{ $customer->user->contact->firstName }}
                                                {{ $customer->user->contact->lastName }}
                                            </td>
                                            <td class="block md:table-cell px-6 py-4 text-left md:text-center">
                                                <span class="font-bold inline-block md:hidden">Email: </span>
                                                {{ $customer->user->email }}
                                            </td>
                                            <td class="block md:table-cell px-6 py-4 text-left md:text-center">
                                                <span class="font-bold inline-block md:hidden">Adres: </span>
                                                {{ $customer->user->contact->adress }}, 
                                                {{ $customer->user->contact->city }}
                                            </td>
                                            <td class="block md:table-cell px-6 py-4 text-left md:text-center">
                                                <span class="font-bold inline-block md:hidden">Instructeur(s): </span>
                                                @foreach($customer->instructors as $instructor)
                                                    {{ $instructor->user->contact->firstName }}
                                                    {{ $instructor->user->contact->lastName }}
                                                    @if(!$loop->last), @endif
                                                @endforeach
                                            </td>
                                            <td class="block md:table-cell px-6 py-4 space-y-2 md:space-y-0 text-left md:text-right">
                                                <span class="font-bold inline-block md:hidden">Acties: </span>
                                                <div class="flex flex-col md:flex-row md:justify-end gap-2">
                                                    <a href="{{ route('owner.customers.lessons', $customer) }}" 
                                                       class="text-blue-500 hover:text-blue-700">
                                                        Lessen
                                                    </a>
                                                    <a href="{{ route('owner.customers.edit', $customer) }}" 
                                                       class="text-blue-500 hover:text-blue-700">
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
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
