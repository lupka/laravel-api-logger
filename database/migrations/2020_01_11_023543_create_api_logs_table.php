<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('method');
            $table->string('url');
            $table->string('ip');
            $table->string('user_agent');
            $table->text('request_body')->nullable();
            $table->text('request_query_parameters');
            $table->integer('user_id')->nullable();
            $table->integer('status');
            $table->text('response_body');
            $table->string('exception_type')->nullable();
            $table->string('exception_message')->nullable();
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
        Schema::dropIfExists('api_logs');
    }
}
