<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class KeeperOfSecretsController extends Controller
{
    public function dashboard()
    {
        $users = User::with('characters')->get();
        return Inertia::render('Dashboard', compact('users'));
    }

    public function welcome()
    {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }
}
