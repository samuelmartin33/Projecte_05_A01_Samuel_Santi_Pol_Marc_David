<?php

namespace App\Http\Controllers;

use App\Mail\PasswordResetMail;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    public function showForgotForm(): View
    {
        return view('forgot-password');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate(
            ['email' => ['required', 'email', 'max:255']],
            [
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email'    => 'Introduce un correo electrónico válido.',
                'email.max'      => 'El correo no puede superar los 255 caracteres.',
            ]
        );

        $usuario = Usuario::where('email', $request->email)->where('estado', 1)->first();

        // Mensaje genérico para evitar enumeración de emails
        $mensaje = 'Si el correo existe en nuestro sistema, recibirás las instrucciones en breve.';

        if (!$usuario) {
            return back()->with('status', $mensaje);
        }

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        $token = Str::random(64);

        DB::table('password_reset_tokens')->insert([
            'email'      => $request->email,
            'token'      => Hash::make($token),
            'created_at' => now(),
        ]);

        $resetUrl = route('password.reset', [
            'token' => $token,
            'email' => $request->email,
        ]);

        try {
            Mail::to($usuario->email, $usuario->nombre)
                ->send(new PasswordResetMail($usuario, $resetUrl));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('No se pudo enviar el correo de reset: ' . $e->getMessage());
        }

        return back()->with('status', $mensaje);
    }

    public function showResetForm(Request $request, string $token): View
    {
        return view('reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'email'                 => ['required', 'email'],
            'token'                 => ['required', 'string'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withErrors(['token' => 'El enlace de restablecimiento no es válido.']);
        }

        if (now()->diffInMinutes($record->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['token' => 'El enlace ha expirado. Solicita uno nuevo.']);
        }

        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario) {
            return back()->withErrors(['email' => 'No se encontró ninguna cuenta con ese correo.']);
        }

        $usuario->update(['password_hash' => $request->password]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')
            ->with('status', '¡Contraseña actualizada! Ya puedes iniciar sesión.');
    }
}
