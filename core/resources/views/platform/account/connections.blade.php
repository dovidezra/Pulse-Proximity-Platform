<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">
      <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.connections') }}</a>
          </div>
        </div>
      </nav>
    </div>
  </div>

  <div class="row">

    <div class="col-md-3 col-lg-2">

        <div class="list-group">
          <a href="#/profile" class="list-group-item">{{ trans('global.profile') }}</a>
          <a href="#/plan" class="list-group-item">{{ trans('global.plan') }}</a>
          <a href="#/connections" class="list-group-item active">{{ trans('global.connections') }}</a>
        </div>
  
    </div>
    <div class="col-md-9 col-lg-10">
      <div class="card-box">
      {{ trans('global.no_connections_available_yet') }}
      </div>
<?php /*
        <ul class="nav nav-tabs navtab-custom">
          <li class="active"> <a href="#general" data-toggle="tab" aria-expanded="false">{{ trans('global.github') }}</a> </li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane active" id="general">
            <form class="ajax" id="frm" method="post" action="{{ url('platform/connections/github') }}">
              {!! csrf_field() !!}
              <fieldset>

                <div class="form-group">
                  <label for="github_username">{{ trans('global.github_username') }}</label>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-github" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" name="github_username" id="github_username" value="{{ (isset($user->settings->github_username)) ? $user->settings->github_username : '' }}" autocomplete="off">
                  </div>
                </div>

              </fieldset>
              <fieldset>
                
                <button class="btn btn-lg btn-primary waves-effect waves-light w-md ladda-button" type="submit" data-style="expand-right"><span class="ladda-label">{{ trans('global.save_changes') }}</span></button>
                
              </fieldset>
            </form>
          </div>
      </div>
*/ ?>
    </div>
  </div>

</div>