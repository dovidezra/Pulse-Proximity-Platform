<?php
namespace Platform\Models\Location;

use Illuminate\Database\Eloquent\Model;

Class Interaction extends Model {

  protected $table='interactions';
  protected $geofields = array('location');

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
    return $query->whereRaw('st_distance(location, POINT('.$location.')) < '.$dist);
  }
}