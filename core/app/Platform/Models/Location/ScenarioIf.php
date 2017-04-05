<?php
namespace Platform\Models\Location;

use Illuminate\Database\Eloquent\Model;

Class ScenarioIf extends Model {

  protected $table = 'scenario_if';

  // Disabling Auto Timestamps
  public $timestamps = false;

  public function scenarios() {
    return $this->belongsToMany('Platform\Models\Location\Scenario', 'scenarios');
  }
}