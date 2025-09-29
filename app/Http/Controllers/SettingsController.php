<?php

namespace App\Http\Controllers;

use App\Models\FileStorage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index(Request $request){
        return view('settings.settings');
    }
    public function update(Request $request){
        session()->flash('type', "infos");
        $data = $request->validate(
            [
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required|email|unique:users,email,'.auth()->user()->id,
                'phone' => 'required|min:9|max:9|unique:users,phone,'.auth()->user()->id,
            ]
        );

        try {
            // check if the user exist first
            $user = User::where('email', auth()->user()->email)->firstOrFail();
            // check if he password match
            $user->email = $data["email"];
            $user->firstname = $data["firstname"];
            $user->lastname = $data["lastname"];
            $user->phone = $data["phone"];
            $user->save();
            return back()->with("message", "informations modifiées");
        } catch (\Throwable $th) {
            return back()->with("error", "une erreur c'est produite!");
        }
    }
    public function updatePwd(Request $request){
        session()->flash('type', "password");
        $data = $request->validate(
            [
                'oldpassword' => 'required|min:8',
                'newpassword' => 'required|min:8',
            ]
        );

        try {
            // check if the user exist first
            $user = User::where('email', auth()->user()->email)->firstOrFail();
            // check if he password match
            if (!Hash::check($data['oldpassword'], $user->password)) {
                return back()->with("error", "Ancien mot de passe incorect");
            }
            $user->password = Hash::make($data['newpassword']);
            $user->save();
            return back()->with("message", "mot de passe modifié");
        } catch (\Throwable $th) {
            return back()->with("error", "une erreur c'est produite!");
        }
    }
    public function updateProfil(Request $request){
        session()->flash('type', "profil");
        $data = $request->validate(
            [
                'profil' => 'required|file',
            ]
        );

        try {
            $user = User::where('email', auth()->user()->email)->firstOrFail();
            $path = FileStorage::storeFile("profil", $data['profil'], 'public');
            if ($path["path"] == "error") {
                return back()->with("error", $path["message"]);
            }
            $oldPath = $user->profil;
            $user->profil = $path["path"];
            $user->save();
            if ($oldPath) {
                FileStorage::removeFile($oldPath);
            }
            return back()->with("message", "profil modifié");
        } catch (\Throwable $th) {
            return back()->with("error", "une erreur c'est produite!");
        }
    }
    public function globalUpdate(Request $request){
        session()->flash('type', "config");
        $data = $request->validate([
            'year' => 'required|string',
            'trimestre' => 'string',
        ]);
        if (array_key_exists('trimestre',$data)) {
            session(['trimestre'=> $data['trimestre']]);
        }
        session(['year' => $data['year']]);

        return back()->with("message", "Année d'exercie et/ou trimestre modifié(e)");
    }
}
