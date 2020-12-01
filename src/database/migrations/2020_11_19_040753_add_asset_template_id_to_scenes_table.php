<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssetTemplateIdToScenesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scenes', function (Blueprint $table) {
            $table->foreignId('asset_template_id')->nullable();
            $table->text('extra_text')->nullable();
        });

        if (!\Bigmom\VeEditor\Models\Folder::where('name', 'Scenes')->first()) {
            $folder = new \Bigmom\VeEditor\Models\Folder;
            $folder->name = 'Scenes';
            $folder->folder_type = 0;
            $folder->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scenes', function (Blueprint $table) {
            $table->dropColumn('asset_template_id');
            $table->dropColumn('extra_text');
        });
    }
}
