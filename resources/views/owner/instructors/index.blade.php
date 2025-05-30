<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Instructeurbeheer') }}
            </h2>
            <a href="{{ route('owner.instructors.create') }}" 
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Nieuwe Instructeur
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
                    @if($instructors->isEmpty())
                        <p class="text-center py-4">Er zijn nog geen instructeurs.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-[#5b9fe3]">
                                        <th class="px-4 py-2">Naam</th>
                                        <th class="px-4 py-2">Email</th>
                                        <th class="px-4 py-2">BSN</th>
                                        <th class="px-4 py-2">Aantal klanten</th>
                                        <th class="px-4 py-2">Acties</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($instructors as $instructor)
                                        <tr class="border-t">
                                            <td class="px-4 py-2">
                                                {{ $instructor->user->contact->firstName }}
                                                {{ $instructor->user->contact->lastName }}
                                            </td>
                                            <td class="px-4 py-2">{{ $instructor->user->email }}</td>
                                            <td class="px-4 py-2">{{ $instructor->user->contact->bsnNumber }}</td>
                                            <td class="px-4 py-2">{{ $instructor->customers->count() }}</td>
                                            <td class="px-4 py-2 space-x-2">
                                                <div class="flex flex-col">
                                                    <div class="flex space-x-2 text-center
                                                                md:flex-col
                                                    ">
                                                        <a href="{{ route('owner.instructors.schedule.day', $instructor) }}" 
                                                           class="text-green-500 hover:text-green-700">
                                                            Dag overzicht
                                                        </a>
                                                        <a href="{{ route('owner.instructors.schedule.week', $instructor) }}" 
                                                           class="text-green-500 hover:text-green-700">
                                                            Week overzicht
                                                        </a>
                                                        <a href="{{ route('owner.instructors.schedule.month', $instructor) }}" 
                                                           class="text-green-500 hover:text-green-700">
                                                            Maand overzicht
                                                        </a>
                                                    </div>

                                                    <div class="flex flex-row justify-center mt-2
                                                                md:flex-col md:items-center
                                                    ">
                                                        <a href="{{ route('owner.instructors.edit', $instructor) }}" 
                                                        class="text-blue-500 hover:text-blue-700 mr-2 md:mr-0">
                                                            Bewerken
                                                        </a>

                                                        <form class="inline" method="POST" 
                                                            action="{{ route('owner.instructors.destroy', $instructor) }}"
                                                            onsubmit="return confirm('Weet je zeker dat je deze instructeur wilt verwijderen?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-500 hover:text-red-700">
                                                                Verwijderen
                                                            </button>
                                                        </form>
                                                    </div>
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
