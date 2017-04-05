<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('campaign_apps', function($table)
    {
      $table->bigIncrements('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->string('name', 32)->nullable();
      $table->string('api_token', 60);
      $table->boolean('active')->default(true);
      $table->json('settings')->nullable();

      // Image
      $table->string('photo_file_name')->nullable();
      $table->integer('photo_file_size')->nullable();
      $table->string('photo_content_type')->nullable();
      $table->timestamp('photo_updated_at')->nullable();

      $table->timestamps();
    });

    Schema::create('campaigns', function($table)
    {
      $table->bigIncrements('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->string('name', 32)->nullable();
      $table->string('api_token', 60)->nullable()->unique();
      $table->dateTime('date_start')->nullable();
      $table->dateTime('date_end')->nullable();
      $table->string('language', 5)->nullable();
      $table->string('timezone', 32)->nullable();
      $table->boolean('active')->default(true);
      $table->tinyInteger('zoom')->nullable();
      $table->decimal('lat', 17, 14)->nullable();
      $table->decimal('lng', 18, 15)->nullable();
      $table->integer('radius')->nullable()->unsigned();
      $table->json('segment')->nullable();
      $table->json('settings')->nullable();

      // Image
      $table->string('photo_file_name')->nullable();
      $table->integer('photo_file_size')->nullable();
      $table->string('photo_content_type')->nullable();
      $table->timestamp('photo_updated_at')->nullable();

      $table->timestamps();
    });

    // Add lat/lng
    DB::statement('ALTER TABLE campaigns ADD location POINT' );

    Schema::create('app_campaigns', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->bigInteger('app_id')->unsigned();
      $table->foreign('app_id')->references('id')->on('campaign_apps')->onDelete('cascade');
      $table->bigInteger('campaign_id')->unsigned();
      $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('campaigns');
    Schema::drop('campaign_apps');
    Schema::drop('app_campaigns');
  }
}
