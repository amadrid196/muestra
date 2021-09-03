<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class menuCrtl extends Controller
{
    public function menuUsuarioVw()
    {
        return view('menuUsuarioVw');
    }
}
