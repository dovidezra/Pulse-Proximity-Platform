<?php
namespace Platform\Models\Location;

use Illuminate\Database\Eloquent\Model;

Class DwellingTime extends Model {

  protected $table='dwelling_time';

  protected $casts = [
    'segment' => 'json',
    'extra' => 'json'
  ];

  public function setUpdatedAtAttribute($value) {
    // Do nothing.
  }

  public function getUpdatedAtColumn() {
    return null;
  }

  public function user() {
    return $this->belongsTo('App\User');
  }
}