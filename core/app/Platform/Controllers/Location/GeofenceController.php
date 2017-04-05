<?php
namespace Platform\Controllers\Location;

use \Platform\Controllers\Core;
use \Platform\Models\Location;
use Illuminate\Http\Request;

class GeofenceController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Geofence controller
   |--------------------------------------------------------------------------
   |
   | Geofence related logic
   |
   */

  /**
   * Show geofences
   */
  public function showGeofences()
  {
    $geofences = Location\Geofence::where('user_id', '=', Core\Secure::userId())->orderBy('name', 'asc');

    return view('platform.geofences.geofences', array(
      'geofences' => $geofences
    ));
  }

  /**
   * New geofence
   */
  public function showNewGeofence()
  {
    // Get all geofence groups / locations
    $location_groups_add = Location\LocationGroup::where('user_id', '=', Core\Secure::userId())->orderBy('name', 'asc')->select(['id', 'name'])->get()->mapWithKeys_v2(function ($item) {
      return [$item['id'] => $item['name']];
    })->toArray();

    $location_groups[''] = '&nbsp;';
    $location_groups['NEW'] = '+ ' . trans('global.add_new_group');

    if (count($location_groups_add) > 0) $location_groups[trans('global.existing')] = $location_groups_add;

    return view('platform.geofences.geofence-new', compact('location_groups'));
  }

  /**
   * Update geofence
   */
  public function showEditGeofence()
  {
    $sl = request()->input('sl', '');

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);
      $geofence = Location\Geofence::where('id', $qs['geofence_id'])->where('user_id', '=', Core\Secure::userId())->first();

      // Get all geofence groups / locations
      $location_groups_add = Location\LocationGroup::where('user_id', '=', Core\Secure::userId())->orderBy('name', 'asc')->select(['id', 'name'])->get()->mapWithKeys_v2(function ($item) {
        return [$item['id'] => $item['name']];
      })->toArray();

      $location_groups[''] = '&nbsp;';
      $location_groups['NEW'] = '+ ' . trans('global.add_new_group');

      if (count($location_groups_add) > 0) $location_groups[trans('global.existing')] = $location_groups_add;

      return view('platform.geofences.geofence-edit', compact('sl', 'geofence', 'location_groups'));
    }
  }

  /**
   * Add / update geofence
   */
  public function postGeofence()
  {
    $sl = request()->input('sl', NULL);
    $group = request()->input('group', NULL);
    if ($group == '') $group = NULL;
    $name = request()->input('name');
    $radius = request()->input('radius', NULL);
    $lat = request()->input('lat', NULL);
    $lng = request()->input('lng', NULL);
    $zoom = request()->input('zoom', NULL);
    $active = (boolean) request()->input('active', false);

    if($sl != NULL)
    {
      $qs = Core\Secure::string2array($sl);
      $geofence = Location\Geofence::where('id', $qs['geofence_id'])->where('user_id', '=', Core\Secure::userId())->first();
    }
    else
    {
      $geofence = new Location\Geofence;
    }

    $geofence->user_id = Core\Secure::userId();
    $geofence->location_group_id = $group;
    $geofence->name = $name;
    $geofence->radius = $radius;
    $geofence->lat = $lat;
    $geofence->lng = $lng;
    $geofence->zoom = $zoom;
    $geofence->active = $active;
    $geofence->setLocationAttribute($lng . ',' . $lat);

    if($geofence->save())
    {
      $response = array(
        'redir' => '#/geofences'
      );
    }
    else
    {
      $response = array(
        'type' => 'error', 
        'msg' => $geofence->errors()->first(),
        'reset' => false
      );
    }

    return response()->json($response);
  }

  /**
   * Export
   */

  public function getExport()
  {
    $type = request()->input('type', 'xls');
    if (! in_array($type, ['xls', 'xlsx', 'csv'])) $type = 'xls';
    $filename = config('app.name', 'Platform') . '-' . str_slug(trans('global.geofences')) . '-' . date('Y-m-d h:i:s');
    $geofences = Location\Geofence::where('geofences.user_id', Core\Secure::userId())->leftJoin('location_groups as bg', 'geofences.location_group_id', '=', 'bg.id')
      ->select(\DB::raw("
        bg.name as '" . trans('global.group') . "', 
        geofences.name as '" . trans('global.name') . "', 
        lat as '" . trans('global.latitude') . "', 
        lng as '" . trans('global.longitude') . "', 
        radius as '" . trans('global.radius') . "', 
        zoom as '" . trans('global.zoom') . "', 
        active as '" . trans('global.active') . "', 
        geofences.created_at as '" . trans('global.created') . "', 
        geofences.updated_at as '" . trans('global.updated') . "'"))->get();

    \Excel::create($filename, function($excel) use($geofences) {
      $excel->sheet(trans('global.geofences'), function($sheet) use($geofences) {
        $sheet->fromArray($geofences);
      });
    })->download($type);
  }

  /**
   * Delete geofence(s)
   */
  public function postDelete()
  {
    $sl = request()->input('sl', '');

    if(\Auth::check() && $sl != '')
    {
      $qs = Core\Secure::string2array($sl);

      $geofence = Location\Geofence::where('id', '=',  $qs['geofence_id'])->where('user_id', '=',  Core\Secure::userId())->delete();
    }
    elseif (\Auth::check())
    {
      foreach(request()->input('ids', array()) as $id)
      {
        $affected = Location\Geofence::where('id', '=', $id)->where('user_id', '=',  Core\Secure::userId())->delete();
      }
    }

    return response()->json(array('result' => 'success'));
  }

  /**
   * Switch geofence(s)
   */
  public function postSwitch()
  {
    if(\Auth::check())
    {
      foreach(request()->input('ids', array()) as $id)
      {
        $current = Location\Geofence::where('id', '=', $id)->first();
        $switch = ($current->active == 1) ? 0 : 1;
        $affected = Location\Geofence::where('id', '=', $id)->where('user_id', '=',  Core\Secure::userId())->update(array('active' => $switch));
      }
    }

    return response()->json(array('result' => 'success'));
  }

  /**
   * Get geofence list data
   */
  public function getGeofenceData(Request $request)
  {
    $order_by = $request->input('order.0.column', 0);
    $order = $request->input('order.0.dir', 'asc');
    $search = $request->input('search.regex', '');
    $q = $request->input('search.value', '');
    $start = $request->input('start', 0);
    $draw = $request->input('draw', 1);
    $length = $request->input('length', 10);
    $data = array();    
    
    $aColumn = array('bg.name', 'geofences.name', 'geofences.lng', 'geofences.active');

    if($q != '')
    {
      $count = Location\Geofence::leftJoin('location_groups as bg', 'geofences.location_group_id', '=', 'bg.id')
        ->orderBy($aColumn[$order_by], $order)
        ->select(array('geofences.id', 'geofences.name', 'geofences.radius', 'geofences.lng', 'geofences.lat', 'geofences.active', 'bg.name as group_name'))
        ->where(function ($query) {
          $query->where('geofences.user_id', '=', Core\Secure::userId());
        })
        ->where(function ($query) use($q) {
          $query->orWhere('geofences.name', 'like', '%' . $q . '%');
          $query->orWhere('geofences.radius', 'like', '%' . $q . '%');
          $query->orWhere('geofences.lng', 'like', '%' . $q . '%');
          $query->orWhere('geofences.lat', 'like', '%' . $q . '%');
          $query->orWhere('bg.name', 'like', '%' . $q . '%');
        })
        ->count();

      $oData = Location\Geofence::leftJoin('location_groups as bg', 'geofences.location_group_id', '=', 'bg.id')
        ->orderBy($aColumn[$order_by], $order)
        ->select(array('geofences.id', 'geofences.name', 'geofences.radius', 'geofences.lng', 'geofences.lat', 'geofences.active', 'bg.name as group_name'))
        ->where(function ($query) {
          $query->where('geofences.user_id', '=', Core\Secure::userId());
        })
        ->where(function ($query) use($q) {
          $query->orWhere('geofences.name', 'like', '%' . $q . '%');
          $query->orWhere('geofences.radius', 'like', '%' . $q . '%');
          $query->orWhere('geofences.lng', 'like', '%' . $q . '%');
          $query->orWhere('geofences.lat', 'like', '%' . $q . '%');
          $query->orWhere('bg.name', 'like', '%' . $q . '%');
        })
        ->take($length)->skip($start)->get();
    }
    else
    {
      $count = Location\Geofence::where('geofences.user_id', '=', Core\Secure::userId())->count();

      $oData = Location\Geofence::where('geofences.user_id', '=', Core\Secure::userId())
        ->leftJoin('location_groups as bg', 'geofences.location_group_id', '=', 'bg.id')
        ->select(array('geofences.id', 'geofences.name', 'geofences.radius', 'geofences.lng', 'geofences.lat', 'geofences.active', 'bg.name as group_name'))
        ->orderBy($aColumn[$order_by], $order)
        ->take($length)
        ->skip($start)
        ->get();
    }

    if($length == -1) $length = $count;

    $recordsTotal = $count;
    $recordsFiltered = $count;

    foreach($oData as $row)
    {
      $data[] = array(
        'DT_RowId' => 'row_' . $row->id,
        'location_group_id' => $row->group_name,
        'name' => $row->name,
        'lng' => $row->lng,
        'lat' => $row->lat,
        'radius' => $row->radius,
        'active' => $row->active,
        'sl' => Core\Secure::array2string(array('geofence_id' => $row->id))
        /*,
        'created_at' => $row->created_at->timezone(Auth::user()->timezone)->format(trans('global.dateformat_full'))*/
      );
    }

    $response = array(
      'draw' => $draw,
      'recordsTotal' => $recordsTotal,
      'recordsFiltered' => $recordsFiltered,
      'data' => $data
    );

    echo json_encode($response);
  }
}