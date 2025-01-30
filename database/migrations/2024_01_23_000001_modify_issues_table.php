1<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('issues', function (Blueprint $table) {
            if (!Schema::hasColumn('issues', 'publication_date')) {
                $table->timestamp('publication_date')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->dropColumn('publication_date');
        });
    }
};
