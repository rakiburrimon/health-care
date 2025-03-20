<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FrontEndController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login request and get the token
    public function postLogin(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Call the API directly to login and get the token
        $response = Http::timeout(60) // Increase the timeout to 60 seconds
        ->post('http://localhost:8000/api/v1/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);
dd($response);
        // Check if the response was successful
        // Check if the response is successful and contains a bearer token
        if ($response->successful() && $response->hasHeader('Authorization')) {
            // Extract the bearer token from the response
            $bearerToken = $response->header('Authorization');

            // Store the token in the session or in a way that you can access it in future requests
            session(['bearer_token' => $bearerToken]);

            // Proceed to the next step (e.g., dashboard or user profile)
            return redirect()->route('dashboard');
        } else {
            // If authentication fails, return an error message
            return back()->withErrors(['error' => 'Invalid credentials.']);
        }
    }

    // Show the dashboard
    public function dashboard()
    {
        return view('dashboard');
    }
}
