@extends('layouts.default-profile')



<?php $whereAmI='profile-index' ?>
@section('style')
<link rel="stylesheet" href="<?php echo URL::asset('css/profile/index.css')?>" type="text/css">
@stop
@section('script')
    <script src="<?php echo URL::asset('js/profile/amchart/amcharts/amcharts.js')?>" type="text/javascript"></script>
  	<script src="<?php echo URL::asset('js/profile/amchart/amcharts/serial.js')?>" type="text/javascript"></script>
    <script>

      var chartUser;
      var chartMedia;
      var chartUserData = {!! $data['mainEntry'] !!};
      var chartMediaData = {!! $data['mediaEntry'] !!};


      var chart = AmCharts.makeChart("chartdiv", {
    "type": "serial",
    "theme": "light",
    "legend": {
        "equalWidths": false,
        "useGraphSettings": true,
        "valueAlign": "left",
        "valueWidth": 120
    },
    "dataProvider": [{
        "date": "2012-01-01",
        "distance": 227,
        "townName": "New York",
        "townName2": "New York",
        "townSize": 25,
        "latitude": 40.71,
        "duration": 408
    }, {
        "date": "2012-01-02",
        "distance": 371,
        "townName": "Washington",
        "townSize": 14,
        "latitude": 38.89,
        "duration": 482
    }, {
        "date": "2012-01-03",
        "distance": 433,
        "townName": "Wilmington",
        "townSize": 6,
        "latitude": 34.22,
        "duration": 562
    }, {
        "date": "2012-01-04",
        "distance": 345,
        "townName": "Jacksonville",
        "townSize": 7,
        "latitude": 30.35,
        "duration": 379
    }, {
        "date": "2012-01-05",
        "distance": 480,
        "townName": "Miami",
        "townName2": "Miami",
        "townSize": 10,
        "latitude": 25.83,
        "duration": 501
    }, {
        "date": "2012-01-06",
        "distance": 386,
        "townName": "Tallahassee",
        "townSize": 7,
        "latitude": 30.46,
        "duration": 443
    }, {
        "date": "2012-01-07",
        "distance": 348,
        "townName": "New Orleans",
        "townSize": 10,
        "latitude": 29.94,
        "duration": 405
    }, {
        "date": "2012-01-08",
        "distance": 238,
        "townName": "Houston",
        "townName2": "Houston",
        "townSize": 16,
        "latitude": 29.76,
        "duration": 309
    }, {
        "date": "2012-01-09",
        "distance": 218,
        "townName": "Dalas",
        "townSize": 17,
        "latitude": 32.8,
        "duration": 287
    }, {
        "date": "2012-01-10",
        "distance": 349,
        "townName": "Oklahoma City",
        "townSize": 11,
        "latitude": 35.49,
        "duration": 485
    }, {
        "date": "2012-01-11",
        "distance": 603,
        "townName": "Kansas City",
        "townSize": 10,
        "latitude": 39.1,
        "duration": 890
    }, {
        "date": "2012-01-12",
        "distance": 534,
        "townName": "Denver",
        "townName2": "Denver",
        "townSize": 18,
        "latitude": 39.74,
        "duration": 810
    }, {
        "date": "2012-01-13",
        "townName": "Salt Lake City",
        "townSize": 12,
        "distance": 425,
        "duration": 670,
        "latitude": 40.75,
        "dashLength": 8,
        "alpha": 0.4
    }, {
        "date": "2012-01-14",
        "latitude": 36.1,
        "duration": 470,
        "townName": "Las Vegas",
        "townName2": "Las Vegas"
    }, {
        "date": "2012-01-15"
    }, {
        "date": "2012-01-16"
    }, {
        "date": "2012-01-17"
    }, {
        "date": "2012-01-18"
    }, {
        "date": "2012-01-19"
    }],
    "valueAxes": [{
        "id": "distanceAxis",
        "axisAlpha": 0,
        "gridAlpha": 0,
        "position": "left",
        "title": "distance"
    }, {
        "id": "latitudeAxis",
        "axisAlpha": 0,
        "gridAlpha": 0,
        "labelsEnabled": false,
        "position": "right"
    }, {
        "id": "durationAxis",
        "duration": "mm",
        "durationUnits": {
            "hh": "h ",
            "mm": "min"
        },
        "axisAlpha": 0,
        "gridAlpha": 0,
        "inside": true,
        "position": "right",
        "title": "duration"
    }],
    "graphs": [{
        "alphaField": "alpha",
        "balloonText": "[[value]] miles",
        "dashLengthField": "dashLength",
        "fillAlphas": 0.7,
        "legendPeriodValueText": "total: [[value.sum]] mi",
        "legendValueText": "[[value]] mi",
        "title": "distance",
        "type": "column",
        "valueField": "distance",
        "valueAxis": "distanceAxis"
    }, {
        "balloonText": "latitude:[[value]]",
        "bullet": "round",
        "bulletBorderAlpha": 1,
        "useLineColorForBulletBorder": true,
        "bulletColor": "#FFFFFF",
        "bulletSizeField": "townSize",
        "dashLengthField": "dashLength",
        "descriptionField": "townName",
        "labelPosition": "right",
        "labelText": "[[townName2]]",
        "legendValueText": "[[value]]/[[description]]",
        "title": "latitude/city",
        "fillAlphas": 0,
        "valueField": "latitude",
        "valueAxis": "latitudeAxis"
    }, {
        "bullet": "square",
        "bulletBorderAlpha": 1,
        "bulletBorderThickness": 1,
        "dashLengthField": "dashLength",
        "legendValueText": "[[value]]",
        "title": "duration",
        "fillAlphas": 0,
        "valueField": "duration",
        "valueAxis": "durationAxis"
    }],
    "chartCursor": {
        "categoryBalloonDateFormat": "DD",
        "cursorAlpha": 0.1,
        "cursorColor":"#000000",
         "fullWidth":true,
        "valueBalloonsEnabled": false,
        "zoomable": false
    },
    "dataDateFormat": "YYYY-MM-DD",
    "categoryField": "date",
    "categoryAxis": {
        "dateFormats": [{
            "period": "DD",
            "format": "DD"
        }, {
            "period": "WW",
            "format": "MMM DD"
        }, {
            "period": "MM",
            "format": "MMM"
        }, {
            "period": "YYYY",
            "format": "YYYY"
        }],
        "parseDates": true,
        "autoGridCount": false,
        "axisColor": "#555555",
        "gridAlpha": 0.1,
        "gridColor": "#FFFFFF",
        "gridCount": 50
    },
    "export": {
    	"enabled": true
     }
});


      AmCharts.ready(function () {

            chartUser = new AmCharts.AmSerialChart();
            chartUser.dataProvider = chartUserData;
            chartUser.categoryField = "date";

            var graph1 = new AmCharts.AmGraph();
            graph1.title = "بازدید از کانال شما در یک ماه اخیر";
            graph1.valueField = "value";
            graph1.bullet = "round";
            graph1.hideBulletsCount = 30;
            graph1.lineColor = "#00f0f0";
            graph1.bulletBorderThickness = 1;
            chartUser.addGraph(graph1);

            var chartCursor2 = new AmCharts.ChartCursor();
            chartCursor2.cursorAlpha = 0.1;
            chartCursor2.fullWidth = true;
            chartCursor2.valueLineBalloonEnabled = true;
            chartUser.addChartCursor(chartCursor2);

            chartUser.write("user-chart");






            chartMedia = new AmCharts.AmSerialChart();
            chartMedia.dataProvider = chartMediaData;
            chartMedia.categoryField = "date";
            chartMedia.synchronizeGrid = true;
            chartMedia.ignoreZoomed = false;
            chartMedia.legend = {"useGraphSettings": true};
            var graph1 = new AmCharts.AmGraph();
            graph1.title = "تعداد نمایش ویدیو ها";
            graph1.valueField = "video";
            graph1.bullet = "round";
            graph1.hideBulletsCount = 30;
            graph1.lineColor = "#e69900";
            graph1.bulletBorderThickness = 1;
            chartMedia.addGraph(graph1);

            var graph1 = new AmCharts.AmGraph();
            graph1.title = "تعداد نمایش موسیقی ها";
            graph1.valueField = "music";
            graph1.bullet = "round";
            graph1.hideBulletsCount = 30;
            graph1.lineColor = "#992600";
            graph1.bulletBorderThickness = 1;
            chartMedia.addGraph(graph1);

            var graph1 = new AmCharts.AmGraph();
            graph1.title = "تعداد نمایش کتاب ها";
            graph1.valueField = "ebook";
            graph1.bullet = "round";
            graph1.hideBulletsCount = 30;
            graph1.lineColor = "#001cc6";
            graph1.bulletBorderThickness = 1;
            chartMedia.addGraph(graph1);

            var chartCursor2 = new AmCharts.ChartCursor();
            chartCursor2.cursorAlpha = 0.1;
            chartCursor2.fullWidth = true;
            chartCursor2.valueLineBalloonEnabled = true;
            chartMedia.addChartCursor(chartCursor2);

            chartMedia.write("media-chart");

         });

    </script>
@stop

@section('content')
  <h2>آمار بازدید از کانال شما در یک ماه اخیر</h2>
  <div id="user-chart"></div>
  <h2>آمار بازدید از رسانه های شما در هفته اخیر</h2>
  <div id="media-chart"></div>
  <div class="total-info">
    <div class="col-xs-12 col-sm-3">
      <div class="btn-info-video">
        <h2>تعداد ویدیو ها</h2>
        <span>{{$data['video']}}</span>
      </div>
    </div>
    <div class="col-xs-12 col-sm-3">
      <div class="btn-info-audio">
        <h2>تعداد موسیقی ها</h2>
        <span>{{$data['music']}}</span>
      </div>
    </div>
    <div class="col-xs-12 col-sm-3">
      <div class="btn-info-book">
        <h2>تعداد کتاب ها</h2>
        <span>{{$data['ebook']}}</span>
      </div>
    </div>
    <div class="col-xs-12 col-sm-3">
      <div class="btn-info-msg">
        <h2>رسانه شاخص</h2>
        <span>
          @if(@$data['top']->hash)
            <a href="{{url('s/'.$data['top']->hash.'/'.\App\Http\handyHelpers::UE($data['top']->title))}}" title="{{$data['top']->title}}">{{$data['top']->title}}</a>
          @else
            مشخص نشده است.
          @endif
        </span>
      </div>
    </div>
  </div>
@stop
