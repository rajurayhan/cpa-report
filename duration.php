<?php
require_once 'includes/database.class.php';

session_start();
if(!isset($_SESSION['user_name']) || empty($_SESSION['user_name'])){
    header('location: login.php');
    exit;
}

$data = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$fromDate  = $_POST["fromDate"];
	$toDate    = $_POST["toDate"];
	$operator  = $_POST["operator"];
	$service   = $_POST["service"];
	$adv 	   = null; 
	$db 	= new database($operator, $service, $fromDate, $toDate, $adv, 'baseDuration');
	$db->connect();
	$data = $db->getDurationData();
	//var_dump($data);
	$db->close();
	foreach ($data as $d) {		
		$msisdn 	= $d->msisdn;              
        $subDate 	= date_create($d->subs_date);
		$unsubDate 	= date_create($d->unsubs_date);
		$diff 		= date_diff($subDate,$unsubDate);

		$duration 	= $diff->format("%a");
		// if($duration>0){
        //     $processedData[] = array(
        //             'msisdn' 		=> $msisdn,
        //             'days'   		=> $duration, 
        //             'subs_date'		=> $d->subs_date, 
        //             'unsubs_date'	=> $d->unsubs_date
        //     );
        // }
        $processedData[] = array(
                'msisdn' 		=> $msisdn,
                'days'   		=> $duration, 
                'subs_date'		=> $d->subs_date, 
                'unsubs_date'	=> $d->unsubs_date
        );
	}
	//var_dump($processedData);
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Reporting Panel || Duration</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="Full Reporting Panel for Adbox Services" name="description" />
        <meta content="Raju Rayhan" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- App css -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/metismenu.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/style.css" rel="stylesheet" type="text/css" />

        <!-- Datepicker -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" rel="stylesheet">

        <!-- DataTables -->
        <link href="assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <!-- Responsive datatable examples -->
        <link href="assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <script src="assets/js/modernizr.min.js"></script>

    </head>


    <body>

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Top Bar Start -->
            <?php 
                require_once 'includes/nav.php';
            ?>
            <!-- Left Sidebar End -->



            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="header-title m-t-0 m-b-20">Duration Report</h3>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-inline" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                                    <label class="sr-only" for="fromDate">From Date</label>
                                    <label class="sr-only" for="fromDate">fROM Date</label>
                                    <div class="input-group mb-2 col-sm-2">
                                        <input type="text" placeholder="From Date" class="datepicker form-control" id="fromDate"  name="fromDate" autocomplete="off" value="<?php if(isset($fromDate)) echo $fromDate ?>">
                                    </div>

                                    <label class="sr-only" for="toDate">To Date</label>
                                    <div class="input-group mb-2 col-sm-2">
                                        <input type="text" placeholder="To Date" class="datepicker form-control" id="toDate"  name="toDate" autocomplete="off" value="<?php if(isset($toDate)) echo $toDate ?>">
                                    </div>

                                    <div class="input-group mb-2 col-sm-2">
                                        <select class="form-control" name="operator" id="operator" required>
                                            <option value="" selected="">Operator</option>
                                            <option value="blink">Blink</option>
                                            <option value="airtel">Airtel</option>
                                            <option value="robi">Robi</option>
                                        </select>
                                    </div>

                                    <div class="input-group mb-2 col-sm-2">                
                                        <select class="form-control" name="service" id="service" required>
                                            <option value="" selected="">Service</option>
                                            <option value="fb">FunBox</option>
                                            <option value="cycas">CYCAS</option>
                                        </select>
                                    </div>

                                    <div class="input-group mb-2 col-sm-2">                
                                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                            <h4 class='header-title m-t-0 m-b-20'></h4>
                                <?php if(isset($processedData) && sizeof($processedData)>0){ 
                                   echo "<h4 class='header-title m-t-0 m-b-20'>Report: ". ucfirst($operator) ." -- ". strtoupper($service) ." </h4>"; ?>
                                <table class="table table-hover" id="datatable-buttons">
                                    <thead>
                                        <tr>
                                            <th>MSISDN</th>
                                            <th>Duration (Day)</th>
                                            <th>Subscribed at</th>
                                            <th>Unsubscribed at</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                <?php }?>
                                        <?php if(isset($processedData) && sizeof($processedData)>0){ 
                                            foreach ($processedData as $data) { ?>
                                                <tr>
                                                    <td><?php echo $data['msisdn']; ?></td>
                                                    <td><?php echo $data['days']; ?></td>
                                                    <td><?php echo $data['subs_date']; ?></td>
                                                    <td><?php echo $data['unsubs_date']; ?></td>
                                                </tr>
                                            <?php 	
                                                }
                                            ?>
                                            
                                        <?php }
                                    ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div> <!-- container -->


                    <div class="footer">   
                        <div class="pull-right hide-phone">
                            Design and Developed By <strong class="text-custom"><a href="https://m.me/raju.rayhan" target="_blank">CodePoet</a></strong>.
                        </div>                      
                        <div>
                            <strong>Adbox Bangladesh</strong> - Copyright © <?php echo date('Y') ?>
                        </div>
                    </div>

                </div> <!-- content -->

            </div>


            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->



        <!-- jQuery  -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/metisMenu.min.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>

        <!-- Datepicker -->

        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>

        <!-- Required datatable js -->
        <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
        <!-- Buttons examples -->
        <script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
        <script src="assets/plugins/datatables/buttons.bootstrap4.min.js"></script>
        <script src="assets/plugins/datatables/jszip.min.js"></script>
        <script src="assets/plugins/datatables/pdfmake.min.js"></script>
        <script src="assets/plugins/datatables/vfs_fonts.js"></script>
        <script src="assets/plugins/datatables/buttons.html5.min.js"></script>
        <script src="assets/plugins/datatables/buttons.print.min.js"></script>

        <!-- Responsive examples -->
        <script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
        <script src="assets/plugins/datatables/responsive.bootstrap4.min.js"></script>

        <!-- App js -->
        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>

    </body>

    <script>
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });

        //Buttons examples
        var table = $('#datatable-buttons').DataTable({
            lengthChange: false,
            buttons: ['copy', 'excel', 'pdf']
        });
        table.buttons().container()
                        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
    </script>

    <?php if(isset($operator)){ ?>
        <script>
            $('#operator').val('<?php echo $operator; ?>');
        </script>
    <?php } ?>

    <?php if(isset($service)){ ?>
        <script>
            $('#service').val('<?php echo $service; ?>');
        </script>
    <?php } ?>

</html>