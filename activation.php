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
	$adv   	   = $_POST["adv"];

	$db 	= new database($operator, $service, $fromDate, $toDate, $adv, 'cpaReport');
	$db->connect();
	$data = $db->getCPAData();
    // var_dump($data);
	$db->close();
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Reporting Panel || Activation</title>
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
                                <h3 class="header-title m-t-0 m-b-20">Activation Report</h3>
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
                                        <select class="form-control" id="service" name="service" required>
                                            <option value="" selected="">Service</option>
                                            <option value="fb">FunBox</option>
                                            <option value="cycas">CYCAS</option>
                                        </select>
                                    </div>

                                    <div class="input-group mb-2 col-sm-2">                
                                        <select required="" name="adv" class="form-control" id="adv" data-toggle="tooltip" data-placement="auto" title="" data-original-title="Advertiser">
                                            <option value="" selected="">Advertiser</option>
                                            <option value="bitterstrawberry">BitterStrawberry</option>
                                            <option value="tiger">Tiger Ads</option>
                                            <option value="kimia">Kimia Solvers</option>
                                            <option value="level23">Level23</option>
                                            <option value="google">Google Ads</option>
                                        </select>
                                    </div>

                                    <div class="input-group mb-2 col-sm-2">                
                                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <h4 class='header-title m-t-0 m-b-20'></h4>
                                <?php 
                                    if($data){
                                        $total = 0;
                                        echo "<h4 class='header-title m-t-0 m-b-20'>Report: ". ucfirst($operator) ." -- ". strtoupper($service) ." -- ". ucfirst($adv) ."</h4>"; ?>
                                        <table class="table table-hover" id="datatable-buttons">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Activation</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    foreach($data as $d){
                                                        echo '<tr>';
                                                            echo '<td>'.$d->date.'</td>';
                                                            echo '<td>'.$d->activation.'</td>';
                                                        echo '</tr>';
                                                        $total += $d->activation;
                                                    }
                                                ?>
                                            </tbody>
                                        </table>

                                        <h4>TOTAL: <?php echo $total; ?></h4>

                                    <?php }
                                    ?>

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

        <!-- Datepicker -->

        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>

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

    <?php if(isset($adv)){ ?>
        <script>
            $('#adv').val('<?php echo $adv; ?>');
        </script>
    <?php } ?>

</html>