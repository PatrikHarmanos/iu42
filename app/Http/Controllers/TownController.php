<?php

namespace App\Http\Controllers;

use App\Models\City;

class TownController extends Controller
{
    public function getIndex()
    {
        $towns = City::all();
        return view('welcome')->with('towns', $towns);
    }

    public function getTown($id)
    {
        $town = City::find($id);
        return view('detail')->with('town', $town);
    }
}
