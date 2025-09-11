<x-guest-layout>
    <form method="POST" action="{{ route('guru.login') }}">
        @csrf
        <div>
            <label>ID Guru</label>
            <input type="text" name="idguru" required autofocus>
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Login Guru</button>
    </form>
</x-guest-layout>
