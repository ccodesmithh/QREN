<x-guest-layout>
    <form method="POST" action="{{ route('siswa.login') }}">
        @csrf
        <div>
            <label>NISN</label>
            <input type="text" name="nisn" required autofocus>
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Login Siswa</button>
    </form>
</x-guest-layout>
