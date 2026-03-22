<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('messages')) {
            return;
        }

        Schema::create('messages_new', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('subject');
            $table->text('content');
            $table->boolean('read_by_admin')->default(false);
            $table->timestamps();
        });

        $rows = DB::table('messages')->orderBy('id')->get();
        foreach ($rows as $row) {
            DB::table('messages_new')->insert([
                'id' => $row->id,
                'user_id' => $row->user_id,
                'guest_name' => null,
                'guest_email' => null,
                'subject' => $row->subject,
                'content' => $row->content,
                'read_by_admin' => false,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ]);
        }

        Schema::drop('messages');
        Schema::rename('messages_new', 'messages');
    }

    public function down(): void
    {
        if (! Schema::hasTable('messages')) {
            return;
        }

        Schema::create('messages_old', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('subject');
            $table->text('content');
            $table->timestamps();
        });

        $rows = DB::table('messages')->whereNotNull('user_id')->orderBy('id')->get();
        foreach ($rows as $row) {
            DB::table('messages_old')->insert([
                'id' => $row->id,
                'user_id' => $row->user_id,
                'subject' => $row->subject,
                'content' => $row->content,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ]);
        }

        Schema::drop('messages');
        Schema::rename('messages_old', 'messages');
    }
};
