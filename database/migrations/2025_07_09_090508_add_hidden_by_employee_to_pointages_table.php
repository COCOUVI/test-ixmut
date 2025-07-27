<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pointages', function (Blueprint $table) {
            $table->boolean('hidden_by_employee')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('pointages', function (Blueprint $table) {
            $table->dropColumn('hidden_by_employee');
        });
    }
};
