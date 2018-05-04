<div class="container-fluid" id="content-container">
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <!-- <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
      <h3>COMP90024 TEAM26</h3>
    </div>
  </div> -->
  <div class="row">
    <div class="col-xs-12 col-sm-10 col-md-10">
      <div id="map"></div>
      <div id="my-comment"></div>
    </div>
    <div class="col-xs-12 col-sm-2 col-md-2">
    <div><button type="button" class="btn btn-default btn-block" onclick="showData('pos_rate', [0, 0.1, 0.2, 0.3, 0.5, 0.8], reverse = true)">Tweets Sentiment</button></div>
      <div><button type="button" class="btn btn-default btn-block" onclick="showData('data2630464559989593264_p_tot_unemp_tot', [0, 200, 500, 1000, 1500, 2000])">Unemployed</button></div>
      <div><button type="button" class="btn btn-default btn-block" onclick="showData('data7892875890288123329_Person_Did_Not_Go_To_School_Total', [0, 50, 100, 300, 600, 1000])">Education</button></div>
      <div><button type="button" class="btn btn-default btn-block" onclick="showData('data1854632499827315136_hc_sa_med_oth_hcs_p' ,[0, 50, 100, 300, 600, 1000])">Medication</button></div>
      <div><button type="button" class="btn btn-default btn-block" onclick="showData('Income of 4000 or more Total_fi_4000_more_tot', [0, 100, 200, 800, 1500, 2000])">Income Over 4000/week</button></div>
      <div><button type="button" class="btn btn-default btn-block" onclick="showData('Persons Total Voluntary work_p_total_total', [0, 5000, 10000, 15000, 20000, 50000])">Volunter</button></div>
      <div><button type="button" class="btn btn-default btn-block" onclick="showData('Same usual address 1 year ago as in 2016 Persons_sme_usl_ad_1_yr_ago_as_2016_p' ,[0, 5000, 10000, 15000, 20000, 50000])">Moving House</button></div>
    </div>
  </div>
  <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
  </script>
  <script async defer
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBsoacIx5MR9VPMGON1Wd3rMp1Uy9jUMTM&libraries=places&callback=initMap"
      async defer></script>
  <script>
    var map;
    var infowindow = null;
    var commentContext = '<p>This map is broken down into Statistical Area Level 2(SA 2).</p>'

    function initMap() {
      // Create a map object and specify the DOM element for display.
      map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: -37.815123, lng: 144.963096},
        zoom: 9
      });
      map.data.loadGeoJson('<?php echo $path['url'].'assets/geojson/FinalTotalAttribute.geojson' ?>');
      map.data.setStyle({ fillColor: "#CC3300", strokeWeight: 1 });


      var comment = document.getElementById('my-comment');
      comment.innerHTML = commentContext
      map.controls[google.maps.ControlPosition.RIGHT_TOP].clear();
      map.controls[google.maps.ControlPosition.RIGHT_TOP].push(comment);


    }

    function showData(attribute, split_list, reverse = false) {
      color_array = ["#33FF00", "#669900", "#CC9900", "#CC3300", "#990000", "#000000"]
      if(reverse) {
        color_array.reverse()
      }
      map.data.setStyle(function(feature) {
          var num = feature.getProperty(attribute);
          if (num >= split_list[0] && num < split_list[1]) {
            return {
              fillColor: color_array[0],
              strokeWeight: 1
            };
          }else if(num >= split_list[1] && num < split_list[2]) {
            return {
              fillColor: color_array[1],
              strokeWeight: 1
            };
          }else if(num >= split_list[2] && num < split_list[3]) {
            return {
              fillColor: color_array[2],
              strokeWeight: 1
            };
          }else if(num >= split_list[3] && num < split_list[4]) {
            return {
              fillColor: color_array[3],
              strokeWeight: 1
            };
          }else if(num >= split_list[4] && num < split_list[5]) {
            return {
              fillColor: color_array[4],
              strokeWeight: 1
            };
          }else {
            return {
              fillColor: color_array[5],
              strokeWeight: 1
            };
          }
      });

      map.data.addListener('click', function(event) {
        var name = event.feature.getProperty('SA2_NAME16');
        var num = event.feature.getProperty(attribute);
        if(infowindow) {
          infowindow.close();
        }
        var contentString = '<div id="content">'+
          '<div id="siteNotice">'+
          '</div>'+
          '<h4 id="firstHeading" classw="firstHeading">'+name+'</h4>'+
          '<div id="bodyContent">'+
          '<p>Number: ' + num + '</p>'+
          '</div>'+
          '</div>';
        infowindow = new google.maps.InfoWindow({
          content: contentString
        });
        infowindow.setPosition(event.latLng);
        infowindow.open(map);
      });

      var comment = document.getElementById('my-comment');
      comment.innerHTML = commentContext
      for (i = 0; i < 6; i++) {
        if (i < 5) {
          var color = color_array[i];
          var startValue = split_list[i];
          var endValue = split_list[i + 1];
          var div = document.createElement('div');
          div.classList.add("clearfix");
          div.innerHTML = '<div class="col-md-3" id="my-box" style="background: ' + color + '; width:20px; height:10px;"></div>' + '<div class="col-md-9">: ' + startValue + ' ~ ' + endValue + '</div>';
        }else {
          var color = color_array[i];
          var startValue = split_list[i];
          var div = document.createElement('div');
          div.classList.add("clearfix");
          div.innerHTML = '<div class="col-md-3" id="my-box" style="background: ' + color + '; width:20px; height:10px;"></div>' + '<div class="col-md-9">: ' + startValue + ' ~ </div>';
        }
        comment.appendChild(div);
      }
      map.controls[google.maps.ControlPosition.RIGHT_TOP].clear();
      map.controls[google.maps.ControlPosition.RIGHT_TOP].push(comment);
    }

    function clearData() {
      map.data.forEach(function (feature) {
          map.data.remove(feature);
      });
    }

  </script>
</div>