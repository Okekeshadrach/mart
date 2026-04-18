<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class PasswordResetController extends Controller
{
    public function sendResetLink(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->string('email')->toString())->first();

        if ($user) {
            $token = Str::random(64);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                ['token' => Hash::make($token), 'created_at' => now()],
            );

            $resetUrl = rtrim(config('app.frontend_url'), '/')
                . '/reset-password?token=' . $token
                . '&email=' . urlencode($user->email);

            Mail::to($user)->send(new PasswordResetMail($user, $resetUrl));
        }

        return response()->json([
            'message' => 'If an account with that email exists, we\'ve sent a reset link.',
        ]);
    }

    public function reset(Request $request): JsonResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->string('email')->toString())
            ->first();

        if (! $record || ! Hash::check($request->string('token')->toString(), $record->token)) {
            return response()->json([
                'message' => 'Invalid or expired reset token.',
            ], 422);
        }

        if (now()->diffInMinutes($record->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $record->email)->delete();

            return response()->json([
                'message' => 'Reset token has expired. Please request a new one.',
            ], 422);
        }

        $user = User::where('email', $record->email)->first();

        if (! $user) {
            return response()->json([
                'message' => 'User not found.',
            ], 404);
        }

        $user->update(['password' => $request->string('password')->toString()]);

        DB::table('password_reset_tokens')->where('email', $record->email)->delete();

        return response()->json([
            'message' => 'Password has been reset successfully.',
        ]);
    }
}
