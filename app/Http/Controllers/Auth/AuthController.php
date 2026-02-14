<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
 
use App\Models\User;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function Login(Request $request)
{
  return 22 ;
    $request->validate([
        'email'    => ['required' ],
        'password' => ['required'],
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return back()->withErrors(['email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.']);
    }
    // return 22 ;
    Auth::login($user, $request->remember);

    return redirect()->intended('/Dashboard');
}
}
