<?php
session_start();
include_once "includes/conn.php";
if (isset($_SESSION['customers'])) {
	include "includes/header.php";
	$cities = mysqli_query($con, "SELECT * FROM cities WHERE 1 ");
	$page_title = 'Edit Profile';
	$is_profile_page = true;
	$customer_id = $_SESSION['customers'];
	if (isset($_POST['submit'])) {
		$profiling = $_POST['profiling'];
		foreach ($profiling as $row) {
			$shipper_name = trim($row['shipper_name']);
			$shipper_phone = trim($row['shipper_phone']);
			$shipper_email = trim($row['shipper_email']);
			$shipper_address = trim($row['shipper_address']);
			$google_address = trim($row['google_address']);
			$shipper_latitude = trim($row['shipper_latitude']);
			$shipper_longitude = trim($row['shipper_longitude']);

			$check_query = mysqli_query($con, "SELECT * FROM profiling WHERE shipper_name='" . $shipper_name . "' AND shipper_phone='" . $shipper_phone . "' AND shipper_email='" . $shipper_email . "' AND shipper_address='" . $shipper_address . "' AND customer_id=" . $customer_id . "  ");
			$rec = mysqli_num_rows($check_query);
			if ($rec == 0) {

                $insert_Qeuery = mysqli_query($con, "INSERT INTO profiling(`google_address`,`shipper_latitude`,`shipper_longitude`,`shipper_name`,`shipper_phone`,`shipper_email`,`shipper_address`,`customer_id`) VALUES('" . $google_address . "','" . $shipper_latitude . "','" . $shipper_longitude . "','" . $shipper_name . "','" . $shipper_phone . "','" . $shipper_email . "','" . $shipper_address . "','" . $customer_id . "') ");
                $insert_id = mysqli_insert_id($con);
                if (isset($insert_id) && $insert_id > 0) {
                    $profile_id = 20000 + $insert_id;
                    mysqli_query($con, "UPDATE profiling SET profile_id = $profile_id WHERE id = $insert_id");
                }
            }
		}
	}
	$profile_query = mysqli_query($con, "SELECT * FROM profiling WHERE customer_id=" . $customer_id . " order by id desc ");
?>
<style>
#fileform .col-lg-12 {
    padding: 0;
}

section .dashboard .white {
    background: #fff;
    padding: 0;
    box-shadow: 0 0 3px #ccc;
    width: 100%;
    display: table;
    margin-bottom: 17px;
}

.btn_save,
.btn_process {
    color: #fff !important;
}

.profile_box_top .multi_profile_main {
    padding: 0 7px;
}

.remove_row {
    color: #fff !important;
}

.btn_save:hover,
.btn_save:focus {
    color: #fff !important;
}

.btn_process:hover,
.btn_process:focus {
    color: #fff !important;
}

.multi_profile_main {
    margin-top: 23px !important;
}

#fileform .col-lg-6 {
    padding: 0 15px 0 0;
}

.form-group label {
    color: #000;
    margin-bottom: 6px;
}

.white h2 {
    color: #000;
}

select,
input,
textarea {
    border: 1px solid #ccc !important;
    color: #000 !important;
}

::-webkit-input-placeholder {
    /* Chrome/Opera/Safari */
    color: #000 !important;
}

::-moz-placeholder {
    /* Firefox 19+ */
    color: #000 !important;
}

:-ms-input-placeholder {
    /* IE 10+ */
    color: #000 !important;
}

:-moz-placeholder {
    /* Firefox 18- */
    color: #000 !important;
}
}


@media(max-width: 1250px) {
    .container {
        width: 100%;
    }
}

@media(max-width: 1024px) {
    .container {
        width: 100%;
    }

    #header_wrap .theme-menu>li:nth-child(5),
    #header_wrap .theme-menu>li:nth-child(6) {
        padding-top: 8px !important;
    }

    .navbar-nav .active:last-child a {
        padding: 5px 0 1px;
    }

    .site-logo img {
        top: 7px !important;
    }

    section .dashboard .white {
        padding: 20px 10px !important;
    }
}

@media(max-width: 767px) {
    .container {
        width: auto !important;
    }

    .menu_icon i {
        top: -42px;
    }

    #header_wrap .menu-bar {
        padding: 17px 0px 0 !important;
    }

    .site-logo img {
        top: -13px !important;
    }
}

<style>table th {
    color: #8f8f8f;
}

.table-bordered tr td {
    color: #000;
}

@media(max-width: 767px) {
    .container {
        width: auto;
    }

    .white h2 {
        margin-top: 23px !important;
    }

    .col-sm-3 {
        padding: 0;
        color: #000;
        margin-bottom: 8px;
    }

    .col-lg-12,
    .col-sm-3 {
        padding: 0;
    }

    .btn-danger {
        width: 100%;
    }

    section .white {
        min-height: auto;
    }

    .bg,
    .password {
        padding: 0px 0 5px;
    }

}
</style>
<section class="bg padding30">
    <div class="container-fluid dashboard">
        <div class="col-lg-2 col-md-3 col-sm-4 profile" style="margin-left:0">
            <?php
				include "includes/sidebar.php";
				?>
        </div>
        <div class="col-lg-10 col-md-9 col-sm-8 profile">


            <div class="row">
                <div class="col-lg-12  login">
                    <div class="white">
                        <h2 style="    background-color: #074e8c;
    border-color: #074e8c;
    margin: 0;
    color: #fff !important;
    font-size: 14px;
    padding: 10px 15px;
    border-bottom: 1px solid transparent;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;">Multipl Profile</h2>
                        <div class="multi_profile_main multiple_profile_box">
                            <form method="POST" action="">
                                <table class="table" id="multiple_profile">
                                    <thead>
                                        <tr>
                                            <th>Shipper Name</th>
                                            <th>Shipper Phone</th>
                                            <th>Shipper Email</th>
                                            <!-- <th>Shipper Address</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input type="text" name="profiling[0][shipper_name]"
                                                    class="form-control" required="true">
                                            </td>
                                            <td>
                                                <input type="text" name="profiling[0][shipper_phone]"
                                                    class="form-control" required="true">
                                            </td>
                                            <td>
                                                <input type="email" name="profiling[0][shipper_email]"
                                                    class="form-control" required="true">
                                            </td>
                                            <!-- <td>
                                                <input type="text" name="profiling[0][shipper_address]"
                                                    class="form-control" required="true">

                                            </td> -->
                                        </tr>
                                        <tr>
                                            <td colspan="5">
                                                <div class="row">
                                                    <div class="col-sm-12 padd_left" style="padding-right:0;">
                                                        <div class="form-group">
                                                            <label class="control-label"><span
                                                                    style="color: red;">*</span>
                                                                Shipper Address</label>
                                                            <!-- <textarea class="form-control" name="receiver_address"  placeholder="Consignee Address" required="true"></textarea> -->
                                                            <input autocomplete="false" required="true"
                                                                name="profiling[0][shipper_address]"
                                                                class="address form-control" type="text" value=""
                                                                id="property_add" placeholder="Shipper Address">


                                                            <input type="hidden" name="profiling[0][google_address]"
                                                                id="google_address">
                                                            <input type="hidden" class="form-control" id="latitude"
                                                                name="profiling[0][shipper_latitude]">
                                                            <input type="hidden" class="form-control" id="longitude"
                                                                name="profiling[0][shipper_longitude]">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="mapping" id="mapping"
                                                                        style="width: 100%; height: 173px;margin-bottom: 10px;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                    </tbody>

                                </table>
                                <input type="submit" name="submit" class="btn btn-success btn_save" value="Save">
                            </form>
                        </div>

                    </div>

                </div>

            </div>



        </div>

        <div class="col-lg-10 col-md-9 col-sm-8 profile profile_box_top">


            <div class="row">
                <div class="col-lg-12  login">
                    <div class="white">
                        <h2 style="    background-color: #074e8c;
    border-color: #074e8c;
    margin: 0;
    color: #fff !important;
    font-size: 14px;
    padding: 10px 15px;
    border-bottom: 1px solid transparent;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;">Profiles</h2>
                        <div class="multi_profile_main">
                            <div class="multi_profile_main">
                                <table class="table table-hover table-bordered dataTable hide-on-tab orders_tbl">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>Profile ID</th>
                                            <th>shipper Name</th>
                                            <th>shipper Phone</th>
                                            <th>shipper Email</th>
                                            <th>shipper Address</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
											$sr = 1;
											while ($row = mysqli_fetch_array($profile_query)) { ?>
                                        <tr>
                                            <td><?php echo $sr; ?></td>
                                            <td>
                                                <?php echo $row['profile_id']; ?>
                                            </td>
                                            <td>
                                                <?php echo $row['shipper_name']; ?>
                                            </td>
                                            <td>
                                                <?php echo $row['shipper_phone']; ?>
                                            </td>
                                            <td>
                                                <?php echo $row['shipper_email']; ?>
                                            </td>
                                            <td>
                                                <?php echo $row['shipper_address']; ?>
                                            </td>
                                            <td>
                                                <a href="update_profiling.php?id=<?php echo $row['id']; ?>"><i
                                                        class="fa fa-pencil"></i></a>
                                                <a href="delete_profiling.php?id=<?php echo $row['id']; ?>"
                                                    onclick="return confirm('Are You Sure Delete This Record')"><i
                                                        class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        <?php $sr++;
											} ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                </div>

            </div>



        </div>
    </div>
</section>

</div>

<?php
	// include "includes/footer.php";
} else {
	header("location:index.php");
}
?>
<?php include 'includes/footer.php'; ?>
<!--  <script type="text/javascript">
  	$('body').on('click','.add_row',function(e){
        e.preventDefault();
        var counter = $('#multiple_profile > tbody tr').length;
        var row = $('#multiple_profile > tbody tr').first().clone();
        row.find('input,select').each(function(){
          var name = $(this).attr('name').split('[0]');
          $(this).attr('name',name[0]+'['+counter+']'+name[1]);
        })
        
        row.find('.add_row').addClass('remove_row');
        row.find('.add_row').addClass('btn btn-danger');
        row.find('.fa-plus').addClass('fa-trash');
        row.find('.add_row').removeClass('btn-info');
        row.find('.fa-plus').removeClass('fa-plus');
        row.find('.add_row').removeClass('add_row');
        $('#multiple_profile').append(row);
      })
  </script> -->

<script>
const api_key = '<?php echo getConfig("api_key") ?>';
var placeSearch, autocomplete;
var componentForm = {
    // street_number: 'short_name',
    // route: 'long_name',
    // locality: 'long_name',
    // administrative_area_level_1: 'short_name',
    // country: 'long_name',
    // postal_code: 'short_name'
};
// starting Navigator

navigator.geolocation.getCurrentPosition(function(position) {
        getUserAddressBy(position.coords.latitude, position.coords.longitude);
        latitude = position.coords.latitude;
        longitude = position.coords.longitude;
        // console.log("ere latitude is" + latitude)
        // console.log(" er e longitude is" + longitude)
        initialize();
    },
    function(error) {
        console.log("The Locator was denied :(")
    })
var locatorSection = document.getElementById("location-input-section")

function init() {
    var locatorButton = document.getElementById("location-button");
    locatorButton.addEventListener("click", locatorButtonPressed)
}

function locatorButtonPressed() {
    locatorSection.classList.add("loading")

    navigator.geolocation.getCurrentPosition(function(position) {
            getUserAddressBy(position.coords.latitude, position.coords.longitude)
            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;
        },
        function(error) {
            locatorSection.classList.remove("loading")
            alert("The Locator was denied :( Please add your address manually")
        })
}

function getUserAddressBy(lat, long) {

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var address = JSON.parse(this.responseText)
            document.getElementById('property_add').value = address.results[0].formatted_address;
            document.getElementById('google_address').value = address.results[0].formatted_address;
            // filladdress(address.results[0]);

        }
    };
    xhttp.open("GET", "https://maps.googleapis.com/maps/api/geocode/json?latlng=" + lat + "," + long +
        "&key=" + api_key + "", true);
    xhttp.send();

}
// Ending Navigator

var latitude = document.getElementById('latitude').value;
var longitude = document.getElementById('longitude').value;

// console.log("latitude is" + latitude)
// console.log("longitude is" + longitude)

function initialize() {

    var latlng = new google.maps.LatLng(latitude, longitude);
    var map = new google.maps.Map(document.getElementById('mapping'), {
        center: latlng,
        zoom: 14
    });
    var marker = new google.maps.Marker({
        map: map,
        position: latlng,
        draggable: true,
        anchorPoint: new google.maps.Point(0, -29)
    });
    var input = document.getElementById('property_add');
    var geocoder = new google.maps.Geocoder();
    var autocomplete = new google.maps.places.Autocomplete(input);

    autocomplete.bindTo('bounds', map);
    var infowindow = new google.maps.InfoWindow();

    autocomplete.addListener('place_changed', function() {
        infowindow.close();
        marker.setVisible(false);
        var place = autocomplete.getPlace();
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
    // this function will work on marker move event into map
    google.maps.event.addListener(marker, 'dragend', function() {
        geocoder.geocode({
            'latLng': marker.getPosition()
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    bindDataToForm(results[0].formatted_address, marker.getPosition().lat(),
                        marker
                        .getPosition().lng());
                    infowindow.setContent(results[0].formatted_address);
                    infowindow.open(map, marker);
                }
            }
        });
    });
}
// }, false);

function bindDataToForm(address, lat, lng) {
    document.getElementById('property_add').value = address;
    document.getElementById('google_address').value = address;
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;
}
</script>