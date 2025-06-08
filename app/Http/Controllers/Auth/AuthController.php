<?php

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AuthController extends Controller
{
   
  
    

    /**
     * Show the user profile
     */
    public function profile()
    {
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }

    /**
     * Update the user's profile.
     */
    public function updateProfile(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:15',
            'photo' => 'nullable|image|max:2048',
            'profile_photo' => 'nullable|image|max:2048', // Add support for both field names
        ]);

        $user = Auth::user();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('profile', 'public');
            $validatedData['photo'] = $photoPath;
        }
        
        // Handle profile_photo upload (alternative field name)
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile', 'public');
            $validatedData['photo'] = $photoPath; // Store in the photo field
        }

        //$user->update($validatedData);

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }

    /**
     * Show the form to request a password reset link
     */
    public function showForgotPassword()
    {
        return view('auth.passwords.email');
    }

    /**
     * Process the password reset request
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show the password reset form
     */
    public function showResetForm(Request $request, $token)
    {
        return view('auth.passwords.reset', ['token' => $token, 'email' => $request->email]);
    }

    /**
     * Process the password reset
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }
}
