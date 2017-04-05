<?php
namespace Platform\Models\Location;

use Illuminate\Database\Eloquent\Model;

Class Visit extends Model {

  protected $table='visits';

  public function setUpdatedAtAttribute($value) {
    // Do nothing.
  }

  public function getUpdatedAtColumn() {
    return null;
  }

  public function user() {
    return $this->belongsTo('App\User');
  }

  public function campaign() {
    return $this->belongsTo('Platform\Models\Campaigns\Campaign');
  }
}