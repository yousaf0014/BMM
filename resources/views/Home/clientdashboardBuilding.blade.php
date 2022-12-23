@extends('layouts.admin.app')
@section('content')

<div class="container-fluid">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table mr-1"></i>
            User 
        </div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }} You are logged in!
                </div>
            @endif
            <div class="box-typical box-typical-padding">
                    
                <div class="form-group row">
                    <label class="col-sm-2 form-control-label" style="color: #000000;font-weight: bold;">Name:</label>
                    <div class="col-sm-10" style="color: #000000;">
                        {{$user->first_name.' '.$user->last_name}}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 form-control-label" style="color: #000000;font-weight: bold;">Email:</label>
                    <div class="col-sm-10" style="color: #000000;">
                        {{$user->email}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-12  layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">                                
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>Search Form</h4>
                    </div>     
                </div>
            </div>
            <div class="widget-content widget-content-area">

                <div class="row">
                    <div class="col-xl-5">
                        <div class="form-group">
                            <input type="text" class="form-control form-control-lg" placeholder="Start Date" id="dateTimeFlatpickr" />
                        </div>
                    </div>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <input type="text" class="form-control form-control-lg" placeholder="End Date" id="dateTimeFlatpickr1" />
                        </div>
                    </div>
                    
                    <div class="col-xl-2">
                        <a href="javascript:{}" class="btn btn-primary btn-sm" onclick="loadReport();">
                            <div class="icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    
    </div>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table mr-1"></i>
            Tracking Report
        </div>
        <div class="card-body">
            <div class="box-typical box-typical-padding">
                <div class="form-group row">
                    <div id="colorsinfo" style="display:flex;"></div>
                    <div id="loadingArea"style="display:none"  class="text-center">Loading...</div>
                    <style>div.google-visualization-tooltip { width:300px; }</style>
                    <div id="chart_div" class="col-lg-12 col-xl-12"></div>
                    <div class="clearfix"></div>
                    <div id="img_div" style="position: fixed; top: 0; right: 0; z-index: 10; border: 1px solid #b9b9b9">
                          Image will be placed here
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('css')
  <link href="{!! asset('plugins/flatpickr/flatpickr.css')!!}" rel="stylesheet" type="text/css" />
@endsection
@section('scripts')
<script src="{!! asset('plugins/flatpickr/flatpickr.js')!!}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/canvg/1.5/canvg.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>


<script>
    var f1 = flatpickr(document.getElementById('dateTimeFlatpickr'), {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
    });
    var f2 = flatpickr(document.getElementById('dateTimeFlatpickr1'), {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
    }); 

    function  loadReport(){
        var date1 = $('#dateTimeFlatpickr').val();
        var date2 = $('#dateTimeFlatpickr1').val();
        $("#loadingDiv").show();
        $.ajax({
          method: "GET",
          url: "{{url('attendanceAjax')}}",
          data: { startDate: date1, endDate: date2 }
            }).done(function( data ) {
                $("#loadingArea").hide();
                drawGoogleChart(data);
          });
    }

    function onlyUnique(value, index, self) { 
        return self.indexOf(value) === index;
    }
    function drawGoogleChart(data){
        var d = JSON.parse(data);             
        var fArr = [];
        var ucolors = [];
        var colors = [];
        var zones = [];
        for(i=0; i<d.length; i++){
          zones.push(d[i][0]);
          var zName = d[i][0];
          var name = d[i][1];
          var inTime = d[i][2];                   
          //my_date = inTime.replace(/-/g, "/");
          inTime = new Date(inTime);

          var outTime = d[i][3];
          //my_date = outTime.replace(/-/g, "/");
          outTime = new Date(outTime);
          var arr = [zName,name,inTime,outTime];
          fArr.push(arr);
          var c = '#'+ d[i][4];  
          ucolors[name] = c;
        }
        var unique = zones.filter( onlyUnique );          
        $('#colorsinfo ').html('');
        for (const key of Object.keys(ucolors)) {
            //var htm ='<div class="input-color"><input style="width:125px" readonly="readonly" type="text" value="'+key+'" /><div class="color-box" style="background-color:'+ucolors[key]+';"></div></div>';
                //$('#colorsinfo ').append(htm);
            colors.push(ucolors[key]);
        }
        
        google.charts.load("current", {packages:["timeline"]});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            console.log(unique);
                var container = document.getElementById('chart_div');
                var chart = new google.visualization.Timeline(container);
                var dataTable = new google.visualization.DataTable(fArr);
                dataTable.addColumn({ type: 'string', id: 'Department' });
                dataTable.addColumn({ type: 'string', id: 'Name' });
                dataTable.addColumn({ type: 'date', id: 'Start' });
                dataTable.addColumn({ type: 'date', id: 'End' });
                dataTable.addRows(fArr);
//colors: colors
                var options = {
                      height:height = unique.length * 73 + 30,
                      legend: 'none',
                      colors: colors      
                    };

                chart.draw(dataTable,options);
                $('html, body').animate({
                    scrollTop: $("#chart_div").offset().top-100
                }, 2000);
          }
        

    }
</script>
@endsection