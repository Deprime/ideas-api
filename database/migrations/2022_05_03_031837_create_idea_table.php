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
    Schema::create('idea', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('rownum')->index();
      $table->foreignId('author_id')
            ->references('id')
            ->on('users')
            ->cascadeOnDelete();
      $table->string('title');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('idea');
  }
};
