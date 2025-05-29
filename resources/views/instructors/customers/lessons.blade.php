<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lessen van') }} {{ $customer->user->contact->firstName }} {{ $customer->user->contact->lastName }}
        </h2>
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
                    @if($lessons->isEmpty())
                        <p class="text-center">Deze klant heeft nog geen lessen.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2">Datum</th>
                                        <th class="px-4 py-2">Tijd</th>
                                        <th class="px-4 py-2">Pakket</th>
                                        <th class="px-4 py-2">Locatie</th>
                                        <th class="px-4 py-2">Status</th>
                                        <th class="px-4 py-2">Notities</th>
                                        <th class="px-4 py-2">Acties</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lessons as $lesson)
                                        <tr>
                                            <td class="px-4 py-2">
                                                {{ $lesson->reservationDate }}
                                            </td>
                                            <td class="px-4 py-2">
                                                {{ $lesson->reservationTime }}
                                            </td>
                                            <td class="px-4 py-2">
                                                {{ $lesson->package->name }}
                                            </td>
                                            <td class="px-4 py-2">
                                                {{ $lesson->location->name }}
                                            </td>
                                            <td class="px-4 py-2">
                                                {{ ucfirst($lesson->status ?? 'pending') }}
                                            </td>
                                            <td class="px-4 py-2">
                                                {{ $lesson->notes ?? '-' }}
                                            </td>
                                            <td class="px-4 py-2">
                                                <button onclick="openEditModal('{{ $lesson->id }}')"
                                                        class="text-blue-600 hover:text-blue-800">
                                                    Bewerken
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Edit Modal -->
                        <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
                            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                <form id="editForm" method="POST" class="space-y-4">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="lesson_id" id="lessonId">
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Status</label>
                                        <select name="status" class="mt-1 block w-full rounded-md border-gray-300">
                                            <option value="completed">Voltooid</option>
                                            <option value="cancelled">Geannuleerd</option>
                                            <option value="pending">In afwachting</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Notities</label>
                                        <textarea name="notes" rows="3" 
                                            class="mt-1 block w-full rounded-md border-gray-300"></textarea>
                                    </div>

                                    <div class="flex justify-end space-x-2">
                                        <button type="button" onclick="closeEditModal()"
                                            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                                            Annuleren
                                        </button>
                                        <button type="submit"
                                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                            Opslaan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(lessonId) {
            document.getElementById('lessonId').value = lessonId;
            document.getElementById('editForm').action = `{{ route('instructor.customers.lessons.update', $customer->id) }}`;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
