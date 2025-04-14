<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('livres', function (Blueprint $table) {
            $table->boolean('archived')->default(false)->after('stock'); // Ajout du champ archived
        });
    }

    public function down()
    {
        Schema::table('livres', function (Blueprint $table) {
            $table->dropColumn('archived');
        });
    }
};
