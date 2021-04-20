<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('upload_id');
            $table->text('content');
            $table->index(['id', 'upload_id']);
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
        Schema::table('upload_comments', function (Blueprint $table) {
            //
        });
    }
}
