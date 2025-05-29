<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Koppel een klant') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($availableCustomers->isEmpty())
                        <p class="text-center py-4">Er zijn geen beschikbare klanten om te koppelen.</p>
                    @else
                        <form method="POST" action="{{ route('instructor.customers.store') }}" class="space-y-6">
                            @csrf
                            <div>
                                <x-input-label for="customer_id" value="Selecteer een klant" />
                                <select id="customer_id" name="customer_id" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-100" required>
                                    <option value="">Kies een klant...</option>
                                    @foreach($availableCustomers as $customer)
                                        <option value="{{ $customer->id }}">
                                            {{ $customer->user->contact->firstName }}
                                            {{ $customer->user->contact->lastName }}
                                            ({{ $customer->user->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <a href="{{ route('instructor.customers.index') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mr-3">
                                    Annuleren
                                </a>
                                <x-primary-button>
                                    Klant Koppelen
                                </x-primary-button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>