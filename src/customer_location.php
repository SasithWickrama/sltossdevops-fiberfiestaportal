<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

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

		#over_map {
			position: absolute;
			top: 110px;
			right: 20px;
			z-index: 99;
			background-color: rgba(0, 0, 0, 0.6);
			color: #ffffff;
			padding: 5px;
			border-radius: 5px;
			font-family: Times New Roman, Times, serif;
			font-size: 12px;
			width: 14%;
			font-weight: bold;
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
					<li class="active">
						<a href="customer_location.php">
							<i class="nc-icon nc-pin-3"></i>
							<p>Customer Location</p>
						</a>
					</li>
					<li>
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
						<a href="user_reports1.php">
						<i class="nc-icon nc-paper"></i>
						<p>User Summary</p>
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
			<nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
				<div class="container">
					<div class="collapse navbar-collapse justify-content-end" id="navigation">
						<ul class="form navbar-nav">

                            <li class="nav-item btn-rotate dropdown">

                                <?php
                                $sql = "select DISTINCT TEAM from FF_TEAM where SNO IN (SELECT DISTINCT FF_USER FROM FF_RECORDS ) order by Team";

                                $stid = oci_parse($CON, $sql);
                                oci_execute($stid);
                                ?>
                                <select class="form-control" id="cmbFFTeam">
                                    <option value="" hidden>Sales Team</option>
                                    <?php
                                    while ($row = oci_fetch_array($stid)) {
                                        ?>
                                        <option value="<?php echo $row['TEAM'] ?>"><?php echo $row['TEAM'] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>

                            </li>
                            <div class="spacing"></div>
							<li class="nav-item btn-rotate dropdown">

								<?php
								$sql = "SELECT DISTINCT FF_CATAGORY FROM FF_RECORDS order by FF_CATAGORY";

								$stid = oci_parse($CON, $sql);
								oci_execute($stid);

								?>

								<select class="form-control" id="cmbSaleCat">

									<option value="" hidden>Sale Category</option>

									<?php
									while ($row = oci_fetch_array($stid)) {
									?>

										<option value="<?php echo $row['FF_CATAGORY'] ?>"><?php echo $row['FF_CATAGORY'] ?></option>

									<?php
									}
									?>
								</select>

							</li>
							<div class="spacing"></div>
							<li class="nav-item btn-rotate dropdown">

								<?php
								$sql = "SELECT DISTINCT FF_CUSCAT FROM FF_RECORDS order by FF_CUSCAT";

								$stid = oci_parse($CON, $sql);
								oci_execute($stid);
								?>
								<select class="form-control" id="cmbFFRec">
									<option value="" hidden>Customer Category</option>
									<?php
									while ($row = oci_fetch_array($stid)) {
									?>
										<option value="<?php echo $row['FF_CUSCAT'] ?>"><?php echo $row['FF_CUSCAT'] ?></option>
									<?php
									}
									?>
								</select>

							</li>
							<div class="spacing"></div>
							<button onclick="loadData()" class="btn btn-primary"><span class="fa fa-search"></span> Get Data </button>
						</ul>
					</div>
				</div>
			</nav>

			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header ">
								Today Customer Locations
							</div>
							<div class="card-body ">
								<div id="map" class="map"></div>

								<div id="over_map">

									<div class="form-group">
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-1" style="height:20px;background-color:#00FF00"></div>
												<div class="col-md-9">Closed Sales</div>
											</div>
										</div>
									</div>

									<div class="form-group">
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-1" style="height:20px;background-color:#FF0000"></div>
												<div class="col-md-9">Not Interested</div>
											</div>
										</div>
									</div>

									<div class="form-group">
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-1" style="height:20px;background-color:#FFA500"></div>
												<div class="col-md-9">Future Sales</div>
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

			initMap();

			var marker;

			var SaleCat = $('#cmbSaleCat').val();
			var FFRec = $('#cmbFFRec').val();
            var FFTeam = $('#cmbFFTeam').val();

			$.get("server.php?q=1&SaleCat=" + SaleCat + "&FFRec=" + FFRec+ "&FFTeam=" + FFTeam + "", function(data1, status) {

				$.each(data1.datax, function(key, data) {

					//dataArray.push(data);

					var pinColor = "FF8C00";
					var iconx = "M280.37 148.26L96 300.11V464a16 16 0 0 0 16 16l112.06-.29a16 16 0 0 0 15.92-16V368a16 16 0 0 1 16-16h64a16 16 0 0 1 16 16v95.64a16 16 0 0 0 16 16.05L464 480a16 16 0 0 0 16-16V300L295.67 148.26a12.19 12.19 0 0 0-15.3 0zM571.6 251.47L488 182.56V44.05a12 12 0 0 0-12-12h-56a12 12 0 0 0-12 12v72.61L318.47 43a48 48 0 0 0-61 0L4.34 251.47a12 12 0 0 0-1.6 16.9l25.5 31A12 12 0 0 0 45.15 301l235.22-193.74a12.19 12.19 0 0 1 15.3 0L530.9 301a12 12 0 0 0 16.9-1.6l25.5-31a12 12 0 0 0-1.7-16.93z";

					if (data.FF_CATAGORY == 'Closed Sale') {

						pinColor = '00FF00';

					} else if (data.FF_CATAGORY == 'Not Interested') {

						pinColor = 'FF0000';

					} else if (data.FF_CATAGORY == 'Future Sale') {

						pinColor = 'FFA500';

					}


					if (data.FF_CUSCAT == 'Business') {
						var iconx = "M436 480h-20V24c0-13.255-10.745-24-24-24H56C42.745 0 32 10.745 32 24v456H12c-6.627 0-12 5.373-12 12v20h448v-20c0-6.627-5.373-12-12-12zM128 76c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40c0 6.627-5.373 12-12 12h-40c-6.627 0-12-5.373-12-12V76zm0 96c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40c0 6.627-5.373 12-12 12h-40c-6.627 0-12-5.373-12-12v-40zm52 148h-40c-6.627 0-12-5.373-12-12v-40c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40c0 6.627-5.373 12-12 12zm76 160h-64v-84c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v84zm64-172c0 6.627-5.373 12-12 12h-40c-6.627 0-12-5.373-12-12v-40c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40zm0-96c0 6.627-5.373 12-12 12h-40c-6.627 0-12-5.373-12-12v-40c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40zm0-96c0 6.627-5.373 12-12 12h-40c-6.627 0-12-5.373-12-12V76c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40z";
					}

					var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor,
						new google.maps.Size(21, 34),
						new google.maps.Point(0, 0),
						new google.maps.Point(10, 34));
					var pinShadow = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_shadow",
						new google.maps.Size(40, 37),
						new google.maps.Point(0, 0),
						new google.maps.Point(12, 35));

					var latLng = new google.maps.LatLng(data.FF_LAT, data.FF_LON);

					var marker = new google.maps.Marker({
						position: latLng,
						map: map,
						//icon: 'img/EXFDP.png',
						title: data.FF_VOICENO,
						// icon: pinImage,
						// shadow: pinShadow
						icon: {
							path: iconx , // "M280.37 148.26L96 300.11V464a16 16 0 0 0 16 16l112.06-.29a16 16 0 0 0 15.92-16V368a16 16 0 0 1 16-16h64a16 16 0 0 1 16 16v95.64a16 16 0 0 0 16 16.05L464 480a16 16 0 0 0 16-16V300L295.67 148.26a12.19 12.19 0 0 0-15.3 0zM571.6 251.47L488 182.56V44.05a12 12 0 0 0-12-12h-56a12 12 0 0 0-12 12v72.61L318.47 43a48 48 0 0 0-61 0L4.34 251.47a12 12 0 0 0-1.6 16.9l25.5 31A12 12 0 0 0 45.15 301l235.22-193.74a12.19 12.19 0 0 1 15.3 0L530.9 301a12 12 0 0 0 16.9-1.6l25.5-31a12 12 0 0 0-1.7-16.93z",
							fillColor: '#'+pinColor,
							fillOpacity: .9,
							anchor: new google.maps.Point(0, 0),
							strokeWeight: 0,
							scale: 0.035
						},
					});

					var details = '';

					details = '<!DOCTYPE html>' +
						'<html>' +
						'<head>' +
						'<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">' +
						'</head>' +
						'<body>' +
						'<table class="table table-borderless" style="width:100%">' +
						'<tr>' +
						'<td><b>Sales Person SN</b></td>' +
						'<td>' + data.FF_USER + '</td>' +
						'</tr>' +
						'<tr>' +
						'<td><b>Sales Person Name</b></td>' +
						'<td>' + data.USR_NAME + '</td>' +
						'</tr>' +
						'<tr>' +
						'<td><b>Sales Person Number</b></td>' +
						'<td>' + data.MOBILENO + '</td>' +
						'</tr>' +
						'<tr>' +
						'<td><b>Voice No</b></td>' +
						'<td>' + data.FF_VOICENO + '</td>' +
						'</tr>' +
						'<tr>' +
						'<td><b>FDP</b></td>' +
						'<td>' + data.FF_FDP + '</td>' +
						'</tr>' +
						'<tr>' +
						'<td><b>CR</b></td>' +
						'<td>' + data.FF_CR + '</td>' +
						'</tr>' +
						'<tr>' +
						'<td><b>Account No</b></td>' +
						'<td>' + data.FF_ACC + '</td>' +
						'</tr>' +
						'<tr>' +
						'<td><b>Mobile No</b></td>' +
						'<td>' + data.FF_MOBILE + '</td>' +
						'</tr>' +
						'<tr><td colspan="2"><b>SLT Services</b><td></tr>' +
						'<tr><td colspan="2"><table class="table table-striped table-bordered" style="width:100%"><tr><th>Circuit</th><th>Satisfaction</th></tr>';



					$.get("server.php?q=3&FF_ID=" + data.FF_ID + "", function(data2, status) {

						$.each(data2.dataslt, function(key, data) {


							details += '<tr><td>' + data.FF_CCT + '</td><td>' + data.FF_SATISFACTION + '</td></tr>';



						});

						details += '</table></td></tr><tr><td colspan="2"><b>Other Services</b><td></tr>' +
							'<tr><td colspan="2"><table class="table table-striped table-bordered" style="width:100%"><tr><th>Service</th></tr>';

						$.each(data2.dataNslt, function(key, data) {


							details += '<tr><td>' + data.FF_SERVICE + '</td></tr>';



						});

						details += '</table></td></tr></table>' +
							'</body>' +
							'</html>';

						bindInfoWindow(marker, map, new google.maps.InfoWindow(), details);

						markerArray.push(marker);

					});

				});
			});

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