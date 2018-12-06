<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cat_id')->unsigned();
            $table->string('name') ;
            $table->text('pic')->nullable() ;
            $table->string('url')->unique();
            $table->string('display_name') ;
            $table->text('content') ;
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('items');
    }
}
