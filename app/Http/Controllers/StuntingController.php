<?php

namespace App\Http\Controllers;

use App\Models\Stunting;

class StuntingController extends Controller
{

    public function api()
    {

        return response()->json(

            Stunting::all()

        );

    }

}