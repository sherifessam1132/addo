<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementReportTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertisement_report_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('advertisement_report_id')->unsigned();
            $table->string('name');
            $table->string('locale')->index();
            $table->unique(['advertisement_report_id','locale'],'advertisement_report_locale');
            $table->foreign('advertisement_report_id','advertisement_report_id')->references('id')->on('advertisement_reports')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advertisement_report_translations');
    }
}
