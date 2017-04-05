<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">

      <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand link" href="#/campaign/apps">{{ trans('global.apps') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.edit_app') }}</a>
          </div>
        </div>
      </nav>

    </div>
  </div>

  <div class="row">
    <form class="ajax" id="frm" method="post" action="{{ url('platform/campaign/app') }}">
      <input type="hidden" name="sl" value="{{ $sl }}">
      {!! csrf_field() !!}
      <div class="col-md-12">
        <div class="panel panel-default">
          <fieldset class="panel-body">
           
            <div class="form-group">
              <label for="name">{{ trans('global.name') }} <sup>*</sup></label>
              <input type="text" class="form-control" name="name" id="name" value="{{ $app->name }}" required autocomplete="off">
            </div>
           
            <div class="form-group">
              <label for="api_token">{{ trans('global.api_token') }} <sup>*</sup> <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('global.api_token_help') }}">&#xE887;</i></label>
              <input type="text" class="form-control" name="api_token" id="api_token" value="{{ $app->api_token }}" required autocomplete="off">
            </div>
          
            <div class="form-group" style="margin-top:20px">
              <div class="checkbox checkbox-primary">
                <input name="active" id="active" type="checkbox" value="1" <?php if ((boolean) $app->active) echo 'checked'; ?>>
                <label for="active"> {{ trans('global.active') }}</label>
              </div>
            </div>

          </fieldset>
        </div>

      </div>
      <!-- end col -->

      <div class="col-md-12">
   
        <div class="panel panel-inverse panel-border">
          <div class="panel-heading"></div>
          <div class="panel-body">
            <a href="#/campaign/apps" class="btn btn-lg btn-default waves-effect waves-light w-md">{{ trans('global.back') }}</a>
            <button class="btn btn-lg btn-primary waves-effect waves-light w-md ladda-button" type="submit" data-style="expand-right"><span class="ladda-label">{{ trans('global.save_changes') }}</span></button>
          </div>
        </div>
    
      </div>

    </form>
  </div>
  <!-- end row --> 
  
</div>