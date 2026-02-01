<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class PageController extends Controller
{
    public function dashboard()
    {
        $users  = User::with('characters')->get();
        $skills = Skill::all();

        return Inertia::render('Dashboard', compact('users', 'skills'));
    }

    public function welcome()
    {
        return Inertia::render('Welcome', [
            'canLogin'    => Route::has('login'),
            'canRegister' => false,
        ]);
    }

    public function faq()
    {
        return Inertia::render('FAQ');
    }
}
