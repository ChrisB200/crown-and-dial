<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('watch_inventory_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('watch_id')->constrained('watches')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedTinyInteger('size');
            $table->integer('quantity')->default(0);
            $table->timestamps();

            $table->unique(['watch_id', 'size']);
        });

        $bandSizes = config('watch_sizes.band', [36, 38, 40, 42]);

        if (Schema::hasTable('inventories')) {
            $rows = DB::table('inventories')->get();
            foreach ($rows as $inv) {
                foreach ($bandSizes as $s) {
                    DB::table('watch_inventory_sizes')->insert([
                        'watch_id' => $inv->watch_id,
                        'size' => $s,
                        'quantity' => (int) $s === 40 ? (int) $inv->quantity : 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            Schema::drop('inventories');
        }

        $allWatchIds = DB::table('watches')->pluck('id');
        foreach ($allWatchIds as $watchId) {
            $hasRows = DB::table('watch_inventory_sizes')->where('watch_id', $watchId)->exists();
            if ($hasRows) {
                continue;
            }
            foreach ($bandSizes as $s) {
                DB::table('watch_inventory_sizes')->insert([
                    'watch_id' => $watchId,
                    'size' => $s,
                    'quantity' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('watch_id');
            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('watch_id')
                ->references('id')
                ->on('watches')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });

        if (Schema::hasTable('watch_inventory_sizes')) {
            $byWatch = DB::table('watch_inventory_sizes')
                ->select('watch_id', DB::raw('SUM(quantity) as total'))
                ->groupBy('watch_id')
                ->get();

            foreach ($byWatch as $row) {
                DB::table('inventories')->insert([
                    'watch_id' => $row->watch_id,
                    'quantity' => (int) $row->total,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            Schema::drop('watch_inventory_sizes');
        }
    }
};
