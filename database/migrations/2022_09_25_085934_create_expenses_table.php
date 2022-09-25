<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->string("exp_id", 30)->primary();
            $table->string("userid");
            $table->string("merchant", 30);
            $table->double("amount");
            $table->date("date");
            $table->text("remark");
            $table->text("receipt");
            $table->string("status", 15)->default("pending");
            $table->foreign("userid")->references("userid")->on("users");
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
        Schema::dropIfExists('expenses');
    }
};
