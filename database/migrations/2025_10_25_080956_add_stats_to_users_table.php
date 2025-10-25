<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mengecek dan menambah kolom satu per satu di luar closure
        if (!Schema::hasColumn('users', 'exp')) {
            Schema::table('users', function (Blueprint $table) {
                $table->bigInteger('exp')->default(0)->after('password');
            });
        }

        if (!Schema::hasColumn('users', 'gold')) {
            Schema::table('users', function (Blueprint $table) {
                $table->bigInteger('gold')->default(0)->after('exp');
            });
        }

        if (!Schema::hasColumn('users', 'intelligence')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('intelligence')->default(1)->after('gold');
            });
        }

        if (!Schema::hasColumn('users', 'strength')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('strength')->default(1)->after('intelligence');
            });
        }

        if (!Schema::hasColumn('users', 'stamina')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('stamina')->default(1)->after('strength');
            });
        }

        if (!Schema::hasColumn('users', 'agility')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('agility')->default(1)->after('stamina');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $columns = ['exp', 'gold', 'intelligence', 'strength', 'stamina', 'agility'];

        Schema::table('users', function (Blueprint $table) use ($columns) {
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
