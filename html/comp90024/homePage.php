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
      <div><button type="button" class="btn btn-default btn-block" onclick="showData('unemployed_Percent', [0, 0.04, 0.05, 0.055, 0.06, 0.08])">Unemployed</button></div>
      <div><button type="button" class="btn btn-default btn-block" onclick="showData('did_not_go_to_schllo_Percent', [0, 0.002, 0.005, 0.007, 0.01, 0.02])">People Did Not Go to School</button></div>
      <div><button type="button" class="btn btn-default btn-block" onclick="showData('health_care_and_social_assistance' ,[0, 0.05, 0.1, 0.12, 0.15, 0.2], reverse = true)">Health Care And Social Assistance</button></div>
      <div><button type="button" class="btn btn-default btn-block" onclick="showData('weekly_income_4000_more_proportion', [0, 0.03, 0.06, 0.12, 0.18, 0.2], reverse = true)">Income Over 4000/week</button></div>
      <div><button type="button" class="btn btn-default btn-block" onclick="showData('volunteer_proportion', [0, 0.1, 0.14, 0.18, 0.2, 0.25], reverse = true)">Volunteer</button></div>
      <div><button type="button" class="btn btn-default btn-block" onclick="showData('place_of_usual_residence_1_year_proportion', [0, 0.4, 0.42, 0.43, 0.44, 0.45])">Moving House</button></div>
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
      map.data.loadGeoJson('<?php echo $path['url'].'assets/geojson/Total_Attributes_In_Percent_Form.geojson' ?>');
      map.data.setStyle({ fillColor: "#CC3300", strokeWeight: 1 });


      var comment = document.getElementById('my-comment');
      comment.innerHTML = commentContext
      map.controls[google.maps.ControlPosition.RIGHT_TOP].clear();
      map.controls[google.maps.ControlPosition.RIGHT_TOP].push(comment);


    }

    function showData(attribute, split_list, reverse = false) {
      color_array = ["#33FF00", "#669900", "#FFFF00", "#CC9900", "#CC3300", "#000000"]
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