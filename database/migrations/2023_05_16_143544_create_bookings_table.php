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
            'bookings',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('escape_room_id');
                $table->unsignedBigInteger('user_id');
                $table->unsignedTinyInteger('discount')->default(0)->comment('Discount in percentage');
                $table->timestamp('begins_at')->comment('The timeslot this booking will begin at');
                $table->timestamp('ends_at')->comment('The timeslot this booking will end at');
                $table->timestamps();

                $table->foreign('escape_room_id')->references('id')->on('escape_rooms')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('bookings');
    }
};
