<?php namespace Platform\Controllers\Analytics;

use \Platform\Controllers\Core;
use \Platform\Controllers\Analytics;
use \Platform\Models\Analytics as ModelAnalytics;
use \Platform\Models\Campaigns;
use Illuminate\Http\Request;

class CampaignAnalyticsController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Campaign Analytics Controller
   |--------------------------------------------------------------------------
   |
   | Campaign Analytics related logic
   |--------------------------------------------------------------------------
   */

  /**
   * Campaign Analytics
   */
  public function showAnalytics()
  {
    // Security link
    $sl = request()->get('sl', '');
    $sql_campaign = '1=1';
    $campaign_id = '';

    if ($sl != '') {
      $qs = Core\Secure::string2array($sl);
      $campaign_id = $qs['campaign_id'];
      $sql_campaign = 'campaign_id = ' . $campaign_id;
      $sl = rawurlencode($sl);
    }

    // Range
    $date_start = request()->get('start', date('Y-m-d', strtotime(' - 30 day')));
    $date_end = request()->get('end', date('Y-m-d'));

    $from =  $date_start . ' 00:00:00';
    $to = $date_end . ' 23:59:59';

    /*
     |--------------------------------------------------------------------------
     | Campaigns
     |--------------------------------------------------------------------------
     */
    $campaigns = Campaigns\Campaign::where('user_id', Core\Secure::userId())
      ->where('active', 1)
      ->orderBy('created_at', 'asc')
      ->get();

    /*
     |--------------------------------------------------------------------------
     | First date
     |--------------------------------------------------------------------------
     */
    $stats_found = false;
    $first_date = date('Y-m-d');
/*
    $coupon_stats = ModelAnalytics\CouponStat::where('user_id', Core\Secure::userId())
      ->select(\DB::raw('DATE(created_at) as date'))
      ->whereRaw($sql_coupon)
      ->orderBy('date', 'asc')
      ->first();

    if (! empty($coupon_stats)) {
      $stats_found = true;
      $first_date = $coupon_stats->date;
    }
*/

    return view('platform.analytics.campaign-analytics', compact('sl', 'first_date', 'stats_found', 'date_start', 'date_end', 'campaigns', 'campaign_id'));
  }
}