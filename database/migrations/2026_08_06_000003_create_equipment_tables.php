<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Equipment: the catalogue an investigator buys from, the places they keep
 * things, and the two extra columns that turn `equipables` into a full
 * possessions list.
 *
 * `equipables` already carried weapons as a morph pivot, so equipment joins it
 * as a second morphed type rather than getting a table of its own. That is what
 * lets the Equipment tab list a revolver and a kerosene lantern side by side,
 * each with the drawer it lives in.
 */
return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('equipment_items', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            // The book's section heading, kept so the picker groups the way the
            // printed pages do.
            $table->string('section')->nullable();
            // The price cell verbatim — ranges, cent signs and "per lb." intact.
            // Only ever shown while choosing; never stored against what is owned.
            $table->string('cost')->nullable();
            $table->string('era')->nullable();
            // Where the row came from: the handbook, or a player who typed
            // something the catalogue did not have.
            $table->boolean('is_custom')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('section');
        });

        Schema::create('storage_locations', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->unsignedSmallInteger('order_by')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('equipables', function (Blueprint $table): void {
            $table->foreignId('storage_location_id')->nullable()->after('equipable_id')
                ->constrained('storage_locations')->nullOnDelete();
            $table->unsignedSmallInteger('quantity')->default(1)->after('ammo_reserve');
            $table->string('notes')->nullable()->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('equipables', function (Blueprint $table): void {
            $table->dropForeign(['storage_location_id']);
            $table->dropColumn(['storage_location_id', 'quantity', 'notes']);
        });

        Schema::dropIfExists('storage_locations');
        Schema::dropIfExists('equipment_items');
    }
};
