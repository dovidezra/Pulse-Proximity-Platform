<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('cards', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

      $table->bigInteger('published_id')->unsigned()->nullable();
      $table->foreign('published_id')->references('id')->on('cards')->onDelete('cascade');
      $table->boolean('published')->default(false);

      $table->string('name', 250);
      $table->string('title')->nullable();
      $table->text('description')->nullable();
      $table->mediumText('content')->nullable();
      $table->text('link1')->nullable();
      $table->text('link2')->nullable();
      $table->timestamp('valid_from_date')->nullable();
      $table->timestamp('expiration_date')->nullable();
      $table->string('language', 5)->default('en');
      $table->string('timezone', 32)->default('UTC');
      $table->boolean('show_location')->default(false);
      $table->boolean('active')->default(true);
      $table->string('icon')->nullable();
      $table->string('image')->nullable();
      $table->json('meta')->nullable();
      $table->json('settings')->nullable();
      $table->tinyInteger('zoom')->nullable();
      $table->decimal('lat', 17, 14)->nullable();
      $table->decimal('lng', 18, 15)->nullable();
      $table->integer('radius')->nullable()->unsigned();
      $table->timestamps();
    });

    // Add lat/lng
    DB::statement('ALTER TABLE cards ADD location POINT');

    Schema::create('card_stats', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->bigInteger('card_id')->unsigned();
      $table->foreign('card_id')->references('id')->on('cards')->onDelete('cascade');
      $table->integer('member_id')->unsigned()->nullable();
      $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
      $table->string('ip', 40)->nullable();
      $table->uuid('device_uuid')->nullable();
      $table->string('os', 32)->nullable();
      $table->string('client', 32)->nullable();
      $table->string('device', 32)->nullable();
      $table->string('platform', 16)->nullable();
      $table->string('model', 32)->nullable();
      $table->char('country', 2)->nullable();
      $table->string('city', 32)->nullable();
      $table->decimal('lat', 10, 8)->nullable();
      $table->decimal('lng', 11, 8)->nullable();
      $table->json('meta')->nullable();

      $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
    });

    // Creates the campaign_card (Many-to-Many relation) table
    Schema::create('campaign_card', function($table)
    {
      $table->bigIncrements('id')->unsigned();
      $table->bigInteger('campaign_id')->unsigned();
      $table->bigInteger('card_id')->unsigned();
      $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
      $table->foreign('card_id')->references('id')->on('cards')->onDelete('cascade');
    });

    // Creates the geofence_card (Many-to-Many relation) table
    Schema::create('geofence_card', function($table)
    {
      $table->bigIncrements('id')->unsigned();
      $table->bigInteger('geofence_id')->unsigned();
      $table->bigInteger('card_id')->unsigned();
      $table->foreign('geofence_id')->references('id')->on('geofences')->onDelete('cascade');
      $table->foreign('card_id')->references('id')->on('cards')->onDelete('cascade');
    });

    // Creates the beacon_card (Many-to-Many relation) table
    Schema::create('beacon_card', function($table)
    {
      $table->bigIncrements('id')->unsigned();
      $table->bigInteger('beacon_id')->unsigned();
      $table->bigInteger('card_id')->unsigned();
      $table->foreign('beacon_id')->references('id')->on('beacons')->onDelete('cascade');
      $table->foreign('card_id')->references('id')->on('cards')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('beacon_card');
    Schema::drop('geofence_card');
    Schema::drop('card_stats');
    Schema::drop('cards');
  }
}
