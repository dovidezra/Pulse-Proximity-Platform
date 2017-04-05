<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'plans';

  protected $casts = [
    'limitations' => 'json',
    'extra' => 'json'
  ];

  public function getLimitationsAttribute($key) {
    $key = json_decode($key, true);

    if ($this->id == 1) {
      $key['account']['plan_visible'] = 1;

      $key['mobile']['visible'] = 1;
      $key['mobile']['apps'] = 100;
      $key['mobile']['campaigns'] = 100;
      $key['mobile']['scenarios_per_campaign'] = 100;
      $key['mobile']['cards'] = 100;
      $key['mobile']['cards_visible'] = 1;
      $key['mobile']['beacons'] = 100;
      $key['mobile']['beacons_visible'] = 1;
      $key['mobile']['geofences'] = 100;
      $key['mobile']['geofences_visible'] = 1;

      $key['online']['visible'] = 1;
      $key['online']['members_visible'] = 1;

      $key['media']['visible'] = 1;

    } else {
      // Default values
      if (! isset($key['account']['plan_visible'])) $key['account']['plan_visible'] = 1;

      if (! isset($key['mobile']['visible'])) $key['mobile']['visible'] = 0;
      if (! isset($key['mobile']['apps'])) $key['mobile']['apps'] = 0;
      if (! isset($key['mobile']['campaigns'])) $key['mobile']['campaigns'] = 0;
      if (! isset($key['mobile']['scenarios_per_campaign'])) $key['mobile']['scenarios_per_campaign'] = 0;
      if (! isset($key['mobile']['cards'])) $key['mobile']['cards'] = 0;
      if (! isset($key['mobile']['cards_visible'])) $key['mobile']['cards_visible'] = 0;
      if (! isset($key['mobile']['beacons'])) $key['mobile']['beacons'] = 0;
      if (! isset($key['mobile']['beacons_visible'])) $key['mobile']['beacons_visible'] = 0;
      if (! isset($key['mobile']['geofences'])) $key['mobile']['geofences'] = 0;
      if (! isset($key['mobile']['geofences_visible'])) $key['mobile']['geofences_visible'] = 0;

      if (! isset($key['online']['visible'])) $key['online']['visible'] = 0;
      if (! isset($key['online']['members_visible'])) $key['online']['members_visible'] = 0;

      if (! isset($key['media']['visible'])) $key['media']['visible'] = 0;
    }

    return $key;
  }
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [];

  /**
   * The attributes excluded from the model's JSON form.
   *
   * @var array
   */
  protected $hidden = [];

  public function getDates() {
    return array('created_at', 'updated_at');
  }

  public function reseller() {
    return $this->belongsTo('\App\Reseller');
  }

  public function users() {
    return $this->belongsToMany('\App\User');
  }
}
