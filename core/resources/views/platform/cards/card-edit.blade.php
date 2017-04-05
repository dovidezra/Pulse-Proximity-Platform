<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">
      <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand link" href="#/cards">{{ trans('global.cards') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.edit_card') }}</a>
          </div>
        </div>
      </nav>
    </div>
  </div>

  <div class="row">
    <form class="ajax" id="frm" method="post" action="{{ url('platform/card') }}">
      <input type="hidden" name="sl" value="{{ $sl }}">
      {!! csrf_field() !!}
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('global.visibility') }}</h3>
          </div>
          <fieldset class="panel-body">
            <div class="form-group">
              <label for="campaigns">{{ trans('global.campaigns') }} <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('global.card_campaigns_help') }}">&#xE887;</i></label>
                <select multiple="multiple" name="campaigns[]" id="campaigns" class="select2-multiple" data-placeholder="{{ trans('global.select_one_or_more_campaigns') }}">
<?php
foreach($campaigns as $campaign) {
  $selected = (in_array($campaign->id, $selected_campaigns)) ? ' selected' : '';
  echo '<option value="' . $campaign->id . '"' . $selected . '>' . $campaign->name . '</option>';
}
?>
              </select>
            </div>

            <div class="form-group">
              <label for="places">{{ trans('global.only_show_when_in_range_of') }} <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('global.card_only_show_when_in_range_of_help') }}">&#xE887;</i></label>
                <select multiple="multiple" name="places[]" id="places" class="select2-multiple-spots" data-placeholder="{{ trans('global.select_beacons_and_or_geofences') }}">
<?php
// Beacons in a group
foreach($location_groups as $location_group)
{
  $geofences_in_group = $location_group->geofences()->orderBy('name', 'asc')->get();
  $beacons_in_group = $location_group->beacons()->orderBy('name', 'asc')->get();

  echo '<optgroup label="' . $location_group->name . '">';

  foreach($geofences_in_group as $geofence)
  {
    $selected = (in_array($geofence->id, $selected_geofences)) ? ' selected' : '';
    echo '<option value="geofence' . $geofence->id . '" data-type="geofence"' . $selected . '>' . $geofence->name . '</option>';
  }

  foreach($beacons_in_group as $beacon)
  {
    $selected = (in_array($beacon->id, $selected_beacons)) ? ' selected' : '';
    echo '<option value="beacon' . $beacon->id . '" data-type="beacon"' . $selected . '>' . $beacon->name . '</option>';
  }

  echo '</optgroup>';
}
// Beacons and geofences without a group
foreach($geofences as $geofence)
{
  $selected = (in_array($geofence->id, $selected_geofences)) ? ' selected' : '';
  echo '<option value="geofence' . $geofence->id . '" data-type="geofence"' . $selected . '>' . $geofence->name . '</option>';
}

foreach($beacons as $beacon)
{
  $selected = (in_array($beacon->id, $selected_beacons)) ? ' selected' : '';
  echo '<option value="beacon' . $beacon->id . '" data-type="beacon"' . $selected . '>' . $beacon->name . '</option>';
}
?>
              </select>
            </div>

            <div class="form-group m-t-20">
              <div class="checkbox checkbox-primary">
                <input name="active" id="active" type="checkbox" value="1" <?php if ((boolean) $card->active) echo 'checked'; ?>>
                <label for="active"> {{ trans('global.active') }}</label>
              </div>
            </div>
            
        </div>


        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('global.content') }}</h3>
          </div>

          <fieldset class="panel-body">
           
            <div class="form-group">
              <label for="name">{{ trans('global.name') }} <sup>*</sup></label>
              <input type="text" class="form-control" name="name" id="name" value="{{ $card->name }}" required autocomplete="off">
            </div>

            <div class="form-group">
              <label for="description">{{ trans('global.description') }} <sup>*</sup></label>
              <textarea class="form-control" name="description" id="description" required rows="3">{{ $card->description }}</textarea>
            </div>

            <div class="form-group">
              <label for="content">{{ trans('global.content') }}</label>
              <textarea class="form-control editor-basic" name="content" rows="12">{{ $card->content }}</textarea>
            </div>

          </fieldset>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('global.design') }}</h3>
          </div>
          <fieldset class="panel-body">

            <div class="form-group">
              <label for="icon">{{ trans('global.icon') }} <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('global.card_icon_help') }}">&#xE887;</i></label>
              <div class="input-group">
                <input type="text" class="form-control" id="icon" name="icon" autocomplete="off" value="{{ $card->icon }}">
                <div class="input-group-btn add-on">
                  <button type="button" class="btn btn-primary" data-toggle="tooltip" title="{{ trans('global.browse') }}" data-type="image" data-id="icon" data-preview="icon-preview"> <i class="fa fa-folder-open" aria-hidden="true"></i> </button>
                  <button type="button" class="btn btn-primary disabled" data-toggle="tooltip" title="{{ trans('global.preview') }}" id="icon-preview"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="image">{{ trans('global.image') }} <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('global.card_image_help') }}">&#xE887;</i></label>
              <div class="input-group">
                <input type="text" class="form-control" id="image" name="image" autocomplete="off" value="{{ $card->image }}">
                <div class="input-group-btn add-on">
                  <button type="button" class="btn btn-primary" data-toggle="tooltip" title="{{ trans('global.browse') }}" data-type="image" data-id="image" data-preview="image-preview"> <i class="fa fa-folder-open" aria-hidden="true"></i> </button>
                  <button type="button" class="btn btn-primary disabled" data-toggle="tooltip" title="{{ trans('global.preview') }}" id="image-preview"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                </div>
              </div>
            </div>

          </fieldset>
        </div>

      </div>
      <!-- end col -->
      
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="pull-right" style="margin: 5px 0">
              <a href="javascript:void(0);" onclick="$('.category_select').prop('checked', true);">{{ trans('global.select_all') }}</a> | 
              <a href="javascript:void(0);" onclick="$('.category_select').prop('checked', false);">{{ trans('global.select_none') }}</a>
            </div>
            <h3 class="panel-title">{{ trans('global.categories') }}</h3>
          </div>
          <fieldset class="panel-body">

            <div class="row">
<?php
foreach($categories as $category) {
?>
            <div class="col-md-6">
              <div class="form-group">
                <div class="checkbox checkbox-primary">
                  <input name="categories[]" id="{{ $category->name }}" type="checkbox" value="{{ $category->id }}" <?php if (in_array($category->id, $selected_categories)) echo 'checked'; ?> class="category_select">
                  <label for="{{ $category->name }}">{{ trans('global.app_categories.' . $category->name) }}</label>
                </div>
              </div>
            </div>
<?php
}
?>

            </div>

          </fieldset>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('global.location') }} <sup>*</sup> <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('global.card_location_help') }}">&#xE887;</i></h3>
          </div>
          <fieldset class="panel-body">

            <input id="pac-input" class="gcontrols" type="text" placeholder="{{ trans('global.search_') }}" style="display: none">
            <div id="gmap" class="gmap" style="width: 100%; height: 757px;"></div>
            <input type="hidden" id="lat" name="lat" value="{{ $card->lat }}">
            <input type="hidden" id="lng" name="lng" value="{{ $card->lng }}">
            <input type="hidden" id="zoom" name="zoom" value="{{ $card->zoom }}">
          </fieldset>
        </div>

      </div>
      <!-- end col -->

      <div class="col-md-12">
   
        <div class="panel panel-inverse panel-border">
          <div class="panel-heading"></div>
          <div class="panel-body">
            <a href="#/cards" class="btn btn-lg btn-default waves-effect waves-light w-md">{{ trans('global.back') }}</a>
            <button class="btn btn-lg btn-primary waves-effect waves-light w-md ladda-button" type="submit" data-style="expand-right"><span class="ladda-label">{{ trans('global.save_changes') }}</span></button>
          </div>
        </div>
    
      </div>

    </form>
  </div>
  <!-- end row --> 
  
</div>

<script>
initMap();

// Catch enter
$('#pac-input').on('keypress', function(e) {
  if (e.keyCode == 13) {
    return false;
  }
});

function initMap() {
  var map = new google.maps.Map(document.getElementById('gmap'), {
    center: {lat: {{ $card->lat }}, lng: {{ $card->lng }}},
    zoom: {{ $card->zoom }},
    mapTypeId: 'roadmap'
  });

  var marker = new google.maps.Marker({
    map: map,
    draggable:true,
    animation: google.maps.Animation.DROP,
    position: {lat: {{ $card->lat }}, lng: {{ $card->lng }}},
  });

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
      var icon = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)
      };

      // Update marker
      marker.setPosition(place.geometry.location);
      marker.setIcon(icon);

      if (place.geometry.viewport) {
        // Only geocodes have viewport.
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
    });
    map.fitBounds(bounds);

    $('#zoom').val(map.getZoom());
    setLngLat(marker);
  });

  google.maps.event.addListener(marker, 'dragend', function() 
  {
    setLngLat(marker);
  });

  google.maps.event.addListener(map, 'zoom_changed', function(event) {
    $('#zoom').val(map.getZoom());
  });  

  google.maps.event.addListener(map, 'click', function(event) {
    marker.setPosition(event.latLng);
    setLngLat(marker);
  });  
}

function setLngLat(marker){
  $('#lat').val(marker.getPosition().lat());
  $('#lng').val(marker.getPosition().lng());
}
</script>