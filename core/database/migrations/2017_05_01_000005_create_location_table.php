<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('scenario_if', function(Blueprint $table)
    {
      $table->increments('id');
      $table->integer('sort')->unsigned();
      $table->string('name', 64);
      $table->boolean('active')->default(true);
    });

    Schema::create('scenario_then', function(Blueprint $table)
    {
      $table->increments('id');
      $table->integer('sort')->unsigned();
      $table->string('name', 64);
      $table->boolean('active')->default(true);
    });

    Schema::create('scenario_day', function(Blueprint $table)
    {
      $table->increments('id');
      $table->integer('sort')->unsigned();
      $table->string('name', 64);
      $table->boolean('active')->default(true);
    });

    Schema::create('scenario_time', function(Blueprint $table)
    {
      $table->increments('id');
      $table->integer('sort')->unsigned();
      $table->string('name', 64);
      $table->boolean('active')->default(true);
    });

    Schema::create('location_groups', function($table)
    {
      $table->bigIncrements('id')->unsigned();
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->string('name', 64);
      $table->json('settings')->nullable();
    });

    Schema::create('scenarios', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->bigInteger('campaign_id')->unsigned();
      $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
      $table->integer('scenario_if_id')->unsigned()->default(1);
      $table->foreign('scenario_if_id')->references('id')->on('scenario_if')->onDelete('cascade');
      $table->integer('scenario_then_id')->unsigned()->nullable();
      $table->foreign('scenario_then_id')->references('id')->on('scenario_then')->onDelete('cascade');
      $table->integer('scenario_day_id')->unsigned()->default(1);
      $table->foreign('scenario_day_id')->references('id')->on('scenario_day')->onDelete('cascade');
      $table->integer('scenario_time_id')->unsigned()->default(1);
      $table->foreign('scenario_time_id')->references('id')->on('scenario_time')->onDelete('cascade');  
      $table->time('time_start')->nullable();
      $table->time('time_end')->nullable();
      $table->date('date_start')->nullable();
      $table->date('date_end')->nullable();
      $table->integer('frequency')->unsigned()->default(0);
      $table->integer('delay')->unsigned()->default(0);
      $table->text('notification')->nullable();
      $table->boolean('active')->default(true);
      $table->text('open_url')->nullable();
      $table->integer('add_points')->unsigned()->nullable();
      $table->integer('substract_points')->unsigned()->nullable();
      $table->json('settings')->nullable();

      // Image
      $table->string('image_file_name')->nullable();
      $table->integer('image_file_size')->nullable();
      $table->string('image_content_type')->nullable();
      $table->timestamp('image_updated_at')->nullable();

      $table->timestamps();
    });

    Schema::create('beacon_uuids', function(Blueprint $table)
    {
      $table->increments('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->uuid('uuid');
    });

    Schema::create('beacons', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->bigInteger('location_group_id')->unsigned()->nullable();
      $table->foreign('location_group_id')->references('id')->on('location_groups');
      $table->string('name', 64);
      $table->text('description')->nullable();
      $table->uuid('uuid')->nullable();
      $table->bigInteger('major')->nullable()->unsigned();
      $table->bigInteger('minor')->nullable()->unsigned();

      // Reference photo
      $table->string('photo_file_name')->nullable();
      $table->integer('photo_file_size')->nullable();
      $table->string('photo_content_type')->nullable();
      $table->timestamp('photo_updated_at')->nullable();

      $table->json('settings')->nullable();
      $table->boolean('active')->default(true);

      $table->tinyInteger('zoom')->nullable();
      $table->decimal('lat', 17, 14)->nullable();
      $table->decimal('lng', 18, 15)->nullable();

      $table->timestamps();
    });

    // Add lat/lng
    DB::statement('ALTER TABLE beacons ADD location POINT' );

    // Creates the beacon_scenario (Many-to-Many relation) table
    Schema::create('beacon_scenario', function($table)
    {
      $table->bigIncrements('id')->unsigned();
      $table->bigInteger('beacon_id')->unsigned();
      $table->bigInteger('scenario_id')->unsigned();
      $table->foreign('beacon_id')->references('id')->on('beacons')->onDelete('cascade');
      $table->foreign('scenario_id')->references('id')->on('scenarios')->onDelete('cascade');
    });

    Schema::create('geofences', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->bigInteger('location_group_id')->unsigned()->nullable();
      $table->foreign('location_group_id')->references('id')->on('location_groups');
      $table->string('name', 64);
      $table->text('description')->nullable();
      $table->char('country', 2)->nullable();
      $table->string('region', 32)->nullable();
      $table->string('city', 24)->nullable();
      $table->string('address', 250)->nullable();

      // Reference photo
      $table->string('photo_file_name')->nullable();
      $table->integer('photo_file_size')->nullable();
      $table->string('photo_content_type')->nullable();
      $table->timestamp('photo_updated_at')->nullable();

      $table->json('settings')->nullable();
      $table->boolean('active')->default(true);

      $table->tinyInteger('zoom')->nullable();
      $table->decimal('lat', 17, 14)->nullable();
      $table->decimal('lng', 18, 15)->nullable();
      $table->integer('radius')->nullable()->unsigned();

      $table->timestamps();
    });

    // Add lat/lng
    DB::statement('ALTER TABLE geofences ADD location POINT');

    // Creates the geofence_scenario (Many-to-Many relation) table
    Schema::create('geofence_scenario', function($table)
    {
      $table->bigIncrements('id')->unsigned();
      $table->bigInteger('geofence_id')->unsigned();
      $table->bigInteger('scenario_id')->unsigned();
      $table->foreign('geofence_id')->references('id')->on('geofences')->onDelete('cascade');
      $table->foreign('scenario_id')->references('id')->on('scenarios')->onDelete('cascade');
    });
    
    Schema::create('interactions', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->bigInteger('campaign_id')->unsigned()->nullable();
      $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('set null');
      $table->bigInteger('scenario_id')->unsigned()->nullable();
      $table->foreign('scenario_id')->references('id')->on('scenarios')->onDelete('set null');
      $table->bigInteger('geofence_id')->unsigned()->nullable();
      $table->foreign('geofence_id')->references('id')->on('geofences')->onDelete('set null');
      $table->string('geofence', 64)->nullable();
      $table->bigInteger('beacon_id')->unsigned()->nullable();
      $table->foreign('beacon_id')->references('id')->on('beacons')->onDelete('set null');
      $table->string('beacon', 64)->nullable();
      $table->string('state', 32)->nullable();
      $table->uuid('device_uuid');
      $table->decimal('lat', 17, 14)->nullable();
      $table->decimal('lng', 18, 15)->nullable();
      $table->ipAddress('ip')->nullable();
      $table->string('model', 64)->nullable();
      $table->string('platform', 16)->nullable();
      $table->json('segment')->nullable();
      $table->json('extra')->nullable();
      $table->timestamp('created_at')->nullable();
    });

    // Add lat/lng
    DB::statement('ALTER TABLE interactions ADD location POINT' );

    Schema::create('dwelling_time', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->bigInteger('campaign_id')->unsigned()->nullable();
      $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('set null');
      $table->bigInteger('geofence_id')->unsigned()->nullable();
      $table->foreign('geofence_id')->references('id')->on('geofences')->onDelete('set null');
      $table->string('geofence', 64)->nullable();
      $table->bigInteger('beacon_id')->unsigned()->nullable();
      $table->foreign('beacon_id')->references('id')->on('beacons')->onDelete('set null');
      $table->string('beacon', 64)->nullable();
      $table->uuid('device_uuid');
      $table->decimal('lat', 17, 14)->nullable();
      $table->decimal('lng', 18, 15)->nullable();
      $table->ipAddress('ip')->nullable();
      $table->boolean('start')->nullable();
      $table->boolean('end')->nullable();
      $table->integer('dwelling_time')->unsigned()->nullable();
      $table->json('segment')->nullable();
      $table->json('extra')->nullable();
      $table->timestamp('created_at')->nullable();
    });

    // Add lat/lng
    DB::statement('ALTER TABLE dwelling_time ADD location POINT' );

    Schema::create('visits', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->bigInteger('campaign_id')->unsigned()->nullable();
      $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('set null');
      $table->uuid('device_uuid');
      $table->timestamp('created_at')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('visits');
    Schema::drop('beacon_scenario');
    Schema::drop('beacons');
    Schema::drop('location_groups');
    Schema::drop('scenarios');
    Schema::drop('scenario_if');
    Schema::drop('scenario_then');
    Schema::drop('scenario_day');
    Schema::drop('scenario_time');
    Schema::drop('geofences');
    Schema::drop('geofence_scenario');
    Schema::drop('interactions');
    Schema::drop('dwelling_time');
  }
}
