<?php

namespace App\Http\Controllers;

use App\Models\Offrande;
use Illuminate\Http\Request;

class OffrandeController extends Controller
{
    public function index(Request $request,$id){
        return view('associations.offrande.index', compact("id"));
    }
}
