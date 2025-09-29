<?php

namespace App\Http\Controllers;

use App\Models\JWTModel;
use App\Models\User;
use App\Notifications\EmailVerification;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Display the login view
     */
    public function loginView()
    {
        return view('auth.login');
    }
    /**
     * Display the login view
     */
    public function login(Request $request)
    {
        $data = $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required',
            ]
        );

        try {
            // check if the user exist first
            $user = User::where('email', $data['email'])->firstOrFail();
            // check if he password match
            if (!Hash::check($data['password'], $user->password)) {
                return redirect()->back()->with("error", "nom d'utilisateur ou mot de passe incorect")->withInput();
            }
            // check if the email is already verified
            if (is_null($user->email_verified_at)) {
                $user->notify(new EmailVerification());
                return redirect()->back()->with("message", "votre email n'est pas encore verifier ! ouvrez votre boite mail pour terminer la configuration.");
            }
            // log in the user
            if (Auth::attempt($data)) {
                $request->session()->regenerate();
                session([
                    'trimestre' => -1,
                    'year' => Carbon::parse(now())->year
                ]);
                return redirect()->route('dashboard.index')->with("message", "vous etes conecter!");
            }
            return redirect()->back()->with("error", "nom d'utilisateur ou mot de passe incorect")->withInput();
        } catch (\Throwable $th) {
            return redirect()->back()->with("error", "nom d'utilisateur ou mot de passe incorect!")->withInput();
        }
    }
    /**
     * Display the register view
     */
    // public function registerView()
    // {
    //     return view('auth.register');
    // }
    // public function register(Request $request)
    // {
    //     $data = $request->validate(
    //         [
    //             'username' => 'required',
    //             'password' => 'required',
    //             'phone' => 'required|min:9|max:9|unique:users,phone',
    //             'cni' => 'required|alpha_num|unique:users,num_cni',
    //             'email' => 'required|email|unique:users,email',
    //         ]
    //     );
    //     try {
    //         // hash password
    //         $data['password'] = Hash::make($data['password']);
    //         // change username to firstname, cni to num_cni and add role
    //         $data['firstname'] = $data['username'];
    //         $data['num_cni'] = $data['cni'];
    //         $data['role_id'] = 1;
    //         // drop username and cni key from data array
    //         $data = Arr::except($data, ["cni", "username"]);
    //         // insert in data base
    //         User::create($data);
    //         return redirect()->route('login')->with("message", "votre compte a été creer veuillez verifier votre boite mail pour finaliser la creation de votre compte!");
    //     } catch (\Throwable $th) {
    //         return redirect()->back()->with("error", "une erreur s'est produite veuillez reessayer!");
    //     }
    // }
    /**
     * Display the recover view
     */
    public function recoverView()
    {
        return view('auth.recover');
    }
    // recover the password method
    public function recover(Request $request)
    {
        $data = $request->validate(
            [
                'email' => 'required|email|exists:users,email',
                'cni' => 'required|exists:users,num_cni',
            ]
        );
        try {
            // check if the user exist first
            $user = User::where('email', $data['email'])->firstOrFail();
            if (!($user->num_cni == $data['cni'])) {
                return back(422)->with('error', 'email ou numero de cni invalide veuillez reesayer!');
            }
            // then send the mail
            $user->notify(new EmailVerification('reset'));

            return back()->with('message', 'lien de reinitialisation valide pour 2h a été envoyer avec success!');
        } catch (\Throwable $th) {
            return redirect()->back()->with("error", "une erreur s'est produite veuillez reessayer!");
        }
    }
    /**
     * Display the reinitialise view
     */
    public function reinitialiseView(Request $request, string $token)
    {
        // validate the token
        if (!JWTModel::is_jwt_valid($token, env('JWT_SECRET_KEY'))) {
            return redirect()->route('recover')->with("message", "le lien a expirer veuillez reessayer!");
        }
        return view('auth.reinitialise', ["token" => $token]);
    }
    public function reinitialise(Request $request, $token)
    {
        $data = $request->validate(
            [
                'password' => 'required|confirmed',
                'password_confirmation' => 'same:password'
            ]
        );
        try {
            if (!JWTModel::is_jwt_valid($token, env('JWT_SECRET_KEY'))) {
                return redirect()->route('recover')->with("message", "le lien a expirer veuillez reessayer!");
            }
            // get the user
            $tokenParts = explode('.', $token);
            $payload = base64_decode(base64_decode($tokenParts[0]));
            $user = User::where('email', json_decode($payload)->email)->firstOrFail();

            // change user password
            $user->password = Hash::make($data['password']);
            // save modififcation
            $user->save();
            return redirect()->route('login')->with("message", "mot de passe modifié avec success!");
        } catch (\Throwable $th) {
            return redirect()->back()->with("error", "une erreur s'est produite veuillez reessayer!");
        }
    }
    // send email verification
    public function verify(Request $request, $name, $token, $id)
    {

        try {
            // validate the token
            if (!JWTModel::is_jwt_valid($token, env('JWT_SECRET_KEY'))) {
                return redirect()->route('login')->with("message", "essayer de vous connecter pour recevoir un nouveau lien!");
            }
            // get the token part
            $tokenParts = explode('.', $token);
            $payload = base64_decode(base64_decode($tokenParts[0]));
            // get the user
            $user = User::where('email', json_decode($payload)->email)->firstOrFail();
            // check and change user email_verified_at
            $user->email_verified_at = new DateTime();
            // save modififcation
            $user->save();
            return redirect()->route('login')->with("message", "email verifié avec success!");
        } catch (\Throwable $th) {
            return redirect()->back()->with("error", "une erreur s'est produite veuillez reessayer!");
        }
    }

    // logout the user
    public function logout(Request $request)
    {
        $name = Auth::user()->firstname;
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('auth.login')->with('message', 'merci de votre passage monsieur ' . $name . ' à la prochaine');
    }
}
