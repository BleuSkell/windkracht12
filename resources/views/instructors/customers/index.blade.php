<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Mijn Klanten') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('instructor.cancellation-requests') }}" 
                   class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Annuleringsverzoeken
                </a>
                <a href="{{ route('instructor.customers.create') }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Nieuwe Klant
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-500 text-white p-4 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-500 text-white p-4 rounded-lg mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-[#0e1142] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    @if($customers->isEmpty())
                        <p class="text-center py-4">Je hebt nog geen klanten.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-[#5b9fe3]">
                                        <th class="px-4 py-2">Naam</th>
                                        <th class="px-4 py-2">Email</th>
                                        <th class="px-4 py-2">Adres</th>
                                        <th class="px-4 py-2">Woonplaats</th>
                                        <th class="px-4 py-2">Telefoon</th>
                                        <th class="px-4 py-2">Acties</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customers as $customer)
                                        <tr class="border-t">
                                            <td class="px-4 py-2">
                                                {{ $customer->user->contact->firstName }}
                                                {{ $customer->user->contact->lastName }}
                                            </td>
                                            <td class="px-4 py-2">{{ $customer->user->email }}</td>
                                            <td class="px-4 py-2">{{ $customer->user->contact->adress }}</td>
                                            <td class="px-4 py-2">{{ $customer->user->contact->city }}</td>
                                            <td class="px-4 py-2">{{ $customer->user->contact->mobile }}</td>
                                            <td class="px-4 py-2">
                                                <a href="{{ route('instructor.customers.lessons', $customer) }}" 
                                                   class="text-blue-500 hover:text-blue-700 mr-2">
                                                    Lessen
                                                </a>
                                                <a href="{{ route('instructor.customers.edit', $customer) }}" 
                                                   class="text-blue-500 hover:text-blue-700 mr-2">
                                                    Bewerken
                                                </a>
                                                <form class="inline" method="POST" 
                                                      action="{{ route('instructor.customers.destroy', $customer) }}"
                                                      onsubmit="return confirm('Weet je zeker dat je deze klant wilt ontkoppelen?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                                        Ontkoppelen
                                                    </button>
                                                </form>
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