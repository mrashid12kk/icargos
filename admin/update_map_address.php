<?php 

require 'includes/conn.php';

$track_no=$_GET['track_no'];

$query="select * from `orders` where `track_no`='$track_no'";

$result = mysqli_query($con, $query) or die(mysql_error());

$row = mysqli_fetch_assoc($result);

$receiver_address=isset($row['receiver_address']) ? $row['receiver_address'] : "";

$latitude=isset($row['map_latitude']) ? $row['map_latitude'] : "";

$longitude=isset($row['map_longitude']) ? $row['map_longitude'] : "";

// $receiver_address="Khushab";

?>



<!DOCTYPE html>

<html lang="en">

   <head>

      <meta charset="UTF-8">

      <meta http-equiv="X-UA-Compatible" content="IE=edge">

      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <title>Update Map Address</title>

      <link rel="stylesheet" href="assets/css/bootstrap/bootstrap.css" />

      <style>

         /* Set the size of the div element that contains the map */

         #map {

         height: 100vh;

         /* The height is 400 pixels */

         width: 100%;

         /* The width is the width of the web page */

         }
         .form-group input{
                height: 42px;
    border: 1px solid #cccccc75;
         }
         .row{
            margin: 0;
         }
         .fields_box{
            position: absolute;
    top: 0;
    background: #fffffffa;
    width: 100%;
    padding: 15px 31px;
    box-shadow: 0 0 15px 0 #ccc;
         }
         .bottom_fixed {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    text-align: center;
    background: #fff;
    padding: 15px;
}
.form-group {
    margin-bottom: 5px;
}
         .bottom_fixed button {
    background-image: linear-gradient(92deg, #4878a3,#428bca);
    padding: 8px 33px;
    font-size: 18px;
    border: none;
    outline: 0;
}

@media(max-width: 767px){
    .container{
        width: auto;
    }
    .fields_box {
    padding: 10px 9px;
}
.form-group input{
                height: 36px;
         }
.form-group {
    margin-bottom: 11px;
}
.bottom_fixed button {
    width: 100%;
}
.bottom_fixed {
    padding: 15px 30px;
}
.form-control{
    font-size: 12px;
}
}

      </style>

   </head>

   <body>
 <div id="map"></div>
 <div class="fields_box">
     <form action="" method="post" id="map-form" onsubmit="return false;">
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">

        <label for="track_no">Track No:</label>

        <input type="text" readonly class="form-control" name="track_no" id="track_no" value="<?php echo isset($_GET['track_no']) ? $_GET['track_no'] : ""; ?>">

    </div>
            </div>
            <div class="col-sm-9">
                <div class="form-group">

        <label for="location">Location:</label>

        <input type="text" class="form-control" type="text" name="location" id="location" value="<?php echo $receiver_address; ?>">

    </div>
            </div>
        </div>
    

    

    <input type="hidden" name="latitude" id="latitude" value="<?php echo $latitude; ?>">

    <input type="hidden" name="longitude" id="longitude" value="<?php echo $longitude; ?>">

    <div class="bottom_fixed">
        <button onclick="handleFormSubmit()" name="submit" class="btn btn-primary">Submit</button>
    </div>

    </form>
 </div>
      

      <script src="assets/js/app/jquery-2.2.4.min.js"></script>

      <script src="assets/js/bootstrap/bootstrap.min.js"></script>

      <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDJa7ERqhIHUt1OrU_UGywlWnzfXgndvrc&libraries=places&callback=initMap" async defer></script>

   </body>

</html>

<script>

    let marker;

    let map;

    let geocoder;

    let place;

    let autocomplete;

    let infowindow;

   function initMap() {

            

           // The location of Uluru

           geocoder = new google.maps.Geocoder();

           let latitude=document.getElementById('latitude').value;

           let longitude=document.getElementById('longitude').value;

           // const myLatLong = { lat: parseFloat(latitude), lng:parseFloat(longitude) };

           var myLatLong = new google.maps.LatLng(latitude, longitude);

           // The map, centered at Uluru

           map = new google.maps.Map(document.getElementById("map"), {

               zoom: 16,

               center: myLatLong,

           });

          

           // The marker, positioned at Uluru

            marker = new google.maps.Marker({

               position: myLatLong,

               map: map,

               draggable:true,

           });

           

           var input = document.getElementById('location');

            autocomplete = new google.maps.places.Autocomplete(input);



            // update 

           if(input.value){

                geocoder.geocode( { 'address': input.value}, function(results, status) {

                if (status == google.maps.GeocoderStatus.OK) {

                        // if($results[0]){

                            map.setCenter(results[0].geometry.location);

                            marker.setPosition(results[0].geometry.location);

                            bindDataToForm(results[0].formatted_address, marker.getPosition().lat(),

                            marker.getPosition().lng());

                            infowindow.setContent(results[0].formatted_address);

                            infowindow.open(map, marker);

                        // }

                    } else {

                        console.log('Geocode was not successful for the following reason: ' + status);

                    }

                });

           }else{

            getLocation(); // get current Location

           }





            // update

           

           autocomplete.bindTo('bounds', map);

            infowindow = new google.maps.InfoWindow();

           

           autocomplete.addListener('place_changed', function() {

               infowindow.close();

           

               marker.setVisible(false);

               place = autocomplete.getPlace();

               if (!place.geometry) {

                   window.alert("Autocomplete's returned place contains no geometry");

                   return;

               }

               // If the place has a geometry, then present it on a map.

               if (place.geometry.viewport) {

                   map.fitBounds(place.geometry.viewport);

               } else {

                   map.setCenter(place.geometry.location);

                   map.setZoom(17);

               }

               marker.setPosition(place.geometry.location);

               marker.setVisible(true);

           

               bindDataToForm(place.formatted_address, place.geometry.location.lat(), place.geometry

                   .location.lng());

           

                infowindow.setContent(place.formatted_address);

               infowindow.open(map, marker);

           

           });

           

           google.maps.event.addListener(marker, 'dragend', function() {

           

               geocoder.geocode({

                   'latLng': marker.getPosition()

               }, function(results, status) {

                   if (status == google.maps.GeocoderStatus.OK) {

                       if (results[0]) {

                           // console.log(marker.getPosition().lat(),marker.getPosition().lng());

                           bindDataToForm(results[0].formatted_address, marker.getPosition().lat(),

                               marker.getPosition().lng());

                               infowindow.setContent(results[0].formatted_address);

                           infowindow.open(map, marker);

                       }

                   }

               });

           });

   }

   

   window.initMap = initMap;



   function getLocation() {

    if (navigator.geolocation) {

      navigator.geolocation.getCurrentPosition(showPosition);

    } else {

      alert("Geolocation is not supported by this browser.");

    }

  }

  function showPosition(position) {

    const pos = {

        lat: position.coords.latitude,

        lng: position.coords.longitude,

      };

    map.setCenter(new google.maps.LatLng(pos.lat, pos.lng));

    marker.setPosition(pos);

    marker.setVisible(true);

        geocoder.geocode({

            'latLng': marker.getPosition()

        }, function(results, status) {

            if (status == google.maps.GeocoderStatus.OK) {

                if (results[0]) {

                    // console.log(marker.getPosition().lat(),marker.getPosition().lng());

                    bindDataToForm(results[0].formatted_address, marker.getPosition().lat(),

                        marker.getPosition().lng());

                        infowindow.setContent(results[0].formatted_address);

                    infowindow.open(map, marker);

                }

            }

        });

  }

   

</script>

<script>

   function bindDataToForm(address, lat, lng) {

      document.getElementById('location').value = address;

      document.getElementById('latitude').value = lat;

      document.getElementById('longitude').value = lng;

   }  

   function handleFormSubmit()

   {

      $('button[name="submit"]').button('loading');

      let formdata=$('#map-form').serialize();

      $.ajax({

        url : 'ajax_update_map_address.php',

        type : 'POST',

        data : formdata,

        success: function(response){

            $('button[name="submit"]').button('reset');

        },

        error: function (error) {

           console.log(error);

        }

      });

   }



</script>

