<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrateConverterQueue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_converter_queue', function (Blueprint $table) {
            $table->increments('id');
            $table->char("temp_hash",32);
            $table->char("file_hash",32);
            $table->char("ext",10);
            $table->mediumText('full_path');
            $table->char('class',40);
            $table->dateTime('date');
            $table->char('status',3);
            $table->unique('file_hash');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_converter_queue');
    }
}
