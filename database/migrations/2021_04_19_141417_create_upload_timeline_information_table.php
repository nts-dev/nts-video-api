<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadTimelineInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload_timeline_information', function (Blueprint $table) {
            $table->bigIncrements('id');
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
        Schema::dropIfExists('upload_timeline_information');
    }
}
