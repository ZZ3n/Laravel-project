<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->increments('id');
            $table->char('name',50)
                ->nullable(false);
            $table->integer('founder_id',false,true)->nullable(false);
        $table->integer('views')
            ->default(0)->unsigned();
        $table->text('content')
            ->nullable(false);
        $table->timestamps();
        $table->foreign('founder_id')->references('id')
            ->on('users')->onDelete('cascade')->onUpdate('cascade');
    });
}

/**
 * Reverse the migrations.
 *
 * @return void
 */
public function down()
{
    Schema::table('meetings',function (Blueprint $table) {
        $table->dropForeign('meetings_founder_id_foreign');
    });
    Schema::dropIfExists('meetings');
    }
}
