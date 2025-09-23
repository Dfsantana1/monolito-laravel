<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class FirebaseController extends Controller
{
	public function callback(Request $request)
	{
		$request->validate(['idToken' => 'required|string']);

		try {
			$apiKey = (string) env('FIREBASE_WEB_API_KEY');
			if ($apiKey === '') {
				throw new \RuntimeException('FIREBASE_WEB_API_KEY no configurado en .env');
			}

			$client = Http::asJson();
			if (app()->environment('local')) {
				$client = $client->withoutVerifying();
			}

			$response = $client->retry(2, 150)->post(
				"https://identitytoolkit.googleapis.com/v1/accounts:lookup?key={$apiKey}",
				['idToken' => $request->idToken]
			);

			if (!$response->ok()) {
				throw new \RuntimeException('Fallo al verificar ID token: ' . $response->body());
			}

			$payload = $response->json();
			$firstUser = $payload['users'][0] ?? null;
			if (!$firstUser) {
				throw new \RuntimeException('Respuesta inválida de Firebase: usuario no encontrado');
			}

			$email = $firstUser['email'] ?? null;
			$name = $firstUser['displayName'] ?? ($email ?: 'Usuario');
			if (!$email) {
				throw new \RuntimeException('El token no contiene un email verificado');
			}

			$user = User::firstOrCreate(
				['email' => $email],
				['name' => $name, 'password' => bcrypt(Str::random(40))]
			);

			Auth::login($user, true);

			return response()->json(['ok' => true]);
		} catch (Throwable $e) {
			Log::error('Firebase callback error', [
				'exception' => get_class($e),
				'message' => $e->getMessage(),
				'file' => $e->getFile(),
				'line' => $e->getLine(),
				'trace' => $e->getTraceAsString(),
			]);
			$code = method_exists($e, 'getCode') ? (int) $e->getCode() : 500;
			return response()->json([
				'ok' => false,
				'error' => 'firebase_verification_failed',
				'message' => $e->getMessage(),
				'hint' => 'Configura FIREBASE_WEB_API_KEY en .env (clave Web de Firebase).',
			], $code === 0 ? 500 : $code);
		}
	}
}
