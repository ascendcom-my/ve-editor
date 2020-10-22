<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->tinyInteger('file_type');
            $table->text('requirement');
            $table->integer('sequence');
            $table->foreignId('folder_id')->index();
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
        Schema::dropIfExists('asset_templates');
    }
}
