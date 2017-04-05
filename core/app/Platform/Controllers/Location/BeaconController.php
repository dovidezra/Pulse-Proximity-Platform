<?php
namespace Platform\Controllers\Location;

use \Platform\Controllers\Core;
use \Platform\Models\Location;
use Illuminate\Http\Request;

class BeaconController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Beacon controller
   |--------------------------------------------------------------------------
   |
   | Beacon related logic
   |
   */

  /**
   * Show beacons
   */
  public function showBeacons()
  {
    $beacons = Location\Beacon::where('user_id', '=', Core\Secure::userId())->orderBy('name', 'asc');

    return view('platform.beacons.beacons', array(
      'beacons' => $beacons
    ));
  }

  /**
   * New beacon
   */
  public function showNewBeacon()
  {
    // Get all beacon groups / locations
    $location_groups_add = Location\LocationGroup::where('user_id', '=', Core\Secure::userId())->orderBy('name', 'asc')->select(['id', 'name'])->get()->mapWithKeys_v2(function ($item) {
      return [$item['id'] => $item['name']];
    })->toArray();

    $location_groups[''] = '&nbsp;';
    $location_groups['NEW'] = '+ ' . trans('global.add_new_group');

    if (count($location_groups_add) > 0) $location_groups[trans('global.existing')] = $location_groups_add;

    // Get all uuids
    $uuids_add = Location\BeaconUuid::where('user_id', '=', Core\Secure::userId())->orderBy('uuid', 'asc')->select('uuid')->get()->mapWithKeys_v2(function ($item) {
      return [$item['uuid'] => $item['uuid']];
    })->toArray();

    $uuids[''] = ['' => '&nbsp;'];
    $uuids['NEW'] = '+ ' . trans('global.add_new_uuid');
    
    if (count($uuids_add) > 0) $uuids[trans('global.existing')] = $uuids_add;
    
    $uuids[trans('global.vendors')] = trans('global.beacon_vendor_uuids');

    return view('platform.beacons.beacon-new', compact('location_groups', 'uuids'));
  }

  /**
   * Update beacon
   */
  public function showEditBeacon()
  {
    $sl = request()->input('sl', '');

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);
      $beacon = Location\Beacon::where('id', $qs['beacon_id'])->where('user_id', '=', Core\Secure::userId())->first();

      // Get all beacon groups / locations
      $location_groups_add = Location\LocationGroup::where('user_id', '=', Core\Secure::userId())->orderBy('name', 'asc')->select(['id', 'name'])->get()->mapWithKeys_v2(function ($item) {
        return [$item['id'] => $item['name']];
      })->toArray();

      $location_groups[''] = '&nbsp;';
      $location_groups['NEW'] = '+ ' . trans('global.add_new_group');

      if (count($location_groups_add) > 0) $location_groups[trans('global.existing')] = $location_groups_add;

      // Get all uuids
      $uuids_add = Location\BeaconUuid::where('user_id', '=', Core\Secure::userId())->orderBy('uuid', 'asc')->select('uuid')->get()->mapWithKeys_v2(function ($item) {
        return [$item['uuid'] => $item['uuid']];
      })->toArray();

      $uuids[''] = ['' => '&nbsp;'];
      $uuids['NEW'] = '+ ' . trans('global.add_new_uuid');

      if (count($uuids_add) > 0) $uuids[trans('global.existing')] = $uuids_add;

      $uuids[trans('global.vendors')] = trans('global.beacon_vendor_uuids');

      return view('platform.beacons.beacon-edit', compact('sl', 'beacon', 'location_groups', 'uuids'));
    }
  }

  /**
   * Add beacon UUID
   */
  public function postBeaconUuid()
  {
    $beacon_uuid = new Location\BeaconUuid;

    $beacon_uuid->user_id = Core\Secure::userId();
    $beacon_uuid->uuid = request()->input('inputValue', NULL);

    $beacon_uuid->save();

    return response()->json(array('id' => $beacon_uuid->uuid));
  }

  /**
   * Add / update beacon
   */
  public function postBeacon()
  {
    $sl = request()->input('sl', NULL);
    $group = request()->input('group', NULL);
    if ($group == '') $group = NULL;
    $name = request()->input('name');
    $lat = request()->input('lat', NULL);
    $lng = request()->input('lng', NULL);
    $zoom = request()->input('zoom', NULL);
    $uuid = request()->input('uuid', NULL);
    $major = request()->input('major', NULL);
    $minor = request()->input('minor', NULL);
    $active = (boolean) request()->input('active', false);

    if($sl != NULL)
    {
      $qs = Core\Secure::string2array($sl);
      $beacon = Location\Beacon::where('id', $qs['beacon_id'])->where('user_id', '=', Core\Secure::userId())->first();
    }
    else
    {
      $beacon = new Location\Beacon;
    }

    $beacon->user_id = Core\Secure::userId();
    $beacon->location_group_id = $group;
    $beacon->name = $name;
    $beacon->uuid = $uuid;
    $beacon->major = $major;
    $beacon->minor = $minor;
    $beacon->lat = $lat;
    $beacon->lng = $lng;
    $beacon->zoom = $zoom;
    $beacon->active = $active;
    $beacon->setLocationAttribute($lng . ',' . $lat);

    if($beacon->save())
    {
      $response = array(
        'redir' => '#/beacons'
      );
    }
    else
    {
      $response = array(
        'type' => 'error', 
        'msg' => $beacon->errors()->first(),
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
    $filename = config('app.name', 'Platform') . '-' . str_slug(trans('global.beacons')) . '-' . date('Y-m-d h:i:s');
    $beacons = Location\Beacon::where('beacons.user_id', Core\Secure::userId())->leftJoin('location_groups as bg', 'beacons.location_group_id', '=', 'bg.id')
      ->select(\DB::raw("
        bg.name as '" . trans('global.group') . "', 
        beacons.name as '" . trans('global.name') . "', 
        uuid as UUID,
        major as '" . trans('global.major') . "', 
        minor as '" . trans('global.minor') . "', 
        lat as '" . trans('global.latitude') . "', 
        lng as '" . trans('global.longitude') . "', 
        zoom as '" . trans('global.zoom') . "', 
        active as '" . trans('global.active') . "', 
        beacons.created_at as '" . trans('global.created') . "', 
        beacons.updated_at as '" . trans('global.updated') . "'"))->get();

    \Excel::create($filename, function($excel) use($beacons) {
      $excel->sheet(trans('global.beacons'), function($sheet) use($beacons) {
        $sheet->fromArray($beacons);
      });
    })->download($type);
  }

  /**
   * Delete beacon(s)
   */
  public function postDelete()
  {
    $sl = request()->input('sl', '');

    if(\Auth::check() && $sl != '')
    {
      $qs = Core\Secure::string2array($sl);

      $beacon = Location\Beacon::where('id', '=',  $qs['beacon_id'])->where('user_id', '=',  Core\Secure::userId())->delete();
    }
    elseif (\Auth::check())
    {
      foreach(request()->input('ids', array()) as $id)
      {
        $affected = Location\Beacon::where('id', '=', $id)->where('user_id', '=',  Core\Secure::userId())->delete();
      }
    }

    return response()->json(array('result' => 'success'));
  }

  /**
   * Switch beacon(s)
   */
  public function postSwitch()
  {
    if(\Auth::check())
    {
      foreach(request()->input('ids', array()) as $id)
      {
        $current = Location\Beacon::where('id', '=', $id)->first();
        $switch = ($current->active == 1) ? 0 : 1;
        $affected = Location\Beacon::where('id', '=', $id)->where('user_id', '=',  Core\Secure::userId())->update(array('active' => $switch));
      }
    }

    return response()->json(array('result' => 'success'));
  }

  /**
   * Get beacon list data
   */
  public function getBeaconData(Request $request)
  {
    $order_by = $request->input('order.0.column', 0);
    $order = $request->input('order.0.dir', 'asc');
    $search = $request->input('search.regex', '');
    $q = $request->input('search.value', '');
    $start = $request->input('start', 0);
    $draw = $request->input('draw', 1);
    $length = $request->input('length', 10);
    $data = array();    
    
    $aColumn = array('bg.name', 'beacons.name', 'beacons.uuid', 'beacons.major', 'beacons.minor', 'beacons.active');

    if($q != '')
    {
      $count = Location\Beacon::leftJoin('location_groups as bg', 'beacons.location_group_id', '=', 'bg.id')
        ->orderBy($aColumn[$order_by], $order)
        ->select(array('beacons.id', 'beacons.name', 'beacons.uuid', 'beacons.major', 'beacons.minor', 'beacons.lat', 'beacons.lng', 'beacons.zoom', 'beacons.active', 'bg.name as group_name'))
        ->where(function ($query) {
          $query->where('beacons.user_id', '=', Core\Secure::userId());
        })
        ->where(function ($query) use($q) {
          $query->orWhere('beacons.name', 'like', '%' . $q . '%');
          $query->orWhere('beacons.uuid', 'like', '%' . $q . '%');
          $query->orWhere('beacons.major', 'like', '%' . $q . '%');
          $query->orWhere('beacons.minor', 'like', '%' . $q . '%');
          $query->orWhere('bg.name', 'like', '%' . $q . '%');
        })
        ->count();

      $oData = Location\Beacon::leftJoin('location_groups as bg', 'beacons.location_group_id', '=', 'bg.id')
        ->orderBy($aColumn[$order_by], $order)
        ->select(array('beacons.id', 'beacons.name', 'beacons.uuid', 'beacons.major', 'beacons.minor', 'beacons.lat', 'beacons.lng', 'beacons.zoom', 'beacons.active', 'bg.name as group_name'))
        ->where(function ($query) {
          $query->where('beacons.user_id', '=', Core\Secure::userId());
        })
        ->where(function ($query) use($q) {
          $query->orWhere('beacons.name', 'like', '%' . $q . '%');
          $query->orWhere('beacons.uuid', 'like', '%' . $q . '%');
          $query->orWhere('beacons.major', 'like', '%' . $q . '%');
          $query->orWhere('beacons.minor', 'like', '%' . $q . '%');
          $query->orWhere('bg.name', 'like', '%' . $q . '%');
        })
        ->take($length)->skip($start)->get();
    }
    else
    {
      $count = Location\Beacon::where('beacons.user_id', '=', Core\Secure::userId())->count();

      $oData = Location\Beacon::where('beacons.user_id', '=', Core\Secure::userId())
        ->leftJoin('location_groups as bg', 'beacons.location_group_id', '=', 'bg.id')
        ->select(array('beacons.id', 'beacons.name', 'beacons.uuid', 'beacons.major', 'beacons.minor', 'beacons.lat', 'beacons.lng', 'beacons.zoom', 'beacons.active', 'bg.name as group_name'))
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
        'uuid' => $row->uuid,
        'major' => ($row->major === NULL) ? '-' : $row->major,
        'minor' => ($row->minor === NULL) ? '-' : $row->minor,
        'lng' => $row->lng,
        'lat' => $row->lat,
        'zoom' => $row->zoom,
        'active' => $row->active,
        'sl' => Core\Secure::array2string(array('beacon_id' => $row->id))
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