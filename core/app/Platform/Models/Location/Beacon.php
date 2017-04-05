<?php
namespace Platform\Models\Location;

use Illuminate\Database\Eloquent\Model;

use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;

Class Beacon extends Model implements StaplerableInterface {
  use EloquentTrait;

  protected $table = 'beacons';
  protected $geofields = array('location');

  protected $casts = [
    'settings' => 'json'
  ];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = true;

  public function __construct(array $attributes = array()) {
    $this->hasAttachedFile('photo', [
      'styles' => [
        'large' => '800x800',
        'small' => '128x128#'
      ]
    ]);

    parent::__construct($attributes);
  }

  public function setLocationAttribute($value) {
    $this->attributes['location'] = \DB::raw("POINT($value)");
  }

  public function getLocationAttribute($value) {
    $loc =  substr($value, 6);
    $loc = preg_replace('/[ ,]+/', ',', $loc, 1);

    return substr($loc,0,-1);
  }

  public function newQuery($excludeDeleted = true) {
    $raw='';
    foreach($this->geofields as $column){
      $raw .= ' astext('.$column.') as '.$column.' ';
    }
    return parent::newQuery($excludeDeleted)->addSelect('*', \DB::raw($raw));
  }

  public function scopeDistance($query, $dist, $location) {
    // Miles
    //$unit = 3959;
    // Kilometers (* 1000 = meters)
    $unit = 6371 * 1000;

    $coords = explode(',', $location);
    $lat = $coords[0];
    $lng = $coords[1];
    return $query->selectRaw("ROUND( " . $unit . " * acos( cos( radians(" . $lat . ") ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(" . $lng . ") ) + sin( radians(" . $lat . ") ) * sin(radians(lat)) ), 0) AS distance")->havingRaw('distance < '.$dist);
  }

  public function user() {
    return $this->belongsTo('App\User');
  }

  public function locationGroup() {
    return $this->belongsTo('Platform\Models\Location\LocationGroup', 'location_group_id');
  }

  public function scenarios() {
    return $this->belongsToMany('Platform\Models\Location\Scenario', 'beacon_scenario', 'beacon_id', 'scenario_id');
  }

  public function cards() {
    return $this->belongsToMany('Platform\Models\Location\Card', 'beacon_card', 'card_id', 'beacon_id');
  }
}