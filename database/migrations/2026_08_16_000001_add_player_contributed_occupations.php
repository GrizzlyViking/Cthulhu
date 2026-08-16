<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Occupations become something players write, not just something they pick.
 *
 * An occupation a player invents in the wizard joins the shared list the same
 * way a skill they add does, so the next investigator can take it. `is_custom`
 * marks those apart from the Investigator Handbook's own, which is what the
 * admin page filters on when pruning.
 *
 * The era column was there from the start (`occupations.eras`), so nothing is
 * added for it here.
 */
return new class() extends Migration
{
    public function up(): void
    {
        Schema::table('occupations', function (Blueprint $table) {
            $table->boolean('is_custom')->default(false)->after('name');
            $table->foreignIdFor(User::class, 'created_by')->nullable()->after('is_custom')->constrained('users')->nullOnDelete();
            // Retiring rather than deleting, as with skills and weapons: the
            // id survives, so a character still reads as what they trained as.
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('occupations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by');
            $table->dropColumn(['is_custom', 'deleted_at']);
        });
    }
};
