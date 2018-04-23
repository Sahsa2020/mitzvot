<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SponsorController extends Controller
{
    //
    public function index()
    {
        // $users = User::paginate(10);

        // return view('admin.users.index', compact('users'));
        return view('admin.sponsor.index');
    }
}
