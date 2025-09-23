@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col justify-center items-center bg-gray-100 py-8">
	<div class="w-full max-w-md">
		<div class="bg-white shadow-xl rounded-xl p-8">
			<div class="flex flex-col items-center mb-6">
				<x-application-logo class="w-16 h-16 text-gray-500" />
				<h1 class="mt-4 text-2xl font-bold text-gray-900">Crear cuenta</h1>
				<p class="text-gray-500 text-sm">Únete para disfrutar del catálogo</p>
			</div>

			<form method="POST" action="{{ route('register') }}" class="space-y-4">
				@csrf

				<div>
					<label class="block text-sm font-medium text-gray-700">Name</label>
					<input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
					@error('name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
				</div>

				<div>
					<label class="block text-sm font-medium text-gray-700">Email</label>
					<input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
					@error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
				</div>

				<div>
					<label class="block text-sm font-medium text-gray-700">Password</label>
					<input id="password" type="password" name="password" required autocomplete="new-password" class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
					@error('password')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
				</div>

				<div>
					<label class="block text-sm font-medium text-gray-700">Confirm Password</label>
					<input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
				</div>

				<div class="pt-2">
					<button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
						REGISTRARME
					</button>
				</div>
			</form>

			<div class="relative my-6">
				<div class="border-t"></div>
				<span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-white px-3 text-xs text-gray-500">o continúa con</span>
			</div>

			<button id="btn-google" type="button" class="w-full inline-flex gap-3 items-center justify-center px-4 py-2 border border-gray-300 bg-white rounded-lg hover:bg-gray-50">
				<img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" class="w-5 h-5" alt="Google">
				<span class="text-sm font-medium text-gray-700">Registrarse con Google</span>
			</button>

			<p class="mt-6 text-center text-sm text-gray-600">
				¿Ya tienes cuenta?
				<a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">Inicia sesión</a>
			</p>
		</div>
	</div>
</div>
@endsection
