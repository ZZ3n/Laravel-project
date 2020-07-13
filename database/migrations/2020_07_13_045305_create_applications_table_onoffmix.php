<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTableOnoffmix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->char('user_id',20);
            $table->unsignedInteger('group_id');
            $table->primary(['user_id','group_id']);
            $table->boolean('approval')
                ->nullable(false)->default(false);
            $table->foreign('user_id')
                ->references('id')->on('users');
            $table->foreign('group_id')
                ->references('_id')->on('groups');
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
