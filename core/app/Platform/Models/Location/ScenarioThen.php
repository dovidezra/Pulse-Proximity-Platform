<?php
namespace Platform\Models\Location;

use Illuminate\Database\Eloquent\Model;

Class ScenarioThen extends Model {

  protected $table = 'scenario_then';

  // Disabling Auto Timestamps
  public $timestamps = false;

  public function scenarios() {
    return $this->belongsToMany('Platform\Models\Location\Scenario', 'scenarios');
  }
}