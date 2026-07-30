<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class() extends Migration
{
    public function up(): void
    {
        DB::table('weapons')->insert([
            'name'           => 'Brawl (Unarmed)',
            'skill'          => 'fighting-brawl',
            'damage'         => '1D3+DB',
            'base_range'     => 'Touch',
            'uses_per_round' => '1',
            'bullets_in_mag' => '-',
            'cost'           => '-',
            'malfunction'    => '-',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('weapons')->where('name', 'Brawl (Unarmed)')->delete();
    }
};
