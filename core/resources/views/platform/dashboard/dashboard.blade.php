<div class="container">
  <div class="row m-t">
    <div class="col-sm-12">
      <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.welcome_name', ['name' => \Auth::user()->name]) }}</a>
          </div>
        </div>
      </nav>
    </div>
<?php if (\Auth::user()->getPlanId() == 0 && Gate::allows('limitation', 'account.plan_visible')) { ?>
    <div class="col-md-12">
      <div class="alert alert-success">{!! trans('global.you_are_on_plan', ['plan' => '<strong>' . \Auth::user()->getPlanName() . '</strong>']) !!} {!! trans('global.click_here_for_more_info', ['link' => '#/plan']) !!}</div>
    </div>
<?php } ?>
  </div>
<?php if (Gate::allows('limitation', 'proximity.visible')) { ?>
<?php
$cols = 4;

if (! Gate::allows('limitation', 'proximity.beacons_visible')) $cols--;
if (! Gate::allows('limitation', 'proximity.geofences_visible')) $cols--;

switch ($cols) {
  case 4: $col_class = 'col-sm-6 col-lg-3'; break;
  case 3: $col_class = 'col-sm-4 col-lg-4'; break;
  case 2: $col_class = 'col-xs-6 col-lg-6'; break;
}
?>
  <div class="row">
    <div class="{{ $col_class }}">
      <div class="card-box widget-icon">
        <a href="#/apps">
          <i class="material-icons text-primary">&#xE5C3;</i>
          <div class="wid-icon-info">
            <p class="text-muted m-b-5 font-13 text-uppercase">{{ trans('global.apps') }}</p>
            <h4 class="m-t-0 m-b-5 counter">{{ \Platform\Models\Campaigns\App::where('user_id', '=', \Platform\Controllers\Core\Secure::userId())->count() }}</h4>
          </div>
        </a>
      </div>
    </div>
    <div class="{{ $col_class }}">
      <div class="card-box widget-icon">
        <a href="#/campaigns">
          <i class="material-icons text-primary">&#xE7F7;</i>
          <div class="wid-icon-info">
            <p class="text-muted m-b-5 font-13 text-uppercase">{{ trans('global.campaigns') }}</p>
            <h4 class="m-t-0 m-b-5 counter">{{ \Platform\Models\Campaigns\Campaign::where('user_id', '=', \Platform\Controllers\Core\Secure::userId())->count() }}</h4>
          </div>
        </a>
      </div>
    </div>
<?php if (Gate::allows('limitation', 'proximity.beacons_visible')) { ?>
    <div class="{{ $col_class }}">
      <div class="card-box widget-icon">
        <a href="#/beacons">
          <i class="material-icons text-primary">&#xE8E1;</i>
          <div class="wid-icon-info">
            <p class="text-muted m-b-5 font-13 text-uppercase">{{ trans('global.beacons') }}</p>
            <h4 class="m-t-0 m-b-5 counter">{{ \Platform\Models\Location\Beacon::where('user_id', '=', \Platform\Controllers\Core\Secure::userId())->count() }}</h4>
          </div>
        </a>
      </div>
    </div>
<?php } ?>
<?php if (Gate::allows('limitation', 'proximity.geofences_visible')) { ?>
    <div class="{{ $col_class }}">
      <div class="card-box widget-icon">
        <a href="#/geofences">
          <i class="material-icons text-primary">&#xE55E;</i>
          <div class="wid-icon-info">
            <p class="text-muted m-b-5 font-13 text-uppercase">{{ trans('global.geofences') }}</p>
            <h4 class="m-t-0 m-b-5 counter">{{ \Platform\Models\Location\Geofence::where('user_id', '=', \Platform\Controllers\Core\Secure::userId())->count() }}</h4>
          </div>
        </a>
      </div>
    </div>
<?php } ?>
  </div>
<?php } ?>


</div>