<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HeaderController extends Controller
{
    function index() {
        return view('header');
    }

    function navigation() {
        return view('navigation');
    }
}
