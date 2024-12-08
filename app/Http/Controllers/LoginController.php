<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index(){
        return view('content.login.login');
    }
    public function checkLogin(Request $request){
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
    
        // Perform user authentication here
        // Example:
        $user = User::where('email', $request->username)
                    ->orWhere('phone_no', $request->username)
                    ->orWhere('name', $request->username)
                    ->first();
    
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            return redirect('/dashboard/analytics')->with('success', 'Login successful!');
        }
    
        return back()->withErrors(['Invalid username or password.']);
    }
    public function logout(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                Auth::logout();  
                $request->session()->invalidate(); 
                $request->session()->regenerateToken(); 
                return redirect()->route('login')->with('success', 'Logged out successfully!');
            }
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('dashboard/ecommerce')->with('success', 'Logged out successfully!');
        }
    
        return redirect()->route('login');  
    }
}
