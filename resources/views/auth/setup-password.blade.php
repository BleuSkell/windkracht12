<x-guest-layout>
    <form method="POST" action="{{ route('password.setup', [$user->id, sha1($user->email)]) }}">
        @csrf

        <div>
            <x-input-label for="password" value="Wachtwoord" />
            <x-text-input id="password" type="password" name="password" required autofocus />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Bevestig wachtwoord" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required />
        </div>

        <div class="mt-4">
            <x-primary-button>
                Stel wachtwoord in
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>