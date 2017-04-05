$(function() {
  if (window.location.pathname == '/platform') {
    
    /*
     * Called with every route change
     */

    var allRoutes = function() {
      //
    };

    /*
     * Called before every route change
     */

    var beforeRoute = function() {
      // Loader
      $('#view').html('<div class="container"><div class="row"><div class="col-xs-12"><div class="loader" id="throbber"></div></div></div>');
      $('#throbber').css('margin', (parseInt($(window).outerHeight()) / 2) - 115 + 'px auto 0 auto');
    };

    /*
     * Called after every route change
     */

    var afterRoute = function() {
      //
    };

    /*
     * Routes
     */

    var router = Router({
      '/': function () { loadRoute('platform/dashboard'); },
      '/media': function () { loadRoute('platform/media/browser'); },

      '/profile': function () { loadRoute('platform/profile'); },
      '/plan': function () { loadRoute('platform/plan', 'profile'); },
      '/connections': function () { loadRoute('platform/connections', 'profile'); },

      '/campaign/apps': function () { loadRoute('platform/campaign/apps'); },
      '/campaign/app/new': function () { loadRoute('platform/campaign/app/new', 'campaign/apps'); },
      '/campaign/app/edit/:sl': function (sl) { loadRoute('platform/campaign/app/edit?sl=' + sl, 'campaign/apps'); },

      '/campaigns': function () { loadRoute('platform/campaigns'); },
      '/campaign/new': function () { loadRoute('platform/campaign/new', 'campaigns'); },
      '/campaign/edit/:sl': function (sl) { loadRoute('platform/campaign/edit?sl=' + sl, 'campaigns'); },

      '/campaign/analytics': function () { loadRoute('platform/campaign/analytics'); },
      '/campaign/analytics/:sl': function (sl) { loadRoute('platform/campaign/analytics?sl=' + sl, 'campaigns'); },
      '/campaign/analytics/:start/:end': function (start, end) { loadRoute('platform/campaign/analytics?start=' + start + '&end=' + end, 'campaigns'); },
      '/campaign/analytics/:start/:end/:sl': function (start, end, sl) { loadRoute('platform/campaign/analytics?sl=' + sl + '&start=' + start + '&end=' + end, 'campaigns'); },

      '/scenarios/:sl': function (sl) { loadRoute('platform/scenarios?sl=' + sl, 'campaigns'); },

      '/beacons': function () { loadRoute('platform/beacons'); },
      '/beacon/new': function () { loadRoute('platform/beacon/new', 'beacons'); },
      '/beacon/edit/:sl': function (sl) { loadRoute('platform/beacon/edit?sl=' + sl, 'beacons'); },

      '/geofences': function () { loadRoute('platform/geofences'); },
      '/geofence/new': function () { loadRoute('platform/geofence/new', 'geofences'); },
      '/geofence/edit/:sl': function (sl) { loadRoute('platform/geofence/edit?sl=' + sl, 'geofences'); },

      '/cards': function () { loadRoute('platform/cards'); },
      '/card/new': function () { loadRoute('platform/card/new', 'cards'); },
      '/card/edit/:sl': function (sl) { loadRoute('platform/card/edit?sl=' + sl, 'cards'); },

      '/members': function () { loadRoute('platform/members'); },
      '/member/:sl': function (sl) { loadRoute('platform/member/edit?sl=' + sl, 'members'); },

      '/admin/plans': function () { loadRoute('platform/admin/plans'); },
      '/admin/plan': function () { loadRoute('platform/admin/plan/new', 'admin/plans'); },
      '/admin/plan/:sl': function (sl) { loadRoute('platform/admin/plan/edit?sl=' + sl, 'admin/plans'); },

      '/admin/resellers': function () { loadRoute('platform/admin/resellers'); },
      '/admin/reseller': function () { loadRoute('platform/admin/reseller/new', 'admin/resellers'); },
      '/admin/reseller/:sl': function (sl) { loadRoute('platform/admin/reseller/edit?sl=' + sl, 'admin/resellers'); },

      '/admin/users': function () { loadRoute('platform/admin/users'); },
      '/admin/user': function () { loadRoute('platform/admin/user/new', 'admin/users'); },
      '/admin/user/:sl': function (sl) { loadRoute('platform/admin/user/edit?sl=' + sl, 'admin/users'); }
    });

    /*
     * Route configuration
     */

    router.configure({
      on: allRoutes,
      before: beforeRoute,
      after: afterRoute
    });

    router.init('#/');

    function loadRoute(url, route) {
      $('#view').load(url, function() {
        onPartialLoaded();

        $('.navigation-menu li').removeClass('active');
        initNavbarMenuActive(route);
      });
    }
  }
});