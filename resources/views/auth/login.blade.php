<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Entrar</title>
</head>
<body>
    <h1>Empréstimo de chaves</h1>

    @error('login')
        <p style="color: red">{{ $message }}</p>
    @enderror

    <a href="{{ route('auth.redirect') }}">Entrar com Google</a>
</body>
</html>