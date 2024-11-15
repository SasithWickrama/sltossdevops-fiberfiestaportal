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

$sql_table = "
DROP TABLE FF_REPORT2;

CREATE TABLE FF_REPORT2
AS
SELECT FF_ID , FF_LAT CUS_LATITUDE , FF_LON CUS_LONGITUDE , 
CASE WHEN LENGTH(FF_VOICENO) = 3 THEN '' ELSE FF_VOICENO END AS VOICE_NO,
FF_CR CUS_CR , FF_ACC CUS_ACCOUNT ,
CASE WHEN LENGTH(FF_MOBILE) = 2 THEN '' ELSE FF_MOBILE END AS MOBILE_NO,
FF_CATAGORY SALES_CATAGORY , FF_CUSCAT CUS_CATAGORY , FF_FDP FDP , FF_USER ENTERD_BY , FF_STATUSDATE ENTERTED_ON,
(SELECT COUNT(*) FROM FF_OTHERSV WHERE FF_SERVICE = ' D-TV'  AND FF_ID = A.FF_ID)  D_TV,
(SELECT COUNT(*) FROM FF_OTHERSV WHERE FF_SERVICE = 'D-BB'  AND FF_ID = A.FF_ID)  D_BB,
(SELECT COUNT(*) FROM FF_OTHERSV WHERE FF_SERVICE = 'D-TV '||'&'||' D-BB'  AND FF_ID = A.FF_ID)  D_TV_AND_D_BB,
(SELECT COUNT(*) FROM FF_OTHERSV WHERE FF_SERVICE = 'SAT TV'  AND FF_ID = A.FF_ID)  SAT_TV,
(SELECT COUNT(*) FROM FF_OTHERSV WHERE FF_SERVICE = 'Other'  AND FF_ID = A.FF_ID)  'OTHER'
 FROM FF_RECORDS A;

ALTER TABLE FF_REPORT2
    ADD SLT_SERVICE1 VARCHAR (30);

ALTER TABLE FF_REPORT2
    ADD SLT_SERVICE1_SATISFACTION VARCHAR (5);

ALTER TABLE FF_REPORT2
    ADD SLT_SERVICE2 VARCHAR (30);

ALTER TABLE FF_REPORT2
    ADD SLT_SERVICE2_SATISFACTION VARCHAR (5);

ALTER TABLE FF_REPORT2
    ADD SLT_SERVICE3 VARCHAR (30);

ALTER TABLE FF_REPORT2
    ADD SLT_SERVICE3_SATISFACTION VARCHAR (5);

ALTER TABLE FF_REPORT2
    ADD SLT_SERVICE4 VARCHAR (30);

ALTER TABLE FF_REPORT2
    ADD SLT_SERVICE4_SATISFACTION VARCHAR (5);
ALTER TABLE FF_REPORT2
    ADD MORE_SV_EXIST VARCHAR (5); 
    

COMMIT;


DECLARE
    NUM      NUMBER;
    CCOUNT   NUMBER;
    CCT      VARCHAR2 (50);
    SAT      VARCHAR2 (10);

    CURSOR PSTN_NO IS
        SELECT FF_ID
          FROM FF_REPORT2
         WHERE CUS_CR IS NOT NULL;

    CURSOR SLTSV IS
        SELECT ff_cct, FF_SATISFACTION
          FROM FF_SERVICES
         WHERE FF_ID = NUM;
BEGIN
    OPEN PSTN_NO;
    LOOP
        FETCH PSTN_NO INTO NUM;
        EXIT WHEN PSTN_NO%NOTFOUND;

        CCOUNT := 0;

        OPEN SLTSV;
        LOOP
            FETCH SLTSV INTO CCT, SAT;
            EXIT WHEN SLTSV%NOTFOUND;

            IF CCOUNT = 0
            THEN
                UPDATE FF_REPORT2
                   SET SLT_SERVICE1 = CCT, SLT_SERVICE1_SATISFACTION = SAT
                 WHERE FF_ID = NUM;
            END IF;
            
            IF CCOUNT = 1
            THEN
                UPDATE FF_REPORT2
                   SET SLT_SERVICE2 = CCT, SLT_SERVICE2_SATISFACTION = SAT
                 WHERE FF_ID = NUM;
            END IF;
            
            IF CCOUNT = 2
            THEN
                UPDATE FF_REPORT2
                   SET SLT_SERVICE3 = CCT, SLT_SERVICE3_SATISFACTION = SAT
                 WHERE FF_ID = NUM;
            END IF;
            
            IF CCOUNT = 3
            THEN
                UPDATE FF_REPORT2
                   SET SLT_SERVICE4 = CCT, SLT_SERVICE4_SATISFACTION = SAT
                 WHERE FF_ID = NUM;
            END IF;
            
            IF CCOUNT > 3
            THEN
                UPDATE FF_REPORT2
                   SET MORE_SV_EXIST = 'YES'
                 WHERE FF_ID = NUM;
            END IF;

            CCOUNT := CCOUNT+1;

            COMMIT;
        END LOOP;

        CLOSE SLTSV;
    END LOOP;

    CLOSE PSTN_NO;
EXCEPTION
    WHEN OTHERS
    THEN
        ROLLBACK;
END;
";


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
          <li class="active">
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
                <div>
                  Toggle column: <a class="toggle-vis" data-column="2">CUS_CR</a> - <a class="toggle-vis" data-column="3">CUS_ACCOUNT</a> - <a class="toggle-vis" data-column="10">CUS_LATITUDE</a> - <a class="toggle-vis" data-column="11">CUS_LONGITUDE</a> - <a class="toggle-vis" data-column="23">SLT_SERVICE4</a> - <a class="toggle-vis" data-column="24">SLT_SERVICE4_SATISFACTION</a>
                
                </div>
				
				<div class="row">
					<div class="col-md-12 float-right">
					<form method="post" id="myForm" action="export.php" enctype="multipart/form-data">
						<button class="btn btn-md btn-warning" onclick="createExcel();"><i class="fa fa-download"></i> Download Full Report</button>
					</form>
					</div>
				</div>

                <div class="table-responsive">
                  <table class="cell-border" id="tbl_exporttable_to_xls">
                    <thead>
                      <th>ID</th>
                      <th>VOICE_NO</th>
                      <th>CUS_CR</th>
                      <th>CUS_ACCOUNT</th>
                      <th>MOBILE_NO</th>
                      <th>CUSTOMER FEEDBACK</th>
                      <th>SALES_CATAGORY</th>
                      <th>CUS_CATAGORY</th>
                      <th>FDP</th>
                      <th>ENTERD_BY</th>
                      <th>ENTERTED_ON</th>
                      <th>CUS_LATITUDE</th>
                      <th>CUS_LONGITUDE</th>
                      <th>D_TV</th>
                      <th>D_BB</th>
                      <th>D_TV_AND_D_BB</th>
                      <th>SAT_TV</th>
                      <th>OTHER</th>
                      <th>SLT_SERVICE1</th>
                      <th>SLT_SERVICE1_SATISFACTION</th>
                      <th>SLT_SERVICE2</th>
                      <th>SLT_SERVICE2_SATISFACTION</th>
                      <th>SLT_SERVICE3</th>
                      <th>SLT_SERVICE3_SATISFACTION</th>
                      <th>SLT_SERVICE4</th>
                      <th>SLT_SERVICE4_SATISFACTION</th>
                      <th>MORE_SV_EXIST</th>
                    </thead>
                    <tbody>
					
					<?php

					$stid_table = oci_parse($CON, $sql_table);
					oci_execute($stid_table);
					
					$curbdate = date('Y/m/d');

					$sql = "SELECT FF_ID,CUS_LATITUDE,CUS_LONGITUDE,VOICE_NO,CUS_CR,CUS_ACCOUNT,MOBILE_NO,FEEDBACK,
							SALES_CATAGORY,CUS_CATAGORY,FDP,ENTERD_BY,to_char(ENTERTED_ON,'MM/DD/YYYY HH:MI:SS AM' ) ENTERTED_ON,D_TV,D_BB,D_TV_AND_D_BB,
							SAT_TV,OTHER,SLT_SERVICE1,SLT_SERVICE1_SATISFACTION,SLT_SERVICE2,SLT_SERVICE2_SATISFACTION,
							SLT_SERVICE3,SLT_SERVICE3_SATISFACTION,SLT_SERVICE4,SLT_SERVICE4_SATISFACTION,MORE_SV_EXIST
							FROM FF_REPORT2 
							WHERE to_char(ENTERTED_ON,'yyyy/mm/dd') = '".$curbdate."'
							ORDER BY FF_ID";

					$stid = oci_parse($CON, $sql);
					oci_execute($stid);

					while ($row = oci_fetch_array($stid)) {

					?>

                        <tr>
                          <td><?php echo $row['FF_ID'] ?></td>
                          <td><?php echo $row['VOICE_NO'] ?></td>
                          <td><?php echo $row['CUS_CR'] ?></td>
                          <td><?php echo $row['CUS_ACCOUNT'] ?></td>
                          <td><?php echo $row['MOBILE_NO'] ?></td>
                          <td><?php echo $row['FEEDBACK'] ?></td>
                          <td><?php echo $row['SALES_CATAGORY'] ?></td>
                          <td><?php echo $row['CUS_CATAGORY'] ?></td>
                          <td><?php echo $row['FDP'] ?></td>
                          <td><?php echo $row['ENTERD_BY'] ?></td>
                          <td><?php echo $row['ENTERTED_ON'] ?></td>
                          <td><?php echo $row['CUS_LATITUDE'] ?></td>
                          <td><?php echo $row['CUS_LONGITUDE'] ?></td>
                          <td><?php echo $row['D_TV'] ?></td>
                          <td><?php echo $row['D_BB'] ?></td>
                          <td><?php echo $row['D_TV_AND_D_BB'] ?></td>
                          <td><?php echo $row['SAT_TV'] ?></td>
                          <td><?php echo $row['OTHER'] ?></td>
                          <td><?php echo $row['SLT_SERVICE1'] ?></td>
                          <td><?php echo $row['SLT_SERVICE1_SATISFACTION'] ?></td>
                          <td><?php echo $row['SLT_SERVICE2'] ?></td>
                          <td><?php echo $row['SLT_SERVICE2_SATISFACTION'] ?></td>
                          <td><?php echo $row['SLT_SERVICE3'] ?></td>
                          <td><?php echo $row['SLT_SERVICE3_SATISFACTION'] ?></td>
                          <td><?php echo $row['SLT_SERVICE4'] ?></td>
                          <td><?php echo $row['SLT_SERVICE4_SATISFACTION'] ?></td>
                          <td><?php echo $row['MORE_SV_EXIST'] ?></td>
                        </tr>

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
  var table = $('#tbl_exporttable_to_xls').DataTable({
	dom: 'Bfrtip',
	"pageLength": 7,
	buttons: [
	  'copy', 'csv', 'excel', 'pdf', 'print'
	]
  });

  $('a.toggle-vis').on('click', function (e) {
	e.preventDefault();
	// Get the column API object
	var column = table.column($(this).attr('data-column'));

	// Toggle the visibility
	column.visible(!column.visible());
});


// const interval = setInterval(function() {
//   location.reload();
	// 	}, 44000);

});

function ExportToExcel(type, fn, dl) {
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
}


function createExcel() {

	document.getElementById("myForm").submit();

}

</script>

</body>

</html>