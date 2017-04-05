<?php
namespace Platform\Models\Location;

use Illuminate\Database\Eloquent\Model;

Class ScenarioTime extends Model {

  protected $table = 'scenario_time';

  // Disabling Auto Timestamps
  public $timestamps = false;

  public function scenarios() {
    return $this->belongsToMany('Platform\Models\Location\Scenario', 'scenarios');
  }
}