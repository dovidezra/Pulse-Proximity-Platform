<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">

      <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand link" href="#/campaigns">{{ trans('global.campaigns') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.edit_campaign') }}</a>
          </div>
        </div>
      </nav>

    </div>
  </div>

  <div class="row">
    <form class="ajax" id="frm" method="post" action="{{ url('platform/campaign') }}">
      <input type="hidden" name="sl" value="{{ $sl }}">
      {!! csrf_field() !!}
      <div class="col-md-12">
        <div class="panel panel-default">
          <fieldset class="panel-body">
           
            <div class="form-group">
              <label for="name">{{ trans('global.name') }} <sup>*</sup></label>
              <input type="text" class="form-control" name="name" id="name" value="{{ $campaign->name }}" required autocomplete="off">
            </div>

            <div class="form-group">
<?php
echo Former::select('apps[]')
  ->multiple('multiple')
  ->class('select2-multiple')
  ->name('apps[]')
  ->dataPlaceholder(trans('global.apps_placeholder'))
  ->forceValue($campaign->apps)
  ->fromQuery($apps, 'name', 'id')
  ->label(trans('global.apps') . ' <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="' . trans('global.apps_help') . '">&#xE887;</i>');
 ?>
            </div>

            <div class="form-group">
<?php
echo Former::select('timezone')
  ->class('select2-required form-control')
  ->name('timezone')
  ->forceValue($campaign->timezone)
  ->options(trans('timezones.timezones'))
  ->label(trans('global.timezone'));
?>
            </div>
           
            <div class="form-group" style="margin-top:20px">
              <div class="checkbox checkbox-primary">
                <input name="active" id="active" type="checkbox" value="1" <?php if ((boolean) $campaign->active) echo 'checked'; ?>>
                <label for="active"> {{ trans('global.active') }}</label>
              </div>
            </div>

          </fieldset>
        </div>
<?php /*
        <div class="panel panel-default">

          <fieldset class="panel-body">

            <div class="form-group">
              <label for="major">{{ trans('global.radius') }} <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('global.radius_help') }}">&#xE887;</i> <sup>*</sup></label>
              
              <div class="input-group">
                <input type="number" class="form-control" name="radius" id="radius" required min="50" value="{{ $campaign->radius }}">
                <span class="input-group-addon">{{ trans('global.meter') }}</span>
              </div>
            </div>

          </fieldset>
        </div> */ ?>
      </div>
      <!-- end col -->
<?php /*
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('global.campaign_region') }} <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('global.campaign_geofence_help') }}">&#xE887;</i></h3>
          </div>
          <fieldset class="panel-body">
            <input id="pac-input" class="gcontrols" type="text" placeholder="{{ trans('global.search_') }}" style="display: none">
            <div id="gmap" class="gmap" style="width: 100%; height: 351px;"></div>
            <input type="hidden" id="lat" name="lat" value="{{ $campaign->lat }}">
            <input type="hidden" id="lng" name="lng" value="{{ $campaign->lng }}">
            <input type="hidden" id="zoom" name="zoom" value="{{ $campaign->zoom }}">
          </fieldset>
        </div>
      </div>
      <!-- end col -->
 */ ?>
      <div class="col-md-12">
   
        <div class="panel panel-inverse panel-border">
          <div class="panel-heading"></div>
          <div class="panel-body">
            <a href="#/campaigns" class="btn btn-lg btn-default waves-effect waves-light w-md">{{ trans('global.back') }}</a>
            <button class="btn btn-lg btn-primary waves-effect waves-light w-md ladda-button" type="submit" data-style="expand-right"><span class="ladda-label">{{ trans('global.save_changes') }}</span></button>
          </div>
        </div>
    
      </div>

    </form>
  </div>
  <!-- end row --> 
  
</div>
<?php /*
<script>
var geofence;
// Radius
$('#radius').on('change keyup', function(e) {
  var radius = parseInt($(this).val());
  console.log(radius);
  geofence.setRadius(radius);
});

initMap();

// Catch enter
$('#pac-input').on('keypress', function(e) {
  if (e.keyCode == 13) {
    return false;
  }
});

function initMap() {
  var map = new google.maps.Map(document.getElementById('gmap'), {
    center: {lat: {{ $campaign->lat }}, lng: {{ $campaign->lng }}},
    mapTypeId: 'roadmap'
  });

  geofence = new google.maps.Circle({
    strokeColor: '#FF0000',
    strokeOpacity: 0.8,
    strokeWeight: 2,
    fillColor: '#FF0000',
    fillOpacity: 0.35,
    map: map,
    editable: true,
    center: {lat: {{ $campaign->lat }}, lng: {{ $campaign->lng }}},
    radius: {{ $campaign->radius }}
  });

  map.fitBounds(geofence.getBounds());

  // Create the search box and link it to the UI element.
  var input = document.getElementById('pac-input');
  var searchBox = new google.maps.places.SearchBox(input);
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  setTimeout(function() {
    $('#pac-input').fadeIn();
  }, 500);
  
  // Bias the SearchBox results towards current map's viewport.
  map.addListener('bounds_changed', function() {
    searchBox.setBounds(map.getBounds());
  });

  var markers = [];
  // Listen for the event fired when the user selects a prediction and retrieve
  // more details for that place.
  searchBox.addListener('places_changed', function() {
    var places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }

    // For each place, get the icon, name and location.
    var bounds = new google.maps.LatLngBounds();
    places.forEach(function(place) {
      if (!place.geometry) {
        console.log("Returned place contains no geometry");
        return;
      }
      // Update geofence
      geofence.setCenter(place.geometry.location);
    });
    map.fitBounds(geofence.getBounds());
  });

  google.maps.event.addListener(geofence, 'center_changed', function() {
    setLngLat(geofence);
  });

  google.maps.event.addListener(geofence, 'radius_changed', function() {
    var radius = parseInt(geofence.getRadius());
    $('#radius').val(radius);
  });

  google.maps.event.addListener(map, 'zoom_changed', function(event) {
    $('#zoom').val(map.getZoom());
  });  

  google.maps.event.addListener(map, 'click', function(event) {
    geofence.setCenter(event.latLng);
    setLngLat(geofence);
  });  
}

function setLngLat(geofence){
  $('#lat').val(geofence.getCenter().lat());
  $('#lng').val(geofence.getCenter().lng());
}
</script> */ ?>