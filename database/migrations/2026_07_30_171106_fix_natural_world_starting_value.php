<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class() extends Migration
{
    public function up(): void
    {
        // CoC 7th edition: Natural World starting value is 10%, not 1%.
        DB::table('skills')->where('slug', 'natural_world')->update(['starting_value' => 10]);
    }

    public function down(): void
    {
        DB::table('skills')->where('slug', 'natural_world')->update(['starting_value' => 1]);
    }
};
