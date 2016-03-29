<!DOCTYPE html>
<html lang="en">
<head>
  <title>Homework 8</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
  <link
        rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">

        

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

  hr { 
    color:white;
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
	<hr>
	</div>

	<div class="container" >
	<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner" role="listbox">
      <div class="item active">
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
	</div>
	</div>
	</div>

	
	



  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
  
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
  <script>
	
  	$(function() {

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

			$("#stockform").submit(function(event){
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
							$.get("handle.php",{sym:str},function(data){
								var temp = $.parseJSON(data);
							
							});
						}
					}
				});
			});

	});


	

	</script>
</body>