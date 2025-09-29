<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function index(Request $request){
        return view('roles.roles');
    }
    public function create(Request $request){
        return view('roles.roles');
    }
    public function update(Request $request){
        return view('roles.roles');
    }
    public function destroy(Request $request){
        return view('roles.roles');
    }
}
