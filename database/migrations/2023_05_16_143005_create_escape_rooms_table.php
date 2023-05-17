<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'escape_rooms',
            function (Blueprint $table) {
                $table->id();
                $table->string('theme')->unique()->comment('The name of the escape room');
                $table->unsignedTinyInteger('max_participants')->default(3);
                $table->unsignedInteger('duration')->comment(
                    'in minutes'
                ); //This duration will repeat daily from opening time (11am) until closing time (9pm)
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('escape_rooms');
    }
};
