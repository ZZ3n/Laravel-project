<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('group_id');
            $table->string('reason')
                ->nullable();
            $table->boolean('approval')
                ->nullable(false)->default(false);
            $table->foreign('user_id')
                ->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('group_id')
                ->references('id')->on('groups')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign('applications_user_id_foreign');
            $table->dropForeign('applications_group_id_foreign');
        });
        Schema::dropIfExists('applications');
    }
}
