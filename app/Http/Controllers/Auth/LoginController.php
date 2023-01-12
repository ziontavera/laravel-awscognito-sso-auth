<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    // Where to redirect users after login.
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        // guest only except logout functions
        $this->middleware('guest')->except('logout', 'cognitoLogout', 'cognitoSwitchAccount');
    }

    // POST to Cognito Host
    // Example COGNITO_HOST/login?client_id=CLIENT_ID&response_type=code&scope=aws.cognito.signin.user.admin+email+openid+phone+profile&redirect_uri=CALLBACK_URL
    public function redirectToExternalAuthServer(): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        // dd(Socialite::driver('cognito')->redirect());
        return Socialite::driver('cognito')->redirect();
    }

    // Callback from AWS Cognito
    // Example: http://myapp.ngrok.io/cognito/callback?code=1234&state=abc
    public function handleExternalAuthCallback(): RedirectResponse
    {
        // dd('gasda');
        // Override default scopes if needed
        $scopes = config('services.cognito.scope');
        // dd(Socialite::driver('cognito')->scopes($scopes)->stateless());
        $user = Socialite::driver('cognito')->scopes($scopes)->stateless()->user(); // NOTE STATELESS - https://stackoverflow.com/questions/30660847/laravel-socialite-invalidstateexception
        // dd($user); // Show the available user attributes
        $authUser = $this->findOrCreateUser($user, 'cognito');
        Auth::login($authUser, true);

        return redirect()->route('home');
    }

    // If a user has registered before using social auth, return the user else, create a new user
    public function findOrCreateUser($user, $provider): User
    {
        // dd('asdasd');
        // Search DB for a user with the provider_id = cognito user sub
        $authUser = User::where('provider_id', $user->user['sub'])->first();
        if ($authUser) {
            // User found
            return $authUser;
        }

        // Access user profile data in cognito user
        $passportUser = $user->user;

        /* EXAMPLE COGNITO USER PROFILE
        "sub" => "88889999-2222-0000-1111-222111110000" // Subject - Cognito UUID of the authenticated user
        "birthdate" => "some_string"
        "email_verified" => "true"
        "gender" => "some gender string"
        "phone_number_verified" => "false"
        "phone_number" => "+61402172740"
        "given_name" => "FirstName"
        "family_name" => "LastName"
        "email" => "example@example.com"
        "username" => "88889999-2222-0000-1111-222111110000"
        */

        // Create new local user
        return User::create([
            // 'first_name'     => $passportUser['given_name'],
            // 'last_name'     => $passportUser['family_name'],
            'email'    => $passportUser['email'],
            'provider' => $provider,
            'provider_id' => $passportUser['sub']
        ]);
    }

    // Logout of cognito, logout of app, redirect to specified logout url
    // Notes: Must be SSL, cognito and env sign out url must match. Ngrok has issues here so I use an external url instead.
    public function cognitoLogout()
    {

        // Log out app
        Auth::logout();

        // Call cognito logout url
        // dd(Socialite::driver('cognito')->logoutCognitoUser());
        return Redirect(Socialite::driver('cognito')->logoutCognitoUser());
    }

    // Logout of cognito, logout of app, redirect to cognito login.
    // Notes: Must be SSL, cognito and env redirect url must match. Use Ngrok for dev SSL simulation.
    public function cognitoSwitchAccount()
    {

        // Log out app
        Auth::logout();

        // Override default scopes if needed
        $scopes = explode(",", env('COGNITO_LOGIN_SCOPE'));

        // Call cognito logout url
        return Redirect(Socialite::driver('cognito')->scopes($scopes)->switchCognitoUser());
    }
}
