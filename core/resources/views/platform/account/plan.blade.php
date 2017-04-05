<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">
      <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.account') }}</a>
          </div>
        </div>
      </nav>
    </div>
  </div>

  <div class="row">

    <div class="col-md-3 col-lg-2">

        <div class="list-group">
          <a href="#/profile" class="list-group-item">{{ trans('global.profile') }}</a>
          <a href="#/plan" class="list-group-item active">{{ trans('global.plan') }}</a>
          <a href="#/connections" class="list-group-item">{{ trans('global.connections') }}</a>
        </div>
  
    </div>
    <div class="col-md-9 col-lg-10">
      <div class="row">
        <div class="col-lg-12">
          <div class="row">
<?php
$plan_count = $plans->count();
$disabled = false;
$col_span = 'col-md-12';

if ($plan_count == 2) $col_span = 'col-md-6';
if ($plan_count%3 == 0) $col_span = 'col-md-4';
if ($plan_count%4 == 0) $col_span = 'col-md-3';
?>
        <article class="pricing-column {{ $col_span }}" style="margin-bottom: 0">
            <div class="inner-box card-box">
                <div class="plan-header text-center">
                    <h3 class="plan-title">&nbsp;</h3>
                    <h2 class="plan-price">{{ trans('global.free') }}</h2>
                    <div class="plan-duration">&nbsp;</div>
                </div>
                <ul class="plan-stats list-unstyled text-center">
                    <li><i class="ti-na text-danger"></i> {{ trans('global.beacons') }}</li>
                    <li><i class="ti-na text-danger"></i> {{ trans('global.geofences') }}</li>
                </ul>

                <div class="text-center">
<?php

if (\Auth::user()->getPlanId() == 0) {
  $btn_text = trans('global.current_plan');
  $btn_link = 'javascript:void(0);';
  $disabled = false;
  $btn_class = 'primary';
} else {
  $btn_text = trans('global.free');
  $btn_link = 'javascript:void(0);';
  $btn_class = 'default';
}
?>
                    <a href="{{ $btn_link }}" class="select-plan btn btn-{{ $btn_class }} btn-bordred btn-rounded waves-effect waves-light" disabled>{{ $btn_text }}</a>
                </div>
            </div>
        </article>

<?php

foreach($plans as $plan) {
?>
        <article class="pricing-column {{ $col_span }}" style="margin-bottom: 0">
<?php if ($plan->ribbon != '') { ?>
            <div class="ribbon"><span>{{ $plan->ribbon }}</span></div>
<?php } ?>
            <div class="inner-box card-box">
                <div class="plan-header text-center"<?php if ($plan->price1_subtitle != '') echo ' style="padding-bottom:23px"'; ?>>
                    <h3 class="plan-title">{!! $plan->name !!}</h3>
                    <h2 class="plan-price">{!! $plan->price1_string !!}</h2>
                    <div class="plan-duration"><?php echo (\Lang::has('global.' . $plan->price1_period_string)) ? trans('global.' . $plan->price1_period_string) : $plan->price1_period_string; ?></div>
<?php if ($plan->price1_subtitle != '') { ?>
                    <h4 class="m-b-0">{!! $plan->price1_subtitle !!}</h4>
<?php } else { ?>
<?php } ?>
                </div>

                <ul class="plan-stats list-unstyled text-center">
                    <li><?php echo ($plan->limitations['proximity']['beacons_visible'] == 1) ? '<i class="ti-check text-success"></i>' : '<i class="ti-na text-danger"></i>'; ?> {{ trans('global.beacons') }}</li>
                    <li><?php echo ($plan->limitations['proximity']['geofences_visible'] == 1) ? '<i class="ti-check text-success"></i>' : '<i class="ti-na text-danger"></i>'; ?> {{ trans('global.geofences') }}</li>
                </ul>

                <div class="text-center">
<?php

if (\Auth::user()->getPlanId() == $plan->id) {
  $btn_text = trans('global.current_plan');
  $btn_link = 'javascript:void(0);';
  $btn_target = '';
  $disabled = false;
  $btn_class = 'primary';
} elseif (! $disabled) {

  $order_url = (isset($plan->order_url)) ? $plan->order_url . '&CUSTOMERID=' . \Auth::user()->id : '';

  $btn_text = trans('global.order_now');
  $btn_link = ($order_url != '') ? $order_url : 'javascript:void(0);';
  $btn_target = '';
  //$btn_target = ($order_url != '') ? '_blank' : '';
  $btn_class = 'warning';
} else {
  $btn_text = trans('global.order_now');
  $btn_link = 'javascript:void(0);';
  $btn_target = '';
  $btn_class = 'warning';
}

?>
                    <a href="{{ $btn_link }}" class="select-plan btn btn-{{ $btn_class }} btn-bordred btn-rounded waves-effect waves-light"<?php if ($disabled || \Auth::user()->getPlanId() == $plan->id || $btn_link == 'javascript:void(0);') echo ' disabled'; ?><?php if ($btn_target != '') echo ' target="' . $btn_target . '"'; ?>>{{ $btn_text }}</a>
                </div>
            </div>
        </article>
<?php
}
?>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>