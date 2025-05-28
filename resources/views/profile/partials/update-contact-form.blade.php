<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Contactgegevens
        </h2>
    </header>

    <form method="POST" action="{{ route('profile.update.contact') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="firstName" value="Voornaam" />
            <x-text-input id="firstName" name="firstName" type="text" class="mt-1 block w-full"
                :value="old('firstName', $user->contact->firstName ?? '')" required />
        </div>
        <div>
            <x-input-label for="infix" value="Tussenvoegsel" />
            <x-text-input id="infix" name="infix" type="text" class="mt-1 block w-full"
                :value="old('infix', $user->contact->infix ?? '')" />
        </div>
        <div>
            <x-input-label for="lastName" value="Achternaam" />
            <x-text-input id="lastName" name="lastName" type="text" class="mt-1 block w-full"
                :value="old('lastName', $user->contact->lastName ?? '')" required />
        </div>
        <div>
            <x-input-label for="adress" value="Adres" />
            <x-text-input id="adress" name="adress" type="text" class="mt-1 block w-full"
                :value="old('adress', $user->contact->adress ?? '')" required />
        </div>
        <div>
            <x-input-label for="city" value="Woonplaats" />
            <x-text-input id="city" name="city" type="text" class="mt-1 block w-full"
                :value="old('city', $user->contact->city ?? '')" required />
        </div>
        <div>
            <x-input-label for="dateOfBirth" value="Geboortedatum" />
            <x-text-input id="dateOfBirth" name="dateOfBirth" type="date" class="mt-1 block w-full"
                :value="old('dateOfBirth', $user->contact->dateOfBirth ?? '')" required />
        </div>
        @if(Auth::user()->roles->roleName = 'instructor' || Auth::user()->roles->roleName = 'owner')
            <div>
                <x-input-label for="bsnNumber" value="BSN" />
                <x-text-input id="bsnNumber" name="bsnNumber" type="text" class="mt-1 block w-full"
                    :value="old('bsnNumber', $user->contact->bsnNumber ?? '')" required />
            </div>
        @endif
        <div>
            <x-input-label for="mobile" value="Mobiel" />
            <x-text-input id="mobile" name="mobile" type="text" class="mt-1 block w-full"
                :value="old('mobile', $user->contact->mobile ?? '')" required />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Opslaan</x-primary-button>
        </div>
    </form>
</section>