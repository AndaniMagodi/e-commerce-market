<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login | Afrimart</title>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="login-body">
<main class="login-shell">
<section class="login-intro">
<x-brand/>
<div>
<span class="eyebrow">BEHIND EVERY GOOD FIND</span>
<h1>A thoughtful store.<br>A simple workspace.</h1>
<p>Keep your catalogue fresh and your next everyday essential ready to discover.</p>
</div>
<small>Afrimart · Store management</small>
</section>
<section class="login-form-panel">
<a class="breadcrumb" href="{{ route('storefront.index') }}">← Back to shop</a>
<div class="login-form">
<span class="eyebrow">ADMINISTRATOR ACCESS</span>
<h2>Welcome back.</h2>
<p class="muted">Sign in to manage your store.</p>
<form method="POST" action="{{ route('login.submit') }}">@csrf<div class="field">
<label for="email">Email address</label>
<input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required>@error('email')<p class="field-error" role="alert">{{ $message }}</p>
@enderror
</div>
<div class="field">
<label for="password">Password</label>
<input id="password" name="password" type="password" autocomplete="current-password" required>@error('password')<p class="field-error">{{ $message }}</p>
@enderror
</div>
<button class="button full-width" type="submit">Sign in →</button>
</form>
<p class="login-security">Administrator access only. Your session is protected.</p>
</div>
</section>
</main>
</body>
</html>