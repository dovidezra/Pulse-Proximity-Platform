<?php namespace Platform\Controllers\Location;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use \Platform\Controllers\Core;
use \Platform\Models\Location;
use \Platform\Models\Campaigns;
use Illuminate\Http\Request;

class ApiController extends \App\Http\Controllers\Controller
{
  /*
  |--------------------------------------------------------------------------
  | Api Controller
  |--------------------------------------------------------------------------
  |
  | Api related logic
  |--------------------------------------------------------------------------
  */
  /**
   * Get scenario interaction from app
   */

  public function postScenario() {
    // Add interaction
    $token = request()->input('token', NULL);
    $type = request()->input('type', NULL);
    $device_uuid = request()->input('uuid', NULL);
    $scenario_id = request()->input('scenario_id', NULL);
    $lat = request()->input('lat', NULL);
    $lng = request()->input('lng', NULL);
    $model = request()->input('model', NULL);
    $platform = request()->input('platform', NULL);

    if ($device_uuid != NULL && $type != NULL && $scenario_id != NULL) {
      $scenario = Location\Scenario::where('id', '=', $scenario_id)->first();
      
      $interaction = new Location\Interaction;

      $interaction->user_id = $scenario->campaign->user_id;
      $interaction->campaign_id = $scenario->campaign_id;
      $interaction->device_uuid = $device_uuid;
      $interaction->model = $model;
      $interaction->platform = $platform;
      $interaction->ip = request()->ip();
      $interaction->lat = $lat;
      $interaction->lng = $lng;
      $interaction->setLocationAttribute($lng . ',' . $lat);
      $interaction->scenario_id = $scenario_id;
      $interaction->state = request()->input('state', NULL);

      $type_id = request()->input('type_id', NULL);

      if ($type == 'beacon') {
        $beacon = Location\Beacon::where('id', '=', $type_id)->first();
        $name = (! empty($beacon)) ? $beacon->name : NULL;

        $interaction->beacon_id = $type_id;
        $interaction->beacon = $name;
      } elseif ($type == 'geofence') {
          $geofence = Location\Geofence::where('id', '=', $type_id)->first();
        $name = (! empty($geofence)) ? $geofence->name : NULL;

        $interaction->geofence_id = $type_id;
        $interaction->geofence = $name;
      }

      $interaction->save();

      return \Response::json([1]);
    }
  }
  
  /**
   * API response for app
   */
  public static

  function getApp()
  {
    $token = request()->input('token', NULL);
    $timezone = request()->input('tz', 'UTC');
    $lat = request()->input('lat', NULL);
    $lng = request()->input('lng', NULL);
    $accuracy = request()->input('acc', 0);
    if ($accuracy > 1000) $accuracy = 1000;
    $distance_beacons = 100 + $accuracy;
    $distance_geofences = 50000 + $accuracy;

    //\DB::enableQueryLog();
    //dd(\DB::getQueryLog()); 

    $apps = Campaigns\App::where('api_token', $token)->where('api_token', '<>', '')->whereNotNull('api_token')->get();

    if (empty($apps)) {
      return response()->json(['error' => 'Token not recognized']);
    }

    $found_geofences = [];
    $found_beacons = [];
    $available_geofences = [];
    $available_beacons = [];
    $available_scenarios = [];
    $campaign_info = NULL;
    $count_beacon = 0;
    $count_geofence = 0;

    foreach ($apps as $app) {
      $campaigns = \DB::select('SELECT ac.campaign_id FROM app_campaigns ac LEFT JOIN campaigns c ON c.id = ac.campaign_id WHERE c.active = 1 AND ac.app_id = ' . $app->id . '');

      foreach($campaigns as $campaign) {
        $campaign = Campaigns\Campaign::where('id', $campaign->campaign_id)->first();
        $scenarios = $campaign->scenarios()->whereNotNull('scenario_then_id')->get();

        foreach($scenarios as $scenario) {
          $scenario_beacons = [];
          $beacons = $scenario
            ->beacons()
            ->distance($distance_beacons, $lat . ',' . $lng)
            ->orderBy('distance', 'asc')
            ->take(20)
            ->skip(0)
            ->get();

          foreach($beacons as $beacon) {
            if ($beacon->active == 1 && !in_array($beacon->beacon_id, $scenario_beacons)) {
              array_push($scenario_beacons, $beacon->beacon_id);
            }

            if ($beacon->active == 1 && !in_array($beacon->beacon_id, $found_beacons)) {
              $available_beacons[$count_beacon] = array(
                'id' => $beacon->beacon_id,
                'identifier' => $beacon->name,
                'uuid' => $beacon->uuid,
                'major' => $beacon->major,
                'minor' => $beacon->minor,
                'lat' => $beacon->lat,
                'lng' => $beacon->lng,
              );
              array_push($found_beacons, $beacon->beacon_id);
              $count_beacon++;
            }
          }

          $scenario_geofences = [];
          $geofences = $scenario
            ->geofences()
            ->distance($distance_geofences, $lat . ',' . $lng)
            ->orderBy('distance', 'asc')
            ->take(100)
            ->skip(0)
            ->get();

          foreach($geofences as $geofence) {
            if ($geofence->active == 1 && !in_array($geofence->geofence_id, $scenario_geofences)) {
              array_push($scenario_geofences, $geofence->geofence_id);
            }

            if ($geofence->active == 1 && !in_array($geofence->geofence_id, $found_geofences)) {
              $available_geofences[$count_geofence] = array(
                'id' => $geofence->geofence_id,
                'identifier' => $geofence->name,
                'lat' => $geofence->lat,
                'lng' => $geofence->lng,
                'radius' => $geofence->radius
              );
              array_push($found_geofences, $geofence->geofence_id);
              $count_geofence++;
            }
          }

          // Check if scenario has (valid) output
          $scenario_has_output = true;

          switch ($scenario->scenario_then_id) {

            // show_image

          case 2:
            if ($scenario->show_image == '') $scenario_has_output = false;
            break;

            // show_template

          case 3:
            if ($scenario->template == NULL) $scenario_has_output = false;
            break;

            // open_url

          case 4:
            if ($scenario->open_url == '') $scenario_has_output = false;
            break;

            // reward_points

          case 10:
            if ($scenario->add_points == '') $scenario_has_output = false;
            break;

            // withdraw_points

          case 11:
            if ($scenario->substract_points == '') $scenario_has_output = false;
            break;

          }

          if ($scenario_has_output && $scenario->active == 1 && $scenario->scenario_then_id != NULL && (! empty($scenario_beacons) || ! empty($scenario_geofences))) {

            // Set scenario_then_id because some scenarios merge

            $scenario_then_id = $scenario->scenario_then_id;
            $open_url = $scenario->open_url;

            $template = ($scenario->template != NULL) ? url('/api/v1/remote/template/' . AppCoreSecure::array2string(array(
              'scenario_id' => $scenario->id
            ))) : NULL;

            $show_image = ($scenario->show_image != NULL) ? url('/api/v1/remote/image/' . AppCoreSecure::array2string(array(
              'scenario_id' => $scenario->id
            ))) : NULL;

            // Translate date and time to general timezone
            if ($campaign->timezone != $timezone) {
              if ($scenario->date_start != null) {
                $date = \Carbon\Carbon::createFromFormat('Y-m-d', $scenario->date_start, $campaign->timezone);
                $scenario->date_start = $date->setTimezone('UTC')->format('Y-m-d');
              }

              if ($scenario->date_end != null) {
                $date = \Carbon\Carbon::createFromFormat('Y-m-d', $scenario->date_end, $campaign->timezone);
                $scenario->date_end = $date->setTimezone('UTC')->format('Y-m-d');
              }

              if ($scenario->time_start != null) {
                $date = \Carbon\Carbon::createFromFormat('H:i:s', $scenario->time_start, $campaign->timezone);
                $scenario->time_start = $date->setTimezone('UTC')->format('H:i:s');
              }

              if ($scenario->time_end != null) {
                $date = \Carbon\Carbon::createFromFormat('H:i:s', $scenario->time_end, $campaign->timezone);
                $scenario->time_end = $date->setTimezone('UTC')->format('H:i:s');
              }
            }

            $available_scenarios[] = array(
              'id' => $scenario->id,
              'scenario_if_id' => $scenario->scenario_if_id,
              'scenario_then_id' => $scenario_then_id,
              'scenario_day_id' => $scenario->scenario_day_id,
              'scenario_time_id' => $scenario->scenario_time_id,
              'time_start' => $scenario->time_start,
              'time_end' => $scenario->time_end,
              'date_start' => $scenario->date_start,
              'date_end' => $scenario->date_end,
              'frequency' => $scenario->frequency,
              'delay' => $scenario->delay,
              'notification' => str_replace('%', '%%', $scenario->notification),
              'show_image' => $show_image,
              'template' => $template,
              'open_url' => $open_url,
              'settings' => $scenario->settings,
              'geofences' => $scenario_geofences,
              'beacons' => $scenario_beacons
            );
          }
        }
      }
    }

    $response = array(
      'meta' => [
        'timezone' => $timezone
      ],
      'geofences' => $available_geofences,
      'beacons' => $available_beacons,
      'scenarios' => $available_scenarios
    );

    return $response;
  }
}