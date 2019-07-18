<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class PagesController extends Controller
{
    public function root()
    {
        return view('pages.root');
    }

    public function test(User $user)
    {
        
    }
}
