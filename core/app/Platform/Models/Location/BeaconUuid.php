<?php
namespace Platform\Models\Location;

use Illuminate\Database\Eloquent\Model;


Class BeaconUuid extends Model {
  protected $table = 'beacon_uuids';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

  public function user() {
    return $this->belongsTo('App\User');
  }
}