<?php
namespace Platform\Models\Location;

use Illuminate\Database\Eloquent\Model;

Class LocationInteraction extends Model {

  protected $table = 'location_interactions';

	public function setUpdatedAtAttribute($value) {
		// Do nothing.
	}

  public function user() {
    return $this->belongsTo('App\User');
  }

  public function scenario() {
    return $this->hasOne('Platform\Models\Location\Scenario');
  }

  public function geofence() {
    return $this->hasOne('Platform\Models\Location\Geofence');
  }

  public function beacon() {
    return $this->hasOne('Platform\Models\Location\Beacon');
  }
}