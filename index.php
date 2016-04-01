<!DOCTYPE html>
<html lang="en">
<head>
  <title>Homework 8</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
  
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
  <script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="moment.js"></script>
<script src="moment-timezone.js"></script>
	<script>
  	$(function() {
  		function round(value){
  			return Number(Math.round(value+'e2')+'e-2');
  		}
  		$("#stockname").keyup(function(){
		$("#stockname")
			.focus().autocomplete({
				source: function(request,response) {
					$.ajax({
						url: "http://dev.markitondemand.com/api/v2/Lookup/jsonp",
						dataType: "jsonp",
						data: {
							input: request.term
						},
						success: function(data) {
							response( $.map(data, function(suggestion) {
								return {
									label: suggestion.Symbol + " - " + suggestion.Name + " (" +suggestion.Exchange+ ")",
									value: suggestion.Symbol
								}
							}));
							
						}
					});
				},
				minLength: 0
			});
		});

			$("#getquote").click(function(event){
				event.preventDefault();
				var str = $("#stockname").val();
				$.ajax({
					url: "http://dev.markitondemand.com/api/v2/Lookup/jsonp",
					dataType: "jsonp",
					data: {
						input: str
					},
					success: function(data){
						if(data.length == 0){

							$("#insertText").text("Select a valid entry").css("color","red").css("font-size","13px").css("padding-top","10px");
						}
						else{
							$("#insertText").text("");
							$(".carousel").carousel(1);
							$("#myCarousel").carousel("pause");
							
							var cacheHold = '';
							$.get("handle.php",{sym:str},function(data){
								//var temp = $.parseJSON(data);
								if($("#checkit").hasClass('active')){
								cacheHold += "<br/><br/><table class='table table-striped'><tr><td>Name</td><td>"+data['Name']+"</td></tr>";
								cacheHold += "<tr><td>Symbol</td><td>"+data['Symbol']+"</td></tr>";
								var lp = round(data['LastPrice']);
								cacheHold += "<tr><td>Last Price</td><td>"+"$ "+lp+"</td></tr>";
								var chng = round(data['Change']);
								var chngper = round(data['ChangePercent']);

								if(chngper > 0){
									cacheHold += "<tr><td>Change (Change Percent)</td><td class='colorGreen'>"+chng+" ( "+chngper+"% )   <img src='up.png'/></td></tr>";
								}

								else if(chngper <0){
									cacheHold += "<tr><td>Change (Change Percent)</td><td class='colorRed'>"+chng+" ( "+chngper+"% )   <img src='down.png'/></td></tr>";
								}

								else{
									cacheHold += "<tr><td>Change (Change Percent)</td><td>"+chng+" ( "+chngper+"% )</td></tr>";
								}
								var timeStamp = data['Timestamp'];
								timeStamp = Date.parse(timeStamp);

								var localTime  = moment.utc(timeStamp).toDate();
    							localTime = moment(localTime).format('DD MMMM YYYY, HH:mm:ss a');
    							


								cacheHold += "<tr><td>Time and Date</td><td>"+localTime+"</td></tr>";

								var mCap = data['MarketCap'];
								if(mCap >= 10000000){
									mCap = mCap/1000000000;
									mCap = round(mCap);
									mCap+=" Billion";
								}
								else if(mCap >= 10000){
									mCap = mCap/1000000;
									mCap = round(mCap);
									mCap+=" Million";
								}
								else{
									mCap = round(mCap);
								}
								cacheHold += "<tr><td>Market Cap</td><td>"+mCap+"</td></tr>";
								cacheHold += "<tr><td>Volume</td><td>"+data['Volume']+"</td></tr>";

								var chngytd = round(data['ChangeYTD']);
								var chngperytd = round(data['ChangePercentYTD']);

								if(chngperytd > 0){
									
									cacheHold += "<tr><td>Change YTD (Change Percent YTD)</td><td class='colorGreen'>"+chngytd+" ( "+chngperytd+"% )   <img src='up.png'/></td></tr>";
							
								}

								else if(chngperytd <0){
									cacheHold += "<tr><td>Change YTD (Change Percent YTD)</td><td class='colorRed'>"+chngytd+" ( "+chngperytd+"% )   <img src='down.png'/></td></tr>";
								}
								else{
									cacheHold += "<tr><td>Change YTD (Change Percent YTD)</td><td>"+chngytd+" ( "+chngperytd+"% )</td></tr>";

								}
								var high = round(data['High']);
								var low = round(data['Low']);
								var open = round(data['Open']);
								cacheHold += "<tr><td>High Price</td><td>$ "+high+"</td></tr>";
								cacheHold += "<tr><td>Low Price</td><td>$ "+low+"</td></tr>";
								cacheHold += "<tr><td>Opening Price</td><td>$ "+open+"</td></tr>";
								cacheHold += "</table>";
								$("#histChart").addClass('hidden');
								$("#quoteTableIn").html(cacheHold);

								var chartReq = "<img src='http://chart.finance.yahoo.com/t?s="+data['Symbol']+"&lang=en-US&width=500&height=300'/> ";
								$("#quoteTableChart").html(chartReq);
								}
								if($("#checkit1").hasClass('active')){
									
									function getInputParams(){
    									return {  
        									Normalized: false,
        									NumberOfDays: 1095,
        									DataPeriod: "Day",
        									Elements: [
            								{
                							Symbol: data['Symbol'],
                							Type: "price",
                							Params: ["ohlc"]
            								}
        								]
    }
}

function _fixDate(dateIn) {
    var dat = new Date(dateIn);
    return Date.UTC(dat.getFullYear(), dat.getMonth(), dat.getDate());
}

function _getOHLC(json) {
    var dates = json.Dates || [];
    var elements = json.Elements || [];
    var chartSeries = [];

    if (elements[0]){

        for (var i = 0, datLen = dates.length; i < datLen; i++) {
            var dat = _fixDate(dates[i]);
            var pointData = [
                dat,
                elements[0].DataSeries['close'].values[i]
            ];
            chartSeries.push( pointData );
        };
    }
    return chartSeries;
}

function render(data_t) {
    var ohlc = _getOHLC(data_t);
    $('#chartHist').highcharts('StockChart', {
            rangeSelector : {
            	buttons: [{
									type: 'week',
									count: 1,
									text: '1w'
								},{
									type: 'month',
									count: 1,
									text: '1m'
								}, {
									type: 'month',
									count: 3,
									text: '3m'
								}, {
									type: 'month',
									count: 6,
									text: '6m'
								}, {
									type: 'ytd',
									text: 'YTD'
								}, {
									type: 'year',
									count: 1,
									text: '1y'
								}, {
									type: 'all',
									text: 'All'
							}],
            	inputEnabled: false,
                selected : 0
            },
            title : {
                text : data['Symbol']+' Stock Price'
            },
            
            yAxis: {
            allowDecimals: false,
            title: {
                text: 'Stock Value'
            }
          
        },

        exporting: {
        	enabled: false
        },

            series : [{
                name : data['Symbol']+' Stock Price',
                data : ohlc,
                type : 'area',
                threshold : null,
                tooltip : {
                    valueDecimals : 2
                },
                lineWidth: 0,
                fillColor : {
                    linearGradient : {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops : [
                        [0, Highcharts.getOptions().colors[0]],
                        [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                    ]
                }
            }]
      
    });
}
				function PlotChartt(){
    
    var params = {
        parameters: JSON.stringify(getInputParams())
    }
                $("#quoteTable").addClass('hidden');
                                       // $("#chartHist").addClass("col-lg-12");
            			
                        	$("#histChart").removeClass('hidden');
                    
                

    $.ajax({
        data: params,
        url: "http://dev.markitondemand.com/Api/v2/InteractiveChart/jsonp",
        dataType: "jsonp",
        success: function(json){
            //Catch errors
            if (!json || json.Message){
                console.error("Error: ", json.Message);
                return;
            }
            render(json);
            			


            
        },
        error: function(response,txtStatus){
            console.log(response,txtStatus)
        }
    });
}

PlotChartt();







								}
								$("#histCharts").click(function(){
									$("#checkit").removeClass('active');
									$("#checkit2").removeClass('active');
									$("#checkit1").addClass('active');
									
    PlotChart();

function PlotChart(){
    
    var params = {
        parameters: JSON.stringify(getInputParams())
    }
                $("#quoteTable").addClass('hidden');
                                       // $("#chartHist").addClass("col-lg-12");
            			
                        	$("#histChart").removeClass('hidden');
                    
                

    $.ajax({
        data: params,
        url: "http://dev.markitondemand.com/Api/v2/InteractiveChart/jsonp",
        dataType: "jsonp",
        success: function(json){
            //Catch errors
            if (!json || json.Message){
                console.error("Error: ", json.Message);
                return;
            }
            render(json);
            			


            
        },
        error: function(response,txtStatus){
            console.log(response,txtStatus)
        }
    });
}

function getInputParams(){
    return {  
        Normalized: false,
        NumberOfDays: 1095,
        DataPeriod: "Day",
        Elements: [
            {
                Symbol: data['Symbol'],
                Type: "price",
                Params: ["ohlc"]
            }
        ]
    }
}

function _fixDate(dateIn) {
    var dat = new Date(dateIn);
    return Date.UTC(dat.getFullYear(), dat.getMonth(), dat.getDate());
}

function _getOHLC(json) {
    var dates = json.Dates || [];
    var elements = json.Elements || [];
    var chartSeries = [];

    if (elements[0]){

        for (var i = 0, datLen = dates.length; i < datLen; i++) {
            var dat = _fixDate(dates[i]);
            var pointData = [
                dat,
                elements[0].DataSeries['close'].values[i]
            ];
            chartSeries.push( pointData );
        };
    }
    return chartSeries;
}

function render(data_t) {
    var ohlc = _getOHLC(data_t);
    $('#chartHist').highcharts('StockChart', {
            rangeSelector : {
            	buttons: [{
									type: 'week',
									count: 1,
									text: '1w'
								},{
									type: 'month',
									count: 1,
									text: '1m'
								}, {
									type: 'month',
									count: 3,
									text: '3m'
								}, {
									type: 'month',
									count: 6,
									text: '6m'
								}, {
									type: 'ytd',
									text: 'YTD'
								}, {
									type: 'year',
									count: 1,
									text: '1y'
								}, {
									type: 'all',
									text: 'All'
							}],
            	inputEnabled: false,
                selected : 0
            },
            title : {
                text : data['Symbol']+' Stock Price'
            },
            
            yAxis: {
            allowDecimals: false,
            title: {
                text: 'Stock Value'
            }
          
        },

        exporting: {
        	enabled: false
        },

            series : [{
                name : data['Symbol']+' Stock Price',
                data : ohlc,
                type : 'area',
                threshold : null,
                tooltip : {
                    valueDecimals : 2
                },
                lineWidth: 0,
                fillColor : {
                    linearGradient : {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops : [
                        [0, Highcharts.getOptions().colors[0]],
                        [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                    ]
                }
            }]
      
    });
}

			                   	});

			                   	$("#prevBut").click(function(){
										$(".carousel").carousel(0);
										$("#myCarousel").carousel("pause");

								});

			                   	$("#currentStock").click(function(){
			                   		$('#checkit1').removeClass('active');
			                   		$('#checkit2').removeClass('active');
			                   		$("#checkit").addClass('active');
			                   		$("#histChart").addClass('hidden');
			                   		$("#quoteTable").removeClass('hidden');
			                   		var cacheHold = '';
							$.get("handle.php",{sym:str},function(data){
								//var temp = $.parseJSON(data);
								cacheHold += "<br/><br/><table class='table table-striped'><tr><td>Name</td><td>"+data['Name']+"</td></tr>";
								cacheHold += "<tr><td>Symbol</td><td>"+data['Symbol']+"</td></tr>";
								var lp = round(data['LastPrice']);
								cacheHold += "<tr><td>Last Price</td><td>"+"$ "+lp+"</td></tr>";
								var chng = round(data['Change']);
								var chngper = round(data['ChangePercent']);

								if(chngper > 0){
									cacheHold += "<tr><td>Change (Change Percent)</td><td class='colorGreen'>"+chng+" ( "+chngper+"% )   <img src='up.png'/></td></tr>";
								}

								else if(chngper <0){
									cacheHold += "<tr><td>Change (Change Percent)</td><td class='colorRed'>"+chng+" ( "+chngper+"% )   <img src='down.png'/></td></tr>";
								}

								else{
									cacheHold += "<tr><td>Change (Change Percent)</td><td>"+chng+" ( "+chngper+"% )</td></tr>";
								}
								var timeStamp = data['Timestamp'];
								timeStamp = Date.parse(timeStamp);

								var localTime  = moment.utc(timeStamp).toDate();
    							localTime = moment(localTime).format('DD MMMM YYYY, HH:mm:ss a');
    							


								cacheHold += "<tr><td>Time and Date</td><td>"+localTime+"</td></tr>";

								var mCap = data['MarketCap'];
								if(mCap >= 10000000){
									mCap = mCap/1000000000;
									mCap = round(mCap);
									mCap+=" Billion";
								}
								else if(mCap >= 10000){
									mCap = mCap/1000000;
									mCap = round(mCap);
									mCap+=" Million";
								}
								else{
									mCap = round(mCap);
								}
								cacheHold += "<tr><td>Market Cap</td><td>"+mCap+"</td></tr>";
								cacheHold += "<tr><td>Volume</td><td>"+data['Volume']+"</td></tr>";

								var chngytd = round(data['ChangeYTD']);
								var chngperytd = round(data['ChangePercentYTD']);

								if(chngperytd > 0){
									
									cacheHold += "<tr><td>Change YTD (Change Percent YTD)</td><td class='colorGreen'>"+chngytd+" ( "+chngperytd+"% )   <img src='up.png'/></td></tr>";
							
								}

								else if(chngperytd <0){
									cacheHold += "<tr><td>Change YTD (Change Percent YTD)</td><td class='colorRed'>"+chngytd+" ( "+chngperytd+"% )   <img src='down.png'/></td></tr>";
								}
								else{
									cacheHold += "<tr><td>Change YTD (Change Percent YTD)</td><td>"+chngytd+" ( "+chngperytd+"% )</td></tr>";

								}
								var high = round(data['High']);
								var low = round(data['Low']);
								var open = round(data['Open']);
								cacheHold += "<tr><td>High Price</td><td>$ "+high+"</td></tr>";
								cacheHold += "<tr><td>Low Price</td><td>$ "+low+"</td></tr>";
								cacheHold += "<tr><td>Opening Price</td><td>$ "+open+"</td></tr>";
								cacheHold += "</table>";
								$("#quoteTableIn").html(cacheHold);

								var chartReq = "<img src='http://chart.finance.yahoo.com/t?s="+data['Symbol']+"&lang=en-US&width=500&height=300'/> ";
								$("#quoteTableChart").html(chartReq);
							});
			                   		});

			                   	$("#newsFeed").click(function(){
			                   		$.get("news.php",{symb:data['Symbol']},function(news){
			                   			console.log(news);
			                   		});
			                   	});
							
							});


						}
					}
				});
			});

			



	});


	

	</script>
        

  <style>
  body{
  	background-color: #1B5173;
  }
  .well{
  	background-color: white;
  }
  #redast{
  	padding-bottom: 7px;
  }
  #stockname{
  	width:100%;
  }
  #hding{

  }
  .well{
  	border-radius: 8px;
  }

  @media(max-width: 768px){
  	#btng{
  		padding-top: 10px;
  	}

  	#labele{
  		padding-bottom:10px;
  	}


  }

  #mainc{
  	padding-top: 20px;
  }

  h5{
  	padding:0;
  	margin:0;
  	font-size: 13px;
  }


  #outhr { 
    color:white;
}

#inhr{
	color:grey;
}

.colorGreen{
	color: green;
}

.colorRed{
	color: red;
}

#inhrp{
	color:grey;
	padding-top : 5px;
}

#nilpad{
	padding:0;
	margin:0;
}

#leftpad{
	padding:0;
	margin: 0;


}

#refauto{
	padding:0;
	margin:0;
	font-size: 12px;
}

#favo{
	padding:0;
	margin:0;
	font-size:13px;
}

#slideStock{
	padding:0;
	margin:0;
	font-size:13px;
}
  

  #logo-mark{
  	padding-top:10px;

  }

  .padel{
  	padding:0;
  	margin:0;
  	margin-left:5px;
  
  }

  #mardel{
  	margin:0;
  	margin-bottom:0px;
  }

  #mardel1{
  	margin:0;
  	margin-bottom:0px;
  }

  #mardel2{
  	margin:0;
  	margin-bottom:0px;
  }
  
  </style>

  
</head>
<body>
	<div class="container" id="mainc">
	<div class="well">
		<h4 class="text-center" id="hding">Stock Market Search</h4>
		<form class="form-inline" role="form" id="stockform" method="GET" action="handle.php">
		
		<div class="row">
			<div class="col-lg-3 col-md-4 col-sm-5 col-xs-12" id="labele">
			<h5>Enter the stock name or symbol:<img src="red-asterix.gif" id="redast"></div></h5>
			
			<div class="col-lg-6 col-md-5 col-sm-3 col-xs-12"><input type="text" class="form-control" id="stockname" placeholder="Apple Inc or AAPL" REQUIRED></input></div>
		
			<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12" id="btng">
				
				<button type="submit" class="btn btn-primary" id="getquote"><span class="glyphicon glyphicon-search"></span> Get Quote</button>
				
				<button type="reset" class="btn btn-default" role="button"><span class="glyphicon glyphicon-refresh"></span> Clear</button></div>
			
			</div>

		<div class="row">
			<div class="col-lg-3"></div>
			<div class="col-lg-6"><p id="insertText"></p></div>
			<div class="col-lg-3 col-xs-12" id="logo-mark">
			Powered By: <a href="http://dev.markitondemand.com/MODApis/" target="_blank"><img src="mod-logo.png" id="rsize" width="130" height="25" ></div></a>
			
		</div>
			
		</form>
	</div>
	<hr id="outhr">
	</div>

	<div class="container" >
	<div id="myCarousel" class="carousel slide">
    <div class="carousel-inner" role="listbox">
      <div class="item active" id="favWell">
	<div class="well">
	<div class="panel panel-default">
    <div class="panel-heading">
    	<div class="row">
    		<div class="col-lg-9">
    			<p id="favo">Favorite List</p>
    		</div>
    		<div class="col-lg-3" id="leftpad">
    			<span id="refauto">Automatic Refresh: </span><input type="checkbox" data-toggle="toggle" id="mardel">
    			<span class="padel"></span>
    			<button type="reset" class="btn btn-default" role="button"><span class="glyphicon glyphicon-refresh" id="mardel1"></span></button>

    			<button type="reset" class="btn btn-default" role="button" disabled="on" id="mardel2"><span class="glyphicon glyphicon-chevron-right"></span></button>
    		</div>
    	
    	</div>

    </div>
    <div class="panel-body">
  
	<table class="table table-striped" id="resultTable">
    
      <tr>
        <td>Symbol</td>
        <td>Company</td>
        <td>Stock Price</td>
        <td>Change (Change Percent)</td>
        <td>Market Cap</td>
      </tr>


    
  </table>
  </div>
    </div>
  </div>
  </div>
  <div class="item" id="quoteView">
  	<div class="well well-lg">
  	<div class="panel panel-default">
  	<div class="panel-heading">
  		<div class="row">
  		<div class="col-lg-5">
  			<button type="reset" class="btn btn-default" role="button" id="prevBut"><span class="glyphicon glyphicon-chevron-left"></span></button>
  		</div>
  		<div class="col-lg-3">
  		<p id="slideStock">Stock Details</p>
  		</div>
  		<div class="col-lg-4"></div>
  		</div>
  	</div>
  	<div class="panel-body">
  	

  	<ul class="nav nav-pills">
    <li id="checkit" class="active"><a data-toggle = "pill" href="#" id="currentStock"><span class="glyphicon glyphicon-dashboard"></span>  Current Stock
</a></li>
    <li id="checkit1"><a data-toggle = "pill" href="#" id="histCharts"><span class="glyphicon glyphicon-stats"></span>
 Historical Charts</a></li>
    <li id="checkit2"><a data-toggle = "pill" href="#" id="newsFeed"><span class="glyphicon glyphicon-link"></span> News Feed</a></li>
  </ul>
  <div class="row">
  <div class="col-lg-12">
  <hr id="inhr">
  </div>
  </div>
  <div id="quoteTable">

  <div class="row">
  	<div class="col-lg-6" id="quoteTableIn">
  	</div>

  	<div class="col-lg-6" id="quoteTableChart"></div>


  </div>

  </div>


  <div id="histChart">
  <div class="row">
  	<div class="col-lg-12" id="chartHist">
  		
  	</div>
  </div>


  </div>

  </div>
  </div>
  </div>
	</div>
	</div>
	</div>
	</div>
	



  
</body>
</html>