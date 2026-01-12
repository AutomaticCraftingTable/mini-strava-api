<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;

class EmailVerificationController extends Controller
{
    /**
     * Mark the authenticated user's email as verified via signed link.
     */
    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return view('verification', ['message' => 'Invalid verification link.', 'status' => 'error']);
        }

        if ($user->hasVerifiedEmail()) {
            return view('verification', ['message' => 'Email already verified.', 'status' => 'info']);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return view('verification', ['message' => 'Email verified successfully.', 'status' => 'success']);
    }

    /**
     * Resend the email verification notification to the authenticated user.
     */
    public function resend(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.'], 400);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification email resent.'], 202);
    }
}
