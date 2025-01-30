<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('issues', function (Blueprint $table) {
            if (!Schema::hasColumn('issues', 'theme_id')) {
                $table->foreignId('theme_id')->nullable()->constrained()->nullOnDelete();
            }
        });
    }

    public function down()
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->dropForeign(['theme_id']);
            $table->dropColumn('theme_id');
        });
    }
};
