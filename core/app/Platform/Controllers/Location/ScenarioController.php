<?php namespace Platform\Controllers\Location;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use \Platform\Controllers\Core;
use \Platform\Models\Location;
use \Platform\Models\Campaigns;
use Illuminate\Http\Request;

class ScenarioController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Scenario Board Controller
   |--------------------------------------------------------------------------
   |
   | Scenario Board related logic
   |--------------------------------------------------------------------------
   */

  /**
   * Coupons editor
   */

  public function showEditScenarios() {
    $sl = request()->input('sl', '');

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);

      // Create a JWT token for API calls
      $jwt_token = JWTAuth::fromUser(auth()->user()); //auth()->user()->createToken('api')->accessToken;

      $campaign = Campaigns\Campaign::where('id', $qs['campaign_id'])->where('user_id', '=', Core\Secure::userId())->first();

      // Get all beacons and groups / locations
      $geofences = Location\Geofence::where('user_id', '=', Core\Secure::userId())->where('active', '=', 1)->where('location_group_id', NULL)->orderBy('name', 'asc')->get();
      $beacons = Location\Beacon::where('user_id', '=', Core\Secure::userId())->where('active', '=', 1)->where('location_group_id', NULL)->orderBy('name', 'asc')->get();
      $location_groups = Location\LocationGroup::where('user_id', '=', Core\Secure::userId())->orderBy('name', 'asc')->get();

      // Get scenario statements
      $scenario_if = Location\ScenarioIf::all();
      $scenario_then = Location\ScenarioThen::where('active', 1)->orderBy('sort', 'asc')->get();
      $scenario_day = Location\ScenarioDay::all();
      $scenario_time = Location\ScenarioTime::all();      
      
      if (! empty($campaign)) {
        return view('platform.scenarios.scenarios-edit', compact('jwt_token', 'campaign', 'sl', 'geofences', 'beacons', 'location_groups', 'scenario_if', 'scenario_then', 'scenario_day', 'scenario_time'));
      }
    }
  }

  /**
   * Save new scenario
   */
  public function postScenario() {
    $sl = request()->input('sl', '');
    $qs = Core\Secure::string2array($sl);
    $campaign = Campaigns\Campaign::where('id', $qs['campaign_id'])->where('user_id', '=', Core\Secure::userId())->first();

    if(! empty($campaign)) {
      $scenario = new Location\Scenario;
      $scenario->campaign_id = $campaign->id;
    }

    if($scenario->save()) {
      $sl = Core\Secure::array2string(array('campaign_id' => $campaign->id, 'scenario_id' => $scenario->id));
      $response = [
        'result' => 'success', 
        'sl' => $sl
      ];
    } else {
      $response = [
        'result' => 'error', 
        'result_msg' => $scenario->errors()->first()
      ];
    }

    return response()->json($response);
  }

  /**
   * Update scenario
   */
  public function postUpdateScenario() {
    $name = request()->input('name', '');
    $value = request()->input('value', '');
    if($value == '') $value = NULL;
    $sl = request()->input('sl', '');

    $qs = Core\Secure::string2array($sl);
    $campaign = Campaigns\Campaign::where('id', $qs['campaign_id'])->where('user_id', '=', Core\Secure::userId())->first();
    $scenario = $campaign->scenarios()->where('id', $qs['scenario_id'])->first();

    if(! empty($scenario)) {
      if($name == 'scenario-if') {
        $scenario->scenario_if_id = $value;
      } elseif($name == 'scenario-then') {
        $scenario->scenario_then_id = $value;
      } elseif($name == 'scenario-when-date') {
        $scenario->scenario_day_id = $value;
      } elseif($name == 'scenario-when-time') {
        $scenario->scenario_time_id = $value;
      } elseif($name == 'datepicker-range') {
        $date_start = request()->input('date_start', '');
        $date_end = request()->input('date_end', '');

        if($date_start == '') $date_start = NULL;
        $scenario->date_start = $date_start;

        if($date_end == '') $date_end = NULL;
        $scenario->date_end = $date_end;
      } elseif($name == 'time-range') {
        $time_start = request()->input('time_start', '');
        $time_end = request()->input('time_end', '');

        if($time_start == '') $time_start = NULL;
        $scenario->time_start = $time_start;

        if($time_end == '') $time_end = NULL;
        $scenario->time_end = $time_end;
      } elseif($name == 'notification') {
        $scenario->notification = $value;
      } elseif($name == 'open_url') {
        $scenario->open_url = $value;
      } elseif($name == 'template') {
        $scenario->template = $value;
      } elseif($name == 'show_image') {
        $scenario->show_image = $value;
      } elseif($name == 'config') {
        $frequency = request()->input('frequency', '');
        $scenario->frequency = $frequency;

        $delay = request()->input('delay', '');
        $scenario->delay = $delay;
      }

      $scenario->save();
    }

    return response()->json(['result' => 'success']);
  }

  /**
   * Update scenario beacons
   */
  public function postUpdateScenarioPlaces() {
    $places = request()->input('places', '');
    $sl = request()->input('sl', '');
    $qs = Core\Secure::string2array($sl);
    $campaign = Campaigns\Campaign::where('id', $qs['campaign_id'])->where('user_id', '=', Core\Secure::userId())->first();
    $scenario = $campaign->scenarios()->where('id', $qs['scenario_id'])->first();

    if(! empty($scenario)) {
      $geofences = array();
      $beacons = array();

      if ($places != '') {
        foreach($places as $place) {
          if (starts_with($place, 'geofence')) {
            $id = str_replace('geofence', '', $place);
            array_push($geofences, $id); 
          }
  
          if (starts_with($place, 'beacon')) {
            $id = str_replace('beacon', '', $place);
            array_push($beacons, $id); 
          }
        }
      }

      $scenario->geofences()->sync($geofences);
      $scenario->beacons()->sync($beacons);
    }

    return response()->json(['result' => 'success']);
  }

  /**
   * Delete scenario
   */
  public function postDeleteScenario() {
    $sl = request()->input('sl', '');
    $qs = Core\Secure::string2array($sl);
    $campaign = Campaigns\Campaign::where('id', $qs['campaign_id'])->where('user_id', '=', Core\Secure::userId())->first();

    if(! empty($campaign)) {
      $scenario = $campaign->scenarios()->where('id', $qs['scenario_id'])->first();
      if(! empty($scenario)) $scenario->forceDelete();
    }

    return response()->json(['result' => 'success']);
  }
}