<?php namespace Platform\Controllers\App;

use \Platform\Controllers\Core;
use \Platform\Models\Software;
use Illuminate\Support\Facades\Gate;

class DashboardController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Dashboard Controller
   |--------------------------------------------------------------------------
   |
   | Dashboard related logic
   |--------------------------------------------------------------------------
   */

  /**
   * Dashboard
   */

  public function showDashboard() {
    return view('platform.dashboard.dashboard');
  }
}