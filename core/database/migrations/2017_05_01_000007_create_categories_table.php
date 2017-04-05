<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('categories', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->integer('order');
      $table->integer('reseller_id')->unsigned()->nullable();
      $table->foreign('reseller_id')->references('id')->on('resellers')->onDelete('cascade');
      $table->string('name', 128);
      $table->text('description')->nullable();
      $table->boolean('active')->default(true);
      $table->string('icon')->nullable();
      $table->string('image')->nullable();
      $table->json('meta')->nullable();
    });

    // Creates the category_card (Many-to-Many relation) table
    Schema::create('category_card', function($table)
    {
      $table->bigIncrements('id')->unsigned();
      $table->bigInteger('category_id')->unsigned();
      $table->bigInteger('card_id')->unsigned();
      $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
      $table->foreign('card_id')->references('id')->on('cards')->onDelete('cascade');
    });

    // Creates the category_campaign (Many-to-Many relation) table
    Schema::create('category_campaign', function($table)
    {
      $table->bigIncrements('id')->unsigned();
      $table->bigInteger('category_id')->unsigned();
      $table->bigInteger('campaign_id')->unsigned();
      $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
      $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
    });

    // Creates the category_campaign_app (Many-to-Many relation) table
    Schema::create('category_campaign_app', function($table)
    {
      $table->bigIncrements('id')->unsigned();
      $table->bigInteger('category_id')->unsigned();
      $table->bigInteger('campaign_apps_id')->unsigned();
      $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
      $table->foreign('campaign_apps_id')->references('id')->on('campaign_apps')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('category_campaign_app');
    Schema::drop('category_campaign');
    Schema::drop('category_card');
    Schema::drop('categories');
  }
}
