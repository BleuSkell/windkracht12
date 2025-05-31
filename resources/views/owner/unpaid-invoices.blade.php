<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Openstaande Betalingen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#0e1142] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($unpaidInvoices->isEmpty())
                        <p class="text-center py-4">Er zijn geen openstaande betalingen.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="bg-[#5b9fe3] text-white">
                                        <th class="px-4 py-2 text-left">Factuurnummer</th>
                                        <th class="px-4 py-2 text-left">Klant</th>
                                        <th class="px-4 py-2 text-left">Pakket</th>
                                        <th class="px-4 py-2 text-left">Bedrag</th>
                                        <th class="px-4 py-2 text-left">Vervaldatum</th>
                                        <th class="px-4 py-2 text-left">Status</th>
                                        <th class="px-4 py-2 text-right">Acties</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($unpaidInvoices as $invoice)
                                        <tr class="border-t {{ \Carbon\Carbon::parse($invoice->dueDate)->isPast() ? 'bg-red-50 dark:bg-red-900' : '' }}">
                                            <td class="px-4 py-2">{{ $invoice->invoiceNumber }}</td>
                                            <td class="px-4 py-2">
                                                {{ $invoice->reservation->user->contact->firstName }}
                                                {{ $invoice->reservation->user->contact->lastName }}
                                            </td>
                                            <td class="px-4 py-2">{{ $invoice->reservation->package->name }}</td>
                                            <td class="px-4 py-2">€{{ number_format($invoice->amount, 2) }}</td>
                                            <td class="px-4 py-2">
                                                {{ \Carbon\Carbon::parse($invoice->dueDate)->format('d-m-Y') }}
                                                @if(\Carbon\Carbon::parse($invoice->dueDate)->isPast())
                                                    <span class="text-red-600 dark:text-red-400">
                                                        ({{ \Carbon\Carbon::parse($invoice->dueDate)->diffForHumans() }})
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2">
                                                <span class="px-2 py-1 text-xs rounded-full bg-red-800 text-red-100">
                                                    Niet betaald
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 text-right space-x-2">
                                                @if($invoice->status === 'paid' && $invoice->reservation->status !== 'confirmed')
                                                    <form method="POST" 
                                                          action="{{ route('owner.reservations.confirm', $invoice->reservation) }}"
                                                          class="inline-block">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm"
                                                                onclick="return confirm('Weet je zeker dat je deze reservering definitief wilt maken?')">
                                                            Definitief maken
                                                        </button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('owner.customers.lessons', $invoice->reservation->user->customer) }}" 
                                                   class="text-blue-600 hover:text-blue-900">
                                                    Bekijk Reservering
                                                </a>
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
