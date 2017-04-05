var selected_apps = [];
var selected_campaigns = [];
var selected_cards = [];
var selected_beacons = [];
var selected_geofences = [];
var ladda_button;

function getCookie(cname) {
  var name = cname + "=";
  var ca = document.cookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length,c.length);
    }
  }
  return "";
}

/**
 * Navigation
 */

(function($){

  'use strict';

  function initNavbar () {

    $('.navbar-toggle').on('click', function(event) {
      $(this).toggleClass('open');
      $('#navigation').slideToggle(400);
    });

    //$('.navigation-menu>li').slice(-1).addClass('last-elements');

    $('.navigation-menu li.has-submenu a[href="javascript:void(0);"]').on('click', function(e) {
      if ($(window).width() < 992) {
        e.preventDefault();
        $(this).parent('li').toggleClass('open').find('.submenu:first').toggleClass('open');
      }
    });

    $('.navigation-menu li.has-submenu a:not([href="javascript:void(0);"])').on('click', function(e) {
      var $submenu = $(this).closest('.submenu');
      $submenu.addClass('hidden');
      setTimeout(function() {
        $submenu.removeClass('hidden');
      }, 200);
    });
  }

  function init () {
    initNavbar();
    initNavbarMenuActive();
  }

  init();

})(jQuery)

// === following js will activate the menu in left side bar based on url ====
function initNavbarMenuActive(route) {
  route = (typeof route !== 'undefined') ? window.location.protocol + "//" + window.location.host + '/platform#/' + route : '';

  $(".navigation-menu a").each(function () {
    if (this.href == window.location.href || route == this.href) {
      $(this).parent().addClass("active"); // add active to li of the current link
      $(this).parent().parent().parent().addClass("active"); // add active class to an anchor
      $(this).parent().parent().parent().parent().parent().addClass("active"); // add active class to an anchor
    }
  });
}

/**
 * Portlets
 */
!function($) {
  "use strict";

  /**
  Portlet Widget
  */
  var Portlet = function() {
    this.$body = $("body"),
    this.$portletIdentifier = ".portlet",
    this.$portletCloser = '.portlet a[data-toggle="remove"]',
    this.$portletRefresher = '.portlet a[data-toggle="reload"]'
  };

  //on init
  Portlet.prototype.init = function() {
    // Panel closest
    var $this = this;
    $(document).on("click",this.$portletCloser, function (ev) {
      ev.preventDefault();
      var $portlet = $(this).closest($this.$portletIdentifier);
        var $portlet_parent = $portlet.parent();
      $portlet.remove();
      if ($portlet_parent.children().length == 0) {
        $portlet_parent.remove();
      }
    });

    // Panel Reload
    $(document).on("click",this.$portletRefresher, function (ev) {
      ev.preventDefault();
      var $portlet = $(this).closest($this.$portletIdentifier);
      // This is just a simulation, nothing is going to be reloaded
      $portlet.append('<div class="panel-disabled"><div class="loader-1"></div></div>');
      var $pd = $portlet.find('.panel-disabled');
      setTimeout(function () {
        $pd.fadeOut('fast', function () {
          $pd.remove();
        });
      }, 500 + 300 * (Math.random() * 5));
    });
  },
  //
  $.Portlet = new Portlet, $.Portlet.Constructor = Portlet

}(window.jQuery),

/**
 * Notifications
 */
function($) {
  "use strict";

  var Notification = function() {};

  //simple notificaiton
  Notification.prototype.notify = function(style,position, title, text) {
    var icon = 'fa fa-adjust';
    if(style == "error"){
      icon = "fa fa-exclamation";
    }else if(style == "warning"){
      icon = "fa fa-warning";
    }else if(style == "success"){
      icon = "fa fa-check";
    }else if(style == "custom"){
      icon = "md md-album";
    }else if(style == "info"){
      icon = "fa fa-question";
    }else{
      icon = "fa fa-adjust";
    }
    $.notify({
      title: title,
      text: text,
      image: "<i class='"+icon+"'></i>"
    }, {
      style: 'metro',
      className: style,
      globalPosition:position,
      showAnimation: "show",
      showDuration: 0,
      hideDuration: 0,
      autoHide: true,
      clickToHide: true
    });
  },

  //auto hide notification
  Notification.prototype.autoHideNotify = function (style,position, title, text) {
    var icon = "fa fa-adjust";
    if(style == "error"){
      icon = "fa fa-exclamation";
    }else if(style == "warning"){
      icon = "fa fa-warning";
    }else if(style == "success"){
      icon = "fa fa-check";
    }else if(style == "custom"){
      icon = "md md-album";
    }else if(style == "info"){
      icon = "fa fa-question";
    }else{
      icon = "fa fa-adjust";
    }
    $.notify({
      title: title,
      text: text,
      image: "<i class='"+icon+"'></i>"
    }, {
      style: 'metro',
      className: style,
      globalPosition:position,
      showAnimation: "show",
      showDuration: 0,
      hideDuration: 0,
      autoHideDelay: 5000,
      autoHide: true,
      clickToHide: true
    });
  },
  //confirmation notification
  Notification.prototype.confirm = function(style,position, title) {
    var icon = "fa fa-adjust";
    if(style == "error"){
      icon = "fa fa-exclamation";
    }else if(style == "warning"){
      icon = "fa fa-warning";
    }else if(style == "success"){
      icon = "fa fa-check";
    }else if(style == "custom"){
      icon = "md md-album";
    }else if(style == "info"){
      icon = "fa fa-question";
    }else{
      icon = "fa fa-adjust";
    }
    $.notify({
      title: title,
      text: 'Are you sure you want to do nothing?<div class="clearfix"></div><br><a class="btn btn-sm btn-white yes">Yes</a> <a class="btn btn-sm btn-danger no">No</a>',
      image: "<i class='"+icon+"'></i>"
    }, {
      style: 'metro',
      className: style,
      globalPosition:position,
      showAnimation: "show",
      showDuration: 0,
      hideDuration: 0,
      autoHide: false,
      clickToHide: false
    });
    //listen for click events from this style
    $(document).on('click', '.notifyjs-metro-base .no', function() {
      //programmatically trigger propogating hide event
      $(this).trigger('notify-hide');
    });
    $(document).on('click', '.notifyjs-metro-base .yes', function() {
      //show button text
      alert($(this).text() + " clicked!");
      //hide notification
      $(this).trigger('notify-hide');
    });
  },
  //init - examples
  Notification.prototype.init = function() {

  },
  //init
  $.Notification = new Notification, $.Notification.Constructor = Notification
}(window.jQuery),

/**
 * Components
 */
function($) {
  "use strict";

  var Components = function() {};

  //initializing tooltip
  Components.prototype.initTooltipPlugin = function() {
    $.fn.tooltip && $('[data-toggle="tooltip"]').tooltip()
  },

  //initializing popover
  Components.prototype.initPopoverPlugin = function() {
    $.fn.popover && $('[data-toggle="popover"]').popover()
  },

  //initializing custom modal
  Components.prototype.initCustomModalPlugin = function() {
    $('[data-plugin="custommodal"]').on('click', function(e) {
      Custombox.open({
        target: $(this).attr("href"),
        effect: $(this).attr("data-animation"),
        overlaySpeed: $(this).attr("data-overlaySpeed"),
        overlayColor: $(this).attr("data-overlayColor")
      });
      e.preventDefault();
    });
  },

  //initializing nicescroll
  Components.prototype.initNiceScrollPlugin = function() {
    //You can change the color of scroll bar here
    $.fn.niceScroll &&  $(".nicescroll").niceScroll({ cursorcolor: '#98a6ad',cursorwidth:'6px', cursorborderradius: '5px'});
  },

  //range slider
  Components.prototype.initRangeSlider = function() {
    $.fn.slider && $('[data-plugin="range-slider"]').slider({});
  },

  /* -------------
   * Form related controls
   */
  //switch
  Components.prototype.initSwitchery = function() {
    $('[data-plugin="switchery"]').each(function (idx, obj) {
      new Switchery($(this)[0], $(this).data());
    });
  },
  //multiselect
  Components.prototype.initMultiSelect = function() {
    if($('[data-plugin="multiselect"]').length > 0)
      $('[data-plugin="multiselect"]').multiSelect($(this).data());
  },

   /* -------------
   * small charts related widgets
   */
   //peity charts
   Components.prototype.initPeityCharts = function() {
    $('[data-plugin="peity-pie"]').each(function(idx, obj) {
      var colors = $(this).attr('data-colors')?$(this).attr('data-colors').split(","):[];
      var width = $(this).attr('data-width')?$(this).attr('data-width'):20; //default is 20
      var height = $(this).attr('data-height')?$(this).attr('data-height'):20; //default is 20
      $(this).peity("pie", {
        fill: colors,
        width: width,
        height: height
      });
    });
    //donut
     $('[data-plugin="peity-donut"]').each(function(idx, obj) {
      var colors = $(this).attr('data-colors')?$(this).attr('data-colors').split(","):[];
      var width = $(this).attr('data-width')?$(this).attr('data-width'):20; //default is 20
      var height = $(this).attr('data-height')?$(this).attr('data-height'):20; //default is 20
      $(this).peity("donut", {
        fill: colors,
        width: width,
        height: height
      });
    });

     $('[data-plugin="peity-donut-alt"]').each(function(idx, obj) {
      $(this).peity("donut");
    });

     // line
     $('[data-plugin="peity-line"]').each(function(idx, obj) {
      $(this).peity("line", $(this).data());
     });

     // bar
     $('[data-plugin="peity-bar"]').each(function(idx, obj) {
      var colors = $(this).attr('data-colors')?$(this).attr('data-colors').split(","):[];
      var width = $(this).attr('data-width')?$(this).attr('data-width'):20; //default is 20
      var height = $(this).attr('data-height')?$(this).attr('data-height'):20; //default is 20
      $(this).peity("bar", {
        fill: colors,
        width: width,
        height: height
      });
     });
   },



  //initilizing
  Components.prototype.init = function() {
    var $this = this;
    this.initTooltipPlugin(),
    this.initPopoverPlugin(),
    this.initNiceScrollPlugin(),
    this.initCustomModalPlugin(),
    this.initRangeSlider(),
    this.initSwitchery(),
    this.initMultiSelect(),
    this.initPeityCharts(),
    //creating portles
    $.Portlet.init();
  },

  $.Components = new Components, $.Components.Constructor = Components

}(window.jQuery),
  //initializing main application module
function($) {
  "use strict";
  $.Components.init();
}(window.jQuery);

/*
 * Mustache.js helpers
 */

function mustacheBuildOptions (object) {
  var validTypes = ['string', 'number', 'boolean'];
  var value;
  var key;
  for (key in object) {
    value = object[key];
    if (object.hasOwnProperty(key) && validTypes.indexOf(typeof value) !== -1) {
      object[key + '=' + value] = true;
    }
  }
  return object;
}

/*
 * Executed when partial is loaded
 */

function onPartialLoaded() {
  select2();
  bsTooltipsPopovers();
  ajaxForms();
  bindMediaBrowser();
  bindFormElements();
  bindTinyMce();
}

function bindTinyMce() {
  tinymce.init({
    selector: '.editor-basic',
    theme: 'modern',
    menubar:false,
    statusbar: false,
    plugins: 'link paste contextmenu textpattern autolink image code',
    selection_toolbar: 'bold italic | quicklink blockquote',
    toolbar: 'bold italic | bullist numlist | link image | code',
    inline: false,
    file_browser_callback: elFinderBrowser,
    relative_urls : false,
    remove_script_host : false,
    convert_urls : true,
    content_css : app_root + '/assets/css/tinymce.css'/*,
    forced_root_block : true*/
  });
}

/*
 * TinyMCE browser
 */

function elFinderBrowser (field_name, url, type, win) {
  tinyMCE.activeEditor.windowManager.open({
  file: app_root + '/elfinder/tinymce',
  title: 'Files',
  width: 940,
  height: 450,
  resizable: 'yes',
  inline: 'yes',  // This parameter only has an effect if you use the inlinepopups plugin!
  popup_css: false, // Disable TinyMCE's default popup CSS
  close_previous: 'no'
  }, {
  setUrl: function (url) {
    win.document.getElementById(field_name).value = url;
  }
  });
  return false;
}

/*
 * Form elements
 */

function bindFormElements()
{
  // Color Picker
  $('.colorpicker-default').colorpicker({
    format: 'hex'
  });

  // Date Picker
  $('.datepicker').datepicker();

  $('.datepicker-autoclose').datepicker({
    autoclose: true,
    format: "yyyy-mm-dd",
    todayHighlight: true
  });

  $('.datepicker-inline').datepicker();

  $('.datepicker-multiple-date').datepicker({
    format: "yyyy-mm-dd",
    clearBtn: true,
    multidate: true,
    multidateSeparator: ","
  });

  $('.date-range').datepicker({
    toggleActive: true,
    format: "yyyy-mm-dd",
    autoclose: true
  });

  // Date Range Picker
  $('.input-daterange-datepicker').daterangepicker({
    buttonClasses: ['btn', 'btn-sm'],
    applyClass: 'btn-default',
    cancelClass: 'btn-primary'
  });

  $('.input-daterange-timepicker').daterangepicker({
    timePicker: true,
    format: 'MM-DD-YYYY h:mm A',
    timePickerIncrement: 30,
    timePicker12Hour: true,
    timePickerSeconds: false,
    buttonClasses: ['btn', 'btn-sm'],
    applyClass: 'btn-default',
    cancelClass: 'btn-primary'
  });

  // Bootstrap TouchSpin
  $('.vertical-spin').TouchSpin({
    verticalbuttons: true,
    buttondown_class: 'btn btn-default',
    buttonup_class: 'btn btn-default',
    verticalupclass: 'fa fa-plus',
    verticaldownclass: 'fa fa-minus'
  });

  $('.vertical-spin-px').TouchSpin({
    verticalbuttons: true,
    buttondown_class: 'btn btn-default',
    buttonup_class: 'btn btn-default',
    verticalupclass: 'fa fa-plus',
    verticaldownclass: 'fa fa-minus',
    postfix: 'px'
  });

  var vspinTrue = $('.vertical-spin').TouchSpin({
   verticalbuttons: true
  });

  if (vspinTrue) {
    $('.vertical-spin').prev('.bootstrap-touchspin-prefix').remove();
  }
}

/*
 * File picker
 */

function bindMediaBrowser()
{
  $('[data-type~=image]').each(function()
  {
    var id = $(this).attr('data-id');
    var preview = $(this).attr('data-preview');
    preview = (typeof preview !== 'undefined' && preview !== false) ? preview : '';

    $(this).on('click', function() {
      $.colorbox({
        href: app_root + '/platform/media/picker?id=' + id + '&preview=' + preview,
        fastIframe: true,
        overlayClose: false,
        iframe: true,
        width: '90%',
        height: '90%'
      });
    });
  });

  $('[data-type~=image]').each(function()
  {
    var self = $(this);
    var image_input = $(this).attr('data-id');

    updateImagePreview(self);

    $('#' + image_input).on('change', function() {
      updateImagePreview(self);
    });
  });
}

function updateImagePreview(self)
{
  var image_input = $(self).attr('data-id');
  var filePath = $('#' + image_input).val();
  var previewButton = $(self).next();

  if (filePath != '')
  {
    var image = new Image();
    image.src = filePath;

    var extra_style = (filePath.match(/.svg$/)) ? 'min-height:64px;min-width:64px;': '';

    $(previewButton).removeClass('disabled');
    $(previewButton).attr('data-toggle', 'popover');
    $(previewButton).attr('data-original-title', null);
    $(previewButton).css('cursor', 'help');
    $(previewButton).attr('data-content', '<img src="' + filePath + '" style="max-width:240px;max-height:280px;' + extra_style + '">');

    $(previewButton).popover(
    {
      container: 'body',
      trigger: 'hover',
      template: '<div class="popover" role="tooltip"><div class="popover-arrow"></div><div class="popover-content" style="padding:0"></div></div>',
      html: true
    });
  }
}

// Callback after elfinder selection
window.processSelectedFile = function(filePath, requestingField, previewButton)
{
  $('#' + requestingField).val(decodeURI(filePath));

  if (previewButton != '')
  {
    var image = new Image();
    image.src = decodeURI(filePath);

    var extra_style = (filePath.match(/.svg$/)) ? 'min-height:64px;min-width:64px;': '';

    $('#' + previewButton).removeClass('disabled');
    $('#' + previewButton).attr('data-toggle', 'popover');
    $('#' + previewButton).attr('data-original-title', null);
    $('#' + previewButton).css('cursor', 'help');
    $('#' + previewButton).attr('data-content', '<img src="' + decodeURI(filePath) + '" style="max-width:240px;max-height:280px;' + extra_style + '">');

    $('[data-toggle~=popover]').popover(
    {
      container: 'body',
      trigger: 'hover',
      template: '<div class="popover" role="tooltip"><div class="popover-arrow"></div><div class="popover-content" style="padding:0"></div></div>',
      html: true
    });

    //bsTooltipsPopovers();
  }
}

/*
 * Ajax forms
 */

function ajaxForms()
{
  var ajax_form_opts = {
    dataType: 'json',
    beforeSerialize: beforeSerialize,
    success: formResponse,
    error: formResponse
  };

  $('form.ajax').validator({
    feedback: {
      success: 'fa fa-check',
      error: 'fa fa-times'
    }
  }).on('submit', function (e) {
    if (! e.isDefaultPrevented()) 
    {
      $('form.ajax').ajaxSubmit(ajax_form_opts);
      e.preventDefault();
    }
  });
}

function beforeSerialize($jqForm, options)
{
  ladda_button = $jqForm.find('[type=submit]').ladda();

  // Loading state
  ladda_button.ladda('start');

  // TinyMCE if available
  if (typeof tinymce !== 'undefined') tinymce.triggerSave();
}

function formResponse(responseText, statusText, xhr, $jqForm)
{
  if (typeof responseText.fn !== 'undefined')
  {
    var fn = window[responseText.fn];
    if(typeof fn === 'function') {
        fn(responseText);
        return;
    }

    // Reset form
    var reset_form = (typeof responseText.reset !== 'undefined') ? responseText.reset : true;

    if (reset_form) 
    {
      $jqForm[0].reset();
    }
    else
    {
      $('[type=password]').val('');
    }

    // Loading state
    ladda_button.ladda('stop');
  }
  else if (typeof responseText.redir !== 'undefined' && responseText.redir == 'reload')
  {
    document.location.reload();
  }
  else if (typeof responseText.redir !== 'undefined')
  {
    document.location = responseText.redir;
  }
  else if (typeof responseText.msg !== 'undefined')
  {
    swal({
      type: responseText.type,
      title: responseText.msg
    }, function() {
  
      // Reset form
      var reset_form = (typeof responseText.reset !== 'undefined') ? responseText.reset : true;

      if (reset_form) 
      {
        $jqForm[0].reset();
      }
      else
      {
        $('[type=password]').val('');
      }

      // Loading state
      ladda_button.ladda('stop');
    });
  }
}

/*
 * BlockUI
 */

function blockUI(el)
{
  if (typeof el === 'undefined')
  {
    $.blockUI(
    {
      message: '<div class="loader"></div>',
      fadeIn: 0,
      fadeOut: 100,
      baseZ: 21000,
      overlayCSS:
      {
        backgroundColor: '#000'
      },
      css:
      {
        border: 'none',
        padding: '0',
        backgroundColor: 'transparant',
        opacity: 1,
        color: '#fff'
      }
    });
  }
  else
  {
    $(el).block(
    {
      message: '<div class="loader loader-xs"></div>',
      fadeIn: 0,
      fadeOut: 100,
      baseZ: 21000,
      overlayCSS:
      {
        backgroundColor: '#000'
      },
      css:
      {
        border: 'none',
        padding: '0',
        backgroundColor: 'transparant',
        opacity: 1,
        color: '#fff'
      }
    });
  }
}

/*
 * unblockUI
 */

function unblockUI(el)
{
  if (typeof el === 'undefined')
  {
    $.unblockUI();
  }
  else
  {
    $(el).unblock();
  }
}

/*
 * Show "Saved" notification in top bar
 */

function showSaved()
{
  $.Notification.notify('success', 'top right', _lang["saved"], _lang["changes_saved"]);
}

/*
 * Toggle password text visibility
 */

function togglePassword(field_name, classes, show)
{
  if (show)
  {
    var pwd = $('#' + field_name).val();
    $('#' + field_name).attr('id', field_name + '2');
    $('#' + field_name + '2').after($('<input id="' + field_name + '" name="' + field_name + '" class="' + classes + '" autocapitalize="off" autocorrect="off" autocomplete="off" type="text">'));
    $('#' + field_name + '2').remove();
    $('#' + field_name).val(pwd);
  }
  else
  {
    var pwd = $('#' + field_name).val();
    $('#' + field_name).attr('id', field_name + '2');
    $('#' + field_name + '2').after($('<input id="' + field_name + '" name="' + field_name + '" class="' + classes + '" type="password">'));
    $('#' + field_name + '2').remove();
    $('#' + field_name).val(pwd);
  }
}

/*
 * Select2
 */

function format_fonts(font) {
  if(!font.id) return font.text; // optgroup
  var $font = $(
    '<span style="font-family:\'' + font.text + '\'"> ' + font.text + '</span>'
  );
  return $font;
}

function select2()
{
	if (jQuery().select2)
	{
		$('.select2').select2({
			allowClear: true
		});
	
		$('.select2-required').select2({
			allowClear: false
		});
	
		$('.select2-tags').select2({
			tags: [],
			tokenSeparators: [',', ';', ' ']
		}); 

		$('.font-picker').select2({
      templateResult: format_fonts,
      templateSelection: format_fonts
		});

		var select2_choices;
	
		$('.select2-datalist').select2({
      allowClear: false
    })
    .on('select2:close', function() {
      var el = $(this);
      if(el.val() === "NEW") {
        var title = el.attr('data-title');
        var post_url = el.attr('data-post');
        var csrf_token = el.attr('data-token');

        swal({
          title: title,
          type: "input",
          showCancelButton: true,
          closeOnConfirm: false,
          animation: "slide-from-top"
        },
        function(inputValue) {
          if (inputValue === false) {
            el.val('').trigger("change");
            return false;
          }

          if (inputValue === "") {
            swal.showInputError(_lang['input_required']);
            return false;
          }

          var request = $.ajax({
            url: post_url,
            type: 'post',
            data: {inputValue: inputValue, _token: csrf_token},
            dataType: 'json'
          });

          request.done(function(json) {
            swal.close();
            if (typeof json.id !== null) {
              el.append('<option value="' + json.id + '">' + inputValue + '</option>')
                .val(json.id);
            } else {
              el.append('<option value="' + inputValue + '">' + inputValue + '</option>')
                .val(inputValue);
            }
          });

          request.fail(function(jqXHR, textStatus) {
            swal.close();
            alert('Request failed, please try again (' + textStatus + ')');
          });

          return false;
        });
      }
    });
	
		$('.select2-multiple').select2();

    $('.select2-multiple-spots').select2({
      templateResult: function(result) {
        if (!result.id) return result.text;

        var type = result.element.getAttribute('data-type');

        if (type == 'geofence') return $('<span><i class="fa fa-map-marker"></i> ' + result.text + '</span>');
        if (type == 'beacon') return $('<span><i class="fa fa-dot-circle-o"></i> ' + result.text + '</span>');
      },
      templateSelection: function(result) {
        if (!result.id) return result.text;

        var type = result.element.getAttribute('data-type');

        if (type == 'geofence') return $('<span><i class="fa fa-map-marker"></i> ' + result.text + '</span>');
        if (type == 'beacon') return $('<span><i class="fa fa-dot-circle-o"></i> ' + result.text + '</span>');
      }
    });
	}
}

/*
 * DataTable loaded event
 */

function onDataTableLoad()
{
  /*
   * moment.js
   */

  $('[data-moment=fromNowDateTime]').each(function(index, value)
  {
    var date = $(this).text();

    if (moment(date, 'YYYY-MM-DD HH:mm:ss').isValid())
    {
      $(this).html('<abbr data-toggle="tooltip" title="' + moment(date).format(_lang['date_time_notation']) + '">' + moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow() + '</abbr>');
    }
  });

  /*
   * Bootstrap Tooltips, Popovers and AJAX Popovers
   */

  bsTooltipsPopovers();
}

/*
 * Bootstrap Tooltips, Popovers and AJAX Popovers
 */

function bsTooltipsPopovers()
{
  $('[data-toggle~=tooltip]').tooltip(
  {
    container: 'body'
  });

  $('[data-toggle~=popover]').popover(
  {
    container: 'body',
    html: true
  });

  // Hide popovers and tooltips when clicking outside
  $('body').on('click', function(e) {
  // Hide tooltips when clicking link with tooltip
  $('[data-toggle~=tooltip]').each(function() {
    $(this).tooltip('hide');
  });
  $('.tooltip').remove();
  });
}

/*
 * Generate random string
 */

function randomString(string_length)
{
  if (typeof string_length === 'undefined') string_length = 8;
  var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
  var randomstring = '';
  for (var i = 0; i < string_length; i++)
  {
      var rnum = Math.floor(Math.random() * chars.length);
      randomstring += chars.substring(rnum, rnum + 1);
  }
  return randomstring;
}

/*
 * Generate random number code
 */

function randomCode(string_length)
{
  if (typeof string_length === 'undefined') string_length = 8;
  var chars = "0123456789";
  var randomstring = '';
  for (var i = 0; i < string_length; i++)
  {
      var rnum = Math.floor(Math.random() * chars.length);
      randomstring += chars.substring(rnum, rnum + 1);
  }
  return randomstring;
}

/*
 * Ajax click
 */

function _confirm(url, data, verb, callback, msg)
{
  if (typeof msg === 'undefined') msg = _lang['confirm'];

  swal(
  {
      title: msg,
      type: "warning",
      showCancelButton: true,
      cancelButtonText: _lang['cancel'],
      confirmButtonColor: "#DD6B55",
      confirmButtonText: _lang['yes']
  },
  function()
  {
      _click(url, data, verb, callback);
  });
};

function _click(url, data, verb, callback)
{
  var callback_arg1 = arguments[4];
  var callback_arg2 = arguments[5];

  var request = $.ajax(
  {
      url: url,
      type: verb,
      data: data,
      dataType: 'json'
  });

  request.done(function(json)
  {
      callback(callback_arg1, callback_arg2, json);
  });

  request.fail(function(jqXHR, textStatus)
  {
      alert('Request failed, please try again (' + textStatus + ')');
  });
};