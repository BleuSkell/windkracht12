<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
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

            <div class="bg-[#0e1142] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    @if($availableCustomers->isEmpty())
                        <p class="text-center py-4">Er zijn geen beschikbare klanten om te koppelen.</p>
                    @else
                        <form method="POST" action="{{ route('instructor.customers.store') }}" class="space-y-6">
                            @csrf
                            <div>
                                <x-input-label for="customer_id" value="Selecteer een klant" />
                                <select id="customer_id" name="customer_id" class="mt-1 block w-full rounded-md border-[#5b9fe3] bg-white text-[#0e1142]" required>
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
                                <a href="{{ route('instructor.customers.index') }}">
                                    <x-secondary-button class="mr-4">
                                        Annuleren
                                    </x-secondary-button>
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