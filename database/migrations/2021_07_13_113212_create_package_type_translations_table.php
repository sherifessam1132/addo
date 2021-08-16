<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageTypeTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_type_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('package_type_id')->unsigned();
            $table->text('description')->nullable();
            $table->string('locale')->index();
            $table->unique(['package_type_id','locale']);
            $table->foreign('package_type_id')->references('id')->on('package_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package_type_translations');
    }
}
