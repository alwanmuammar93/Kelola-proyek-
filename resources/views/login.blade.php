<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>

    {{-- Menampilkan error login --}}
    @if($errors->any())
        <p style="color:red; font-weight:bold;">
            {{ $errors->first('login') }}
        </p>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        {{-- Username --}}
        <label>Username:</label><br>
        <input 
            type="text" 
            name="username" 
            value="{{ old('username') }}" 
            required
        ><br><br>

        {{-- Password --}}
        <label>Password:</label><br>
        <input 
            type="password" 
            name="password" 
            required
        ><br><br>

        <button type="submit">Login</button>
    </form>
</body>
</html>
