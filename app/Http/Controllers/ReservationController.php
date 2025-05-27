<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        return view('reservations.index');
    }

    public function create()
    {
        return view('reservations.create');
    }
}
