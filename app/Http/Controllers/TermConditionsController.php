<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TermConditionsController extends Controller
{
    public function tariffSelection(){
        return view('term-condition.t&c-for-tariff-selection');
    }
    public function joinEVoting(){
        return view('term-condition.t&c-for-join-eVoting');
    }
     public function registrationEVoting(){
        return view('term-condition.t&c-for-registration');
    }
}
