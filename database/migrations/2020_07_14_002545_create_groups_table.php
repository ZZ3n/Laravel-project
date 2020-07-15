<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('meeting_id')
                ->nullable(false);
            // apply date
            $table->dateTime('apply_start_date');
            $table->dateTime('apply_end_date');
            // action date
            $table->dateTime('act_start_date');
            $table->dateTime('act_end_date');

            $table->integer('capacity',false,true)
                ->nullable(false);
            // first = first come first serve, check = founder must check.
            $table->enum('approval_opt',['first','check']);

            $table->char('name',200);

            $table->timestamps();

            $table->foreign('meeting_id')
                ->references('id')->on('meetings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropForeign('groups_meeting_id_foreign');
        });
        Schema::dropIfExists('groups');
    }
}
