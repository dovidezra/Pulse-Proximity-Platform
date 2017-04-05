<div class="container">
<div class="row m-t">
  <div class="col-sm-6">
<?php
if (count($campaigns) == 0) {
?>
    <div class="card-box">
      <h1>{{ trans('global.no_data_found') }} </h1>
    </div>

<?php
} else { 
?>
    <div class="card-box" style="padding:13px">
      <select id="campaigns" class="select2-required">
<?php
echo '<option value="">' . trans('global.all_campaigns') . '</option>';

foreach($campaigns as $key => $row) {
  $selected = ($row['id'] == $campaign_id) ? ' selected' : '';
  echo '<option value="' . $key . '"' . $selected . '>' . $row['name'] . '</option>';
}
?>
      </select>
<script>
$('#campaigns').on('change', function() {
  document.location = ($(this).val() == '') ? '#/campaign/analytics/<?php echo $date_start ?>/<?php echo $date_end ?>' : '#/campaign/analytics/<?php echo $date_start ?>/<?php echo $date_end ?>/' + $(this).val();
});
</script>
    </div>
  </div>
  <div class="col-sm-6 text-center m-b-20">
      <div class="form-control" id="reportrange" style="cursor:pointer;padding:20px; width:100%; display:table"> <i class="fa fa-calendar" style="margin:0 10px 0 0"></i> <span></span> </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-12">

      <div class="card-box">
        <h3 class="page-title">{{ trans('global.views') }}</h3>
        <div id="combine-chart">
          <div id="combine-chart-container" class="flot-chart" style="height: 320px;"> </div>
        </div>
      </div>

  </div>
</div>

<script>
$('#reportrange span').html(moment('<?php echo $date_start ?>').format('MMMM D, YYYY') + ' - ' + moment('<?php echo $date_end ?>').format('MMMM D, YYYY'));

$('#reportrange').daterangepicker({
  format: 'MM-DD-YYYY',
  startDate: moment('<?php echo $date_start ?>').format('MM-D-YYYY'),
  endDate: moment('<?php echo $date_end ?>').format('MM-D-YYYY'),
  minDate: moment('<?php echo $first_date ?>').format('MM-D-YYYY'),
  maxDate: '<?php echo date('m/d/Y') ?>',
  dateLimit: {
      days: 60
  },
  showDropdowns: true,
  showWeekNumbers: true,
  timePicker: false,
  timePickerIncrement: 1,
  timePicker12Hour: true,
  ranges: {
   '<?php echo trans('global.today') ?>': [ moment(), moment() ],
   '<?php echo trans('global.yesterday') ?>': [ moment().subtract(1, 'days'), moment().subtract(1, 'days') ],
   '<?php echo trans('global.last_7_days') ?>': [ moment().subtract(6, 'days'), moment() ],
   '<?php echo trans('global.last_30_days') ?>': [ moment().subtract(29, 'days'), moment() ],
   '<?php echo trans('global.this_month') ?>': [ moment().startOf('month'), moment().endOf('month') ],
   '<?php echo trans('global.last_month') ?>': [ moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month') ]
  },

  opens: 'left',
  drops: 'down',
  buttonClasses: ['btn', 'btn-sm'],
  applyClass: 'btn-primary',
  cancelClass: 'btn-inverse',
  separator: ' {{ strtolower(trans('global.to')) }} ',
  locale: {
    applyLabel: '<?php echo trans('global.submit') ?>',
    cancelLabel: '<?php echo trans('global.reset') ?>',
    fromLabel: '<?php echo trans('global.date_from') ?>',
    toLabel: '<?php echo trans('global.date_to') ?>',
    customRangeLabel: '<?php echo trans('global.custom_range') ?>',
    daysOfWeek: ['<?php echo trans('global.su') ?>', '<?php echo trans('global.mo') ?>', '<?php echo trans('global.tu') ?>', '<?php echo trans('global.we') ?>', '<?php echo trans('global.th') ?>', '<?php echo trans('global.fr') ?>','<?php echo trans('global.sa') ?>'],
      monthNames: ['<?php echo trans('global.january') ?>', '<?php echo trans('global.february') ?>', '<?php echo trans('global.march') ?>', '<?php echo trans('global.april') ?>', '<?php echo trans('global.may') ?>', '<?php echo trans('global.june') ?>', '<?php echo trans('global.july') ?>', '<?php echo trans('global.august') ?>', '<?php echo trans('global.september') ?>', '<?php echo trans('global.october') ?>', '<?php echo trans('global.november') ?>', '<?php echo trans('global.december') ?>'],
      firstDay: 1
  }
});

$('#reportrange').on('apply.daterangepicker', function(ev, picker) {
  $('#reportrange span').html(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
  var start = picker.startDate.format('YYYY-MM-DD');
  var end = picker.endDate.format('YYYY-MM-DD');

  var sl = '{{ $sl }}';
  document.location = (sl == '') ? '#/campaign/analytics/' + start + '/' + end : '#/campaign/analytics/' + start + '/' + end + '/' + sl;
});


</script>
<?php } ?>