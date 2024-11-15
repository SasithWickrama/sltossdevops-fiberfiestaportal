<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if ($_SESSION['loggedin'] != true) {
	echo '<script type="text/javascript"> document.location = "../index.php";</script>';
}

if ($_SESSION['app'] != 'FF') {
	echo '<script type="text/javascript"> document.location = "../index.php";</script>';
}

include 'dbcon.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8" />

	<link rel="icon" type="image/png" href="../assets/img/fiber_fiesta.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>
		Fiber Fiesta
	</title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
	<!--     Fonts and icons     -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
	<!-- CSS Files -->
	<link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
	<link href="../assets/css/paper-dashboard.css?v=2.0.1" rel="stylesheet" />
	<!-- CSS Just for demo purpose, don't include it in your project -->
	<link href="../assets/demo/demo.css" rel="stylesheet" />

	<style>
		select option[disabled]:first-child {
			display: none;
		}

		.spacing {
			width: 10px;
		}
	</style>

</head>

<body>

	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyACf3KGqbKylkAoI4MkjKTdwlbdoCMD-rY&libraries=drawing&callback=initMap">
	</script>

	<div class="wrapper ">
		<!-- Sidebar -->
		<div class="sidebar" data-color="black" data-active-color="danger">
			<div class="logo">
				<a href="https://www.creative-tim.com" class="simple-text logo-mini">
				</a>
				<a href="customer_location.php" class="simple-text logo-normal">
					Fiber Fiesta
				</a>
			</div>
			<div class="sidebar-wrapper">
				<ul class="nav">
					<li>
						<a href="customer_location.php">
							<i class="nc-icon nc-pin-3"></i>
							<p>Customer Location</p>
						</a>
					</li>
					<li class="active">
						<a href="user_location.php">
							<i class="nc-icon nc-single-02"></i>
							<p>User Location</p>
						</a>
					</li>
					<li>
						<a href="user_reports.php">
							<i class="nc-icon nc-paper"></i>
							<p>User Reports</p>
						</a>
					</li>
					<li >
						<a href="team_reports.php">
						<i class="nc-icon nc-paper"></i>
						<p>Team Summary</p>
						</a>
					</li>
					<li>
						<a href="logout.php">
							<i class="nc-icon nc-single-02"></i>
							<p>Logout</p>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<!-- End Sidebar -->
		<div class="main-panel">

			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header ">
								Displays the most recent location of user.

								<input id="searchuser"> </input>
								<button onclick="loadData()" class="btn">Search User</button>
							</div>
							<div class="card-body ">
								<div id="map" class="map"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<footer class="footer footer-black  footer-white fixed-bottom">
			<div class="container-fluid">
				<div class="row">
					<div class="credits ml-auto">
						<span class="copyright"> Copyright
							© <script>
								document.write(new Date().getFullYear());
							</script> IT Solutions & DevOps
						</span>
					</div>
				</div>
			</div>
		</footer>

	</div>

	<script src="../assets/js/core/jquery.min.js"></script>
	<script src="../assets/js/core/popper.min.js"></script>
	<script src="../assets/js/core/bootstrap.min.js"></script>
	<script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
	<script src="../assets/js/plugins/chartjs.min.js"></script>
	<script src="../assets/js/plugins/bootstrap-notify.js"></script>
	<script src="../assets/js/paper-dashboard.min.js?v=2.0.1" type="text/javascript"></script>
	<script src="../assets/demo/demo.js"></script>
	<script src="../JS/fontawesome-markers.min.js"></script>

	<script>
		var map;
		const dataArray = [];
		const markerArray = [];

		function initMap() {

			var marker;

			map = new google.maps.Map(document.getElementById('map'), {
				center: new google.maps.LatLng(7.927079, 80.761244),
				zoom: 7,
				// mapTypeId: 'satellite'
			});



		}


		function loadData() {

			var marker;
			directionsDisplay = new google.maps.DirectionsRenderer({
				suppressMarkers: true
			});

			var directionsService = new google.maps.DirectionsService();
			var Coordinates = [];
			var flightPlanCoordinates = [];
			var bounds = new google.maps.LatLngBounds();

			var searchuser = $('#searchuser').val();
			var count = 0;
			var oldlat;
			var oldlon;

			$.get("server.php?q=4&searchuser=" + searchuser, function(data1, status) {

				$.each(data1.datax, function(key, data) {

					count++;


					dataArray.push(data);

					var pinColor = "#FF8C00";

					if (parseInt(data.UTIME) > 60) {

						pinColor = '#FF0000';

					} else if (parseInt(data.UTIME) < 10) {

						pinColor = '#FFFF00';

					} else {

						pinColor = '#00FF00';

					}

					var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor,
						new google.maps.Size(21, 34),
						new google.maps.Point(0, 0),
						new google.maps.Point(10, 34));
					var pinShadow = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_shadow",
						new google.maps.Size(40, 37),
						new google.maps.Point(0, 0),
						new google.maps.Point(12, 35));

					var latLng = new google.maps.LatLng(data.LAT, data.LON);

					var marker = new google.maps.Marker({
						position: latLng,
						map: map,
						//icon: 'img/EXFDP.png',
						title: data.SID,
						//  icon: pinImage,

						icon: {
							path: "M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z",
							fillColor: pinColor,
							fillOpacity: .9,
							anchor: new google.maps.Point(0, 0),
							strokeWeight: 0,
							scale: 0.03
						},
						//shadow: pinShadow
					});

					var details = '<!DOCTYPE html>' +
						'<html>' +
						'<head>' +
						'<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">' +
						'</head>' +
						'<body>' +
						'<table class="table table-borderless" style="width:100%">' +
						'<tr>' +
						'<td><b>Captured User</b></td>' +
						'<td>' + data.SID + ' / ' + data.USR_NAME + '</td>' +
						'</tr>' +
						'<tr>' +
						'<td><b>Last Updated Time</b></td>' +
						'<td>' + data.LOG_DATE + '</td>' +
						'</tr>' +
						'</table>' +
						'</body>' +
						'</html>';

					bindInfoWindow(marker, map, new google.maps.InfoWindow(), details);

					markerArray.push(marker);
					flightPlanCoordinates.push(marker.getPosition());
					bounds.extend(marker.position);

					if(count == 20){
						Coordinates.push(flightPlanCoordinates);
						flightPlanCoordinates = [];
						count = 0;
					}


				});



				map.fitBounds(bounds);

				for (var x = 0; x<Coordinates.length; x++){
				// directions service configuration
				flightPlanCoordinates1 = Coordinates[0];
				var start = flightPlanCoordinates1[0];
				var end = flightPlanCoordinates1[flightPlanCoordinates1.length - 1];
				var waypts = [];
				for (var i = 1; i < flightPlanCoordinates1.length - 1; i++) {
					waypts.push({
						location: flightPlanCoordinates1[i],
						stopover: true
					});
				}
				calcRoute(start, end, waypts);

			}

			});



			function calcRoute(start, end, waypts) {
				var request = {
					origin: start,
					destination: end,
					waypoints: waypts,
					optimizeWaypoints: true,
					travelMode: google.maps.TravelMode.DRIVING
				};
				directionsService.route(request, function(response, status) {
					if (status == google.maps.DirectionsStatus.OK) {
						directionsDisplay.setDirections(response);
						// var route = response.routes[0];
						// var summaryPanel = document.getElementById('directions_panel');
						// summaryPanel.innerHTML = '';
						// // For each route, display summary information.
						// for (var i = 0; i < route.legs.length; i++) {
						// 	var routeSegment = i + 1;
						// 	summaryPanel.innerHTML += '<b>Route Segment: ' + routeSegment + '</b><br>';
						// 	summaryPanel.innerHTML += route.legs[i].start_address + ' to ';
						// 	summaryPanel.innerHTML += route.legs[i].end_address + '<br>';
						// 	summaryPanel.innerHTML += route.legs[i].distance.text + '<br><br>';
						// }
					}
				});
			}



			function bindInfoWindow(marker, map, infowindow, strDescription) {
				google.maps.event.addListener(marker, 'click', function() {
					infowindow.setContent(strDescription);
					infowindow.open(map, marker);
				});
			}





		}
	</script>

</body>

</html>