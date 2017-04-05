<?php
namespace Platform\Models\Location;

use Illuminate\Database\Eloquent\Model;

Class Scenario extends Model {

  protected $table = 'scenarios';

  protected $casts = [
    'settings' => 'json'
  ];

  public function campaign() {
    return $this->belongsTo('Platform\Models\Campaigns\Campaign');
  }

  public function geofences() {
    return $this->belongsToMany('Platform\Models\Location\Geofence', 'geofence_scenario', 'scenario_id', 'geofence_id');
  }

  public function beacons() {
    return $this->belongsToMany('Platform\Models\Location\Beacon', 'beacon_scenario', 'scenario_id', 'beacon_id');
  }

  public function scenarioIf() {
    return $this->hasOne('Platform\Models\Location\ScenarioIf');
  }

  public function scenarioThen() {
    return $this->hasOne('Platform\Models\Location\ScenarioThen');
  }

  public function scenarioDay() {
    return $this->hasOne('Platform\Models\Location\ScenarioDay');
  }

  public function scenarioTime() {
    return $this->hasOne('Platform\Models\Location\ScenarioTime');
  }
}