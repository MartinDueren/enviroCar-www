<?
include('header.php');
?>

<script type="text/javascript">
  var loading = true;

	function addPhenomenonStatistics(name, avg, unit){
      $('#phenomenonStatistics').append('<li class="customLi"><img src="./assets/img/route.svg" style="height: 30px; margin-right: 10px; "/>&Oslash;  '+name+':  '+Math.round(avg*100)/100+" "+unit);
  }

  function addOverallStatistics(name, value){
      $('#overallStatistics').append('<li class="customLi"><img src="./assets/img/route.svg" style="height: 30px; margin-right: 10px; "/>'+name+': '+value);
  }

  function addRoutes(id, name, date){
      $('#routes').append('<li class="customLi"><img src="./assets/img/route.svg" style="height: 30px; margin-right: 10px; "/><a href="route.php?id='+id+'">'+name+'</a><br><div><? echo $created ?>: '+date+'</div></li>');
  }

    $.get('./assets/includes/users.php?tracks', function(data) {
      if(data >= 400){
        console.log(data);
        if(data == 400){
            error_msg("<? echo $routeError ?>");
        }else if(data == 401 || data == 403){
          error_msg("<? echo $routeNotAllowed ?>")
        }else if(data == 404){
          error_msg("<? echo $routeNotFound ?>")
        }
        $('#loadingIndicator_routes').hide();
      }else{
        	data = JSON.parse(data);
          numberofTracks = data.tracks.length;
          addOverallStatistics("Tracks", numberofTracks);
          if(numberofTracks > 0){
            if(!loading){
              $('#loadingIndicator_routes').hide();
            }else{
              loading = false;
            }
    		    for(i = 0; i < data.tracks.length; i++){
    			     addRoutes(data.tracks[i].id, data.tracks[i].name, convertToLocalTime(data.tracks[i].modified));
    		    }

          }else{
            $('#routes').append("<? echo $noroutesavailable ?>");
            $('#loadingIndicator_routes').hide();
          }
    	}
  	});

    $.get('./assets/includes/users.php?userStatistics', function(data) {
      if(data >= 400){
        console.log(data);
        if(data == 400){
            error_msg("<? echo $statisticsError ?>");
        }else if(data == 401 || data == 403){
          error_msg("<? echo $statisticsNotAllowed ?>")
        }else if(data == 404){
          error_msg("<? echo $statisticsNotFound ?>")
        }
        $('#loadingIndicator_statistics').hide();
      }else{
          data = JSON.parse(data);
          if(data.statistics.length > 0){
            if(!loading){
              $('#loadingIndicator_').hide();
            }else{
              loading = false;
            }
            for(i = 0; i < data.statistics.length; i++){
               addPhenomenonStatistics(data.statistics[i].phenomenon.name, data.statistics[i].avg, data.statistics[i].phenomenon.unit);
            }

          }else{
            $('#loadingIndicator_statistics').hide();
          }
      }
      $('#loadingIndicator_statistics').hide();
    });

  	function convertToLocalTime(serverDate) {
	    var dt = new Date(Date.parse(serverDate));
	    var localDate = dt;


	    var gmt = localDate;
	        var min = gmt.getTime() / 1000 / 60; // convert gmt date to minutes
	        var localNow = new Date().getTimezoneOffset(); // get the timezone
	        // offset in minutes
	        var localTime = min - localNow; // get the local time

	    var dateStr = new Date(localTime * 1000 * 60);
		  var d = dateStr.getDate();
	    var m = dateStr.getMonth() + 1;
	    var y = dateStr.getFullYear();

      var totalSec = dateStr.getTime() / 1000;
      var hours = parseInt( totalSec / 3600 ) % 24;
      var minutes = parseInt( totalSec / 60 ) % 60;


	    return '' + y + '-' + (m<=9 ? '0' + m : m) + '-' + (d <= 9 ? '0' + d : d) + ' ' + hours +':'+ (minutes <= 9 ? '0' + minutes : minutes);
	  }

</script>


<div class="container rightband">
	<div class="row-fluid">
      <div class="span5">
        <h2><? echo $yourroutes ?></h2>
        <div id="loadingIndicator_routes" style="background:url(./assets/img/ajax-loader.gif) no-repeat centereight:100px;"></div>
        	<ul id="routes" style="max-height: 400px; overflow-y: auto;">
        	</ul>
      </div>
      <div class="span5 ">
        <h2><? echo $yourstatistics ?> (<a href="support.php#phen">?</a>)</h2>
        <div id="loadingIndicator_statistics" style="background:url(./assets/img/ajax-loader.gif) no-repeat center center; height:100px;"></div>
        <div style="max-height: 400px; overflow-y: auto;">
          <ul id="overallStatistics">
          </ul>
          <ul id="phenomenonStatistics">
          </ul>
        </div>
      </div>
    </div>
</div>




<?
include('footer.php');
?>