@extends('layouts.app')

@section('content') 
<header id="topnav">
  <div class="topbar-main">
    <div class="container"> 

      <div class="logo">
        <a href="#/" class="logo"><img src="{{ \Platform\Controllers\Core\Reseller::get()->logo }}" style="height: 35px" alt="{{ \Platform\Controllers\Core\Reseller::get()->name }}"></a>
      </div>
      
      <div class="menu-extras">
        <ul class="nav navbar-nav navbar-right pull-right">
          <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle waves-effect waves-light profile" data-toggle="dropdown" aria-expanded="true"><img src="{{ \Auth::user()->getAvatar() }}" class="img-circle avatar"> </a>
            <ul class="dropdown-menu">
              <li class="dropdown-header" style="font-size: 1.5rem">{{ \Auth::user()->name }}</li>
              <li class="dropdown-header text-muted">{{ \Auth::user()->email }}</li>
              <li role="separator" class="divider"><hr></li>
              <li><a href="#/profile"><i class="ti-user m-r-5"></i> {{ trans('global.profile') }}</a></li>
<?php if (Gate::allows('limitation', 'account.plan_visible')) { ?>
              <li><a href="#/plan"><i class="ti-crown m-r-5"></i> {{ trans('global.plan') }}</a></li>
<?php } ?>
              <li role="separator" class="divider"><hr></li>
              <li><a href="{{ url('logout') }}"><i class="ti-power-off m-r-5"></i> {{ trans('global.logout') }}</a></li>
            </ul>
          </li>
        </ul>
<?php
// Only show language dropdown if there's more than one language available
if (count($languages) > 1) {
?>
        <ul class="nav navbar-nav navbar-right pull-right">
          <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="true">{{ $current_language }} </a>
            <ul class="dropdown-menu">
<?php foreach($languages as $code => $language) { ?>
              <li><a href="{{ url('platform?lang=' . $code) }}">{{ $language }}</a></li>
<?php } ?>
            </ul>
          </li>
        </ul>
<?php } ?>
        <div class="menu-item"> 
          <a class="navbar-toggle">
          <div class="lines">
            <span></span>
            <span></span>
            <span></span>
          </div>
          </a> 
        </div>
      </div>
    </div>
  </div>

  <div class="navbar-custom">
    <div class="container">
      <div id="navigation">
        <ul class="navigation-menu">
           <li class="has-submenu" id="tour-dashboard"><a href="#/"><i class="material-icons">&#xE871;</i> {{ trans('global.dashboard') }}</a></li>
<?php if (Gate::allows('limitation', 'mobile.visible')) { ?>
          <li class="has-submenu"> <a href="javascript:void(0);"><i class="material-icons">&#xE32C;</i> {{ trans('global.mobile') }}</a>
            <ul class="submenu">
              <li class="dropdown-header">{{ trans('global.scenarios_and_content') }}</li>
              <li><a href="#/campaign/apps">{{ trans('global.apps') }}</a></li>
              <li><a href="#/campaigns">{{ trans('global.campaigns') }}</a></li>
<?php if (Gate::allows('limitation', 'mobile.cards_visible')) { ?>
              <li><a href="#/cards">{{ trans('global.cards') }}</a></li>
<?php } ?>
              <li role="separator" class="divider"><hr></li>
              <li class="dropdown-header">{{ trans('global.spots') }}</li>
<?php if (Gate::allows('limitation', 'mobile.beacons_visible')) { ?>
              <li><a href="#/beacons">{{ trans('global.beacons') }}</a></li>
<?php } ?>
<?php if (Gate::allows('limitation', 'mobile.geofences_visible')) { ?>
              <li><a href="#/geofences">{{ trans('global.geofences') }}</a></li>
<?php } ?>
            </ul>
          </li>
<?php } ?>
<?php if (Gate::allows('limitation', 'online.visible')) { ?>
          <li class="has-submenu"> <a href="javascript:void(0);"><i class="material-icons">&#xE894;</i> {{ trans('global.online') }}</a>
            <ul class="submenu">
<?php if (Gate::allows('limitation', 'online.members_visible')) { ?>
              <li> <a href="#/members">{{ trans('global.members') }}</a>
<?php } ?>
            </ul>
          </li>
<?php } ?>

<?php if (Gate::allows('limitation', 'media.visible')) { ?>
          <li class="has-submenu"><a href="#/media"><i class="material-icons">&#xE8A7;</i> {{ trans('global.media') }}</a></li>
<?php } ?>
          <li class="has-submenu"> <a href="#/profile"><i class="material-icons">&#xE853;</i> {{ trans('global.account') }}</a></li>

<?php if (Gate::allows('admin-management')) { ?>
          <li class="has-submenu last-elements"> <a href="javascript:void(0);" data-toggle="tooltip" title="{{ trans('global.admin') }}"><i class="material-icons">&#xE8B8;</i></a>
            <ul class="submenu">
              <li class="has-submenu">
                <a href="javascript:void(0);">{{ trans('global.users') }}</a>
                <ul class="submenu">
                  <li><a href="#/admin/users">{{ trans('global.users') }}</a></li>
                  <li><a href="#/admin/plans">{{ trans('global.plans') }}</a></li>
<?php if (Gate::allows('owner-management')) { ?>
                  <li role="separator" class="divider"><hr></li>
                  <li><a href="#/admin/resellers">{{ trans('global.resellers') }}</a></li>
<?php } ?>
                </ul>
              </li>

            </ul>
          </li>
<?php } ?>
        </ul>
      </div>
    </div>
  </div>
</header>
<div class="wrapper">
  <section id="view">
  </section>
</div>

<script async defer
src="https://maps.googleapis.com/maps/api/js?key={{ env('GMAPS_KEY') }}&libraries=places">
</script>
@endsection 