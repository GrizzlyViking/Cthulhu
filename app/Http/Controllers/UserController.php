<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        return User::with('characters')->get();
    }

    public function online(): array
    {
        return DB::table('sessions')->whereNotNull('user_id', )->get('user_id')->toArray();
    }
}
