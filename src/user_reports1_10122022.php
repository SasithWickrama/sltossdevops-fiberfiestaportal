<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();

if ($_SESSION['loggedin'] != true) {
  echo '<script type="text/javascript"> document.location = "../index.php";</script>';
}

$users = array();

include 'dbcon.php';


?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8" />
  <!-- <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png"> -->
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

  <link href="../assets/css/jquery.dataTables.min.css" rel="stylesheet" />

  <style>
    select option[disabled]:first-child {
      display: none;
    }

    .spacing {
      width: 10px;
    }
  </style>

  <script src="JS/geoxml3.js"></script>
  <script src="JS/walk.js"></script>
  <script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
  <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


</head>

<body>

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
          <li>
            <a href="user_location.php">
              <i class="nc-icon nc-single-02"></i>
              <p>User Location</p>
            </a>
          </li>
          <li >
            <a href="user_reports.php">
              <i class="nc-icon nc-paper"></i>
              <p>User Reports</p>
            </a>
          </li>
          <li class="active">
            <a href="user_reports1.php">
              <i class="nc-icon nc-paper"></i>
              <p>User Summary</p>
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
      <!-- Navbar -->
      <!-- <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
        <div class="container">
          <div class="collapse navbar-collapse justify-content-end" id="navigation">
            <ul class="form navbar-nav">
              <button onclick="ExportToExcel('xlsx')" class="btn btn-primary"><span class="fa fa-search"></span> Download Data </button>
            </ul>
          </div>
        </div>
      </nav> -->
      <!-- End Navbar -->
      <div class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <!-- <div class="card-header">
                <h4 class="card-title"> Simple Table</h4>
              </div> -->
              <div class="card-body">

                <nav>
				  <div class="nav nav-tabs" id="nav-tab" role="tablist">
					<button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Today Summery</button>
					<button class="nav-link" id="nav-ovrlsummry-tab" data-bs-toggle="tab" data-bs-target="#nav-ovrlsummry" type="button" role="tab" aria-controls="nav-ovrlsummry" aria-selected="false">Overall Summery</button>
				  </div>
				</nav>
				
				<div class="tab-content" id="nav-tabContent">
				  
				  <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
					
					<br/>
					
					<div class="table-responsive">
					<table class="cell-border" id="tbl_tdsummery">
                    <thead>
                      <th>Team</th>
                      <th>Service No</th>
                      <th>All Visits</th>
                      <th>Future_Sales</th>
                      <th>Closed_Sales</th>
                      <th>Not_Interested</th>
                      <th>Blank</th>
                    </thead>
                    <tbody>
                      <?php

                      $stid_table = oci_parse($CON, $sql_table);
                      oci_execute($stid_table);
					
					  $curbdate = date('Y/m/d');

                      $sql = "select (select  TEAM from FF_TEAM where sno = ENTERD_BY ) TEAM, ENTERD_BY ,
                      (select count(*) from FF_REPORT2 x where   x.ENTERD_BY = a.ENTERD_BY) A5 , 
                      (select count(*) from FF_REPORT2 x where  x.SALES_CATAGORY = 'Future Sale' and x.ENTERD_BY = a.ENTERD_BY) A1 ,
                      (select count(*) from FF_REPORT2 x where  x.SALES_CATAGORY = 'Closed Sale' and x.ENTERD_BY = a.ENTERD_BY) A2 ,
                      (select count(*) from FF_REPORT2 x where  x.SALES_CATAGORY = 'Not Interested' and x.ENTERD_BY = a.ENTERD_BY) A3 ,
                      (select count(*) from FF_REPORT2 x where  x.SALES_CATAGORY is null and x.ENTERD_BY = a.ENTERD_BY) A4 
                      from FF_REPORT2 a
                      where ENTERD_BY is not  null
					  and to_char(ENTERTED_ON,'yyyy/mm/dd') = '".$curbdate."'
                      group by  ENTERD_BY ";

                      $stid = oci_parse($CON, $sql);
                      oci_execute($stid);

                      while ($row = oci_fetch_array($stid)) {

                      ?>

                        <tr>
                          <td><?php echo $row['TEAM'] ?></td>
                          <td><?php echo $row['ENTERD_BY'] ?></td>
                          <td><?php echo $row['A5'] ?></td>
                          <td><?php echo $row['A1'] ?></td>
                          <td><?php echo $row['A2'] ?></td>
                          <td><?php echo $row['A3'] ?></td>
                          <td><?php echo $row['A4'] ?></td></tr>

                      <?php

                      }

                      ?>

                    </tbody>
					</table>
					</div>
					
				  </div>
				  
				  <div class="tab-pane fade" id="nav-ovrlsummry" role="tabpanel" aria-labelledby="nav-ovrlsummry-tab">
					
					</br>
					
					<div class="table-responsive">
					<table class="cell-border" id="tbl_ovrlsum">
                    <thead>
                      <th>Team</th>
                      <th>Service No</th>
                      <th>All Visits</th>
                      <th>Future_Sales</th>
                      <th>Closed_Sales</th>
                      <th>Not_Interested</th>
                      <th>Blank</th>
                    </thead>
                    <tbody>
                      <?php

                      $stid_table = oci_parse($CON, $sql_table);
                      oci_execute($stid_table);


                      $sql = "select (select  TEAM from FF_TEAM where sno = ENTERD_BY ) TEAM, ENTERD_BY ,
                      (select count(*) from FF_REPORT2 x where   x.ENTERD_BY = a.ENTERD_BY) A5 , 
                      (select count(*) from FF_REPORT2 x where  x.SALES_CATAGORY = 'Future Sale' and x.ENTERD_BY = a.ENTERD_BY) A1 ,
                      (select count(*) from FF_REPORT2 x where  x.SALES_CATAGORY = 'Closed Sale' and x.ENTERD_BY = a.ENTERD_BY) A2 ,
                      (select count(*) from FF_REPORT2 x where  x.SALES_CATAGORY = 'Not Interested' and x.ENTERD_BY = a.ENTERD_BY) A3 ,
                      (select count(*) from FF_REPORT2 x where  x.SALES_CATAGORY is null and x.ENTERD_BY = a.ENTERD_BY) A4 
                      from FF_REPORT2 a
                      where ENTERD_BY is not  null
                      group by  ENTERD_BY ";

                      $stid = oci_parse($CON, $sql);
                      oci_execute($stid);

                      while ($row = oci_fetch_array($stid)) {

                      ?>

                        <tr>
                          <td><?php echo $row['TEAM'] ?></td>
                          <td><?php echo $row['ENTERD_BY'] ?></td>
                          <td><?php echo $row['A5'] ?></td>
                          <td><?php echo $row['A1'] ?></td>
                          <td><?php echo $row['A2'] ?></td>
                          <td><?php echo $row['A3'] ?></td>
                          <td><?php echo $row['A4'] ?></td></tr>

                      <?php

                      }

                      ?>

                    </tbody>
					</table>
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
                  document.write(new Date().getFullYear())
                </script> IT Solutions & DevOps
              </span>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/jquery.min.js"></script>
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <!--  Google Maps Plugin    -->
  <!-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyACf3KGqbKylkAoI4MkjKTdwlbdoCMD-rY&libraries=geometry,drawing&callback=initMap" type="text/javascript"></script> -->
  <!-- Chart JS -->
  <script src="../assets/js/plugins/chartjs.min.js"></script>
  <!--  Notifications Plugin    -->
  <script src="../assets/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/paper-dashboard.min.js?v=2.0.1" type="text/javascript"></script><!-- Paper Dashboard DEMO methods, don't include it in your project! -->
  <script src="../assets/demo/demo.js"></script>

  <script src="../JS/jquery.dataTables.min.js"></script>
  <script src="../JS/dataTables.buttons.min.js"></script>
  <script src="../JS/buttons.html5.min.js"></script>

  <script>
    $(document).ready(function() {
      
		var table1 = $('#tbl_tdsummery').DataTable({
			dom: 'Bfrtip',
			"pageLength": 7,
			buttons: [
			  'copy', 'csv', 'excel', 'pdf', 'print'
			]
		});

		$('a.toggle-vis').on('click', function (e) {
			e.preventDefault();
			// Get the column API object
			var column = table1.column($(this).attr('data-column'));

			// Toggle the visibility
			column.visible(!column.visible());
		});
		
		
		var table2 = $('#tbl_ovrlsum').DataTable({
			dom: 'Bfrtip',
			"pageLength": 7,
			buttons: [
			  'copy', 'csv', 'excel', 'pdf', 'print'
			]
		});

		$('a.toggle-vis').on('click', function (e) {
			e.preventDefault();
			// Get the column API object
			var column = table2.column($(this).attr('data-column'));

			// Toggle the visibility
			column.visible(!column.visible());
		});

    });

   /*function ExportToExcel(type, fn, dl) {
      var elt = document.getElementById('tbl_exporttable_to_xls');
      var wb = XLSX.utils.table_to_book(elt, {
        sheet: "sheet1"
      });
      return dl ?
        XLSX.write(wb, {
          bookType: type,
          bookSST: true,
          type: 'base64'
        }) :
        XLSX.writeFile(wb, fn || ('FiberFiesta_Report.' + (type || 'xlsx')));
    }*/
	
  </script>

</body>

</html>