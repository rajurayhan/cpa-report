<?php
    session_start();
    if(!isset($_SESSION['user_name']) || empty($_SESSION['user_name'])){
        header('location: login.php');
        exit;
    }

    // $dbHost     = 'localhost';
    // $dbUser     = 'root';
    // $dbPass     = 'mysql@1';

    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    // define('DB_PASSWORD', '');
    define('DB_PASSWORD', 'mysql@1');


    define('TODAY', date('Y-m-d'));
    // define('TODAY', '2019-08-23');

    $fbactivation     = 0;

    function getFunBoxDailyActivationData()
    {
        $activation     = 0;
        try {
            $dbName     = 'cpa';
            $pdo        = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . $dbName, DB_USERNAME, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );
        } catch(PDOException $e) {
          die("ERROR: Could not connect. " . $e->getMessage());
        }

        // Banglalink Data

        $query  = "SELECT COUNT(msisdn) as activation FROM cpaClick_blink WHERE DATE(d_date) ='".TODAY."'";

        $result = $pdo->query($query);
        $row    = $result->fetch(PDO::FETCH_OBJ);
        //$pdo    = null;
        if($row){
            $activation+= $row->activation;
        }

        // Robi Data

        $query  = "SELECT COUNT(msisdn) as activation FROM cpaClick_robi WHERE DATE(d_date) ='".TODAY."'";

        $result = $pdo->query($query);
        $row    = $result->fetch(PDO::FETCH_OBJ);
        //$pdo    = null;
        if($row){
            $activation+= $row->activation;
        }

        $pdo    = null;

        return $activation;
    }

    function getFunBoxDailyDeactivationData(){
        $deactivation     = 0;
        try {
            $pdo    = new PDO( "mysql:host=".DB_SERVER.";charset=UTF-8", DB_USERNAME, DB_PASSWORD );
        } catch(PDOException $e) {
          die("ERROR: Could not connect. " . $e->getMessage());
        }
        // Blink Data
        $pdo->exec('USE gpwap');
        $query  = "SELECT COUNT(msisdn) as deactivation FROM subscribers WHERE DATE(unsubs_date) ='".TODAY."' AND status = '0'";

        $result = $pdo->query($query);
        $row    = $result->fetch(PDO::FETCH_OBJ);

        if($row){
            $deactivation+= $row->deactivation;
        }

        // Robi Data
        $pdo->exec('USE sms');
        $query  = "SELECT COUNT(msisdn) as deactivation FROM robi_subscribers WHERE DATE(unsubs_date) ='".TODAY."' AND status = '0' AND service = 'fb'";

        $result = $pdo->query($query);
        $row    = $result->fetch(PDO::FETCH_OBJ);

        if($row){
            $deactivation+= $row->deactivation;
        }

        $pdo    = null;

        return $deactivation;
    }

    function getCyCasDailyActivationData()
    {
        $activation     = 0;
        try {
            $dbName     = 'sms';
            $pdo        = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . $dbName, DB_USERNAME, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );
        } catch(PDOException $e) {
          die("ERROR: Could not connect. " . $e->getMessage());
        }

        $query  = "SELECT COUNT(msisdn) as activation FROM zcycas_cpa_airtel_cysgame WHERE DATE(d_date)='".TODAY."'";

        $result = $pdo->query($query);
        $row    = $result->fetch(PDO::FETCH_OBJ);
        //$pdo    = null;
        if($row){
            $activation+= $row->activation;
        }

        return $activation;
    }

    function getCyCasDailyDeactivationData()
    {
        $deactivation     = 0;
        try {
            $dbName     = 'sms';
            $pdo        = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . $dbName, DB_USERNAME, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );
        } catch(PDOException $e) {
          die("ERROR: Could not connect. " . $e->getMessage());
        }

        $query  = "SELECT COUNT(msisdn) as deactivation FROM robi_subscribers WHERE DATE(unsubs_date)='".TODAY."' AND status = '0' AND service = 'cycas'";

        $result = $pdo->query($query);
        $row    = $result->fetch(PDO::FETCH_OBJ);
        //$pdo    = null;
        if($row){
            $deactivation+= $row->deactivation;
        }

        return $deactivation;
    }

    function cycasChartData()
    {
        try {
            $dbName     = 'sms';
            $pdo        = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . $dbName, DB_USERNAME, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );
        } catch(PDOException $e) {
          die("ERROR: Could not connect. " . $e->getMessage());
        }

        $today      = date('Y-m-d'); //Current Date
        $lastweek   = date('Y-m-d', strtotime('-6 days', strtotime($today))); 

        // Activation

        $query  = "SELECT COUNT(msisdn) as activation, DATE(d_date) as date FROM zcycas_cpa_airtel_cysgame WHERE DATE(d_date) BETWEEN '".$lastweek."' AND '".$today."' GROUP BY date";

        $result         = $pdo->query($query);
        $activation     = $result->fetchAll(PDO::FETCH_OBJ);

        foreach ($activation as $a) {
                $activationArtr[$a->date] = $a->activation;
            }

        $query  = "SELECT COUNT(msisdn) as deactivation, DATE(date_added) as date FROM zcycas_unsub_log WHERE DATE(date_added) BETWEEN '".$lastweek."' AND '".$today."' GROUP BY date";

        $result             = $pdo->query($query);
        $deactivation       = $result->fetchAll(PDO::FETCH_OBJ);

        foreach ($deactivation as $d) {
                $deactivationArtr[$d->date] = $d->deactivation;
            }

        $extendedDate   = date('Y-m-d', strtotime('+1 days', strtotime($today))); 

        $period = new DatePeriod(
             new DateTime($lastweek),
             new DateInterval('P1D'),
             new DateTime($extendedDate)
        );

        $chartData = array();

        foreach ($period as $key => $value) {
            $date   = $value->format('Y-m-d') ;
            if($activationArtr[$date]){
                $a_count    = $activationArtr[$date];
            }
            else{
                $a_count    = 0;
            }

            if($deactivationArtr[$date]){
                $d_count    = $deactivationArtr[$date];
            }
            else{
                $d_count    = 0;
            }

            $chartData [] = array(
                            'date'          => date("d", strtotime($date)), 
                            'activation'    => $a_count, 
                            'deactivation'  => $d_count
                        );

        }

        $chartData =  json_encode($chartData);

        return $chartData;
    }

    function FBactivationChart()
    {
        try {
            $dbName     = 'cpa';
            $pdo        = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . $dbName, DB_USERNAME, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );
        } catch(PDOException $e) {
          die("ERROR: Could not connect. " . $e->getMessage());
        }

        $today      = date('Y-m-d'); //Current Date
        $lastweek   = date('Y-m-d', strtotime('-6 days', strtotime($today))); 

        // Activation Banglalink

        $query  = "SELECT COUNT(msisdn) as activation, d_date as date FROM cpaClick_blink WHERE d_date BETWEEN '".$lastweek."' AND '".$today."' GROUP BY date";

        $result         = $pdo->query($query);
        $activation     = $result->fetchAll(PDO::FETCH_OBJ);

        foreach ($activation as $a) {
                $activationArtr[$a->date] = $a->activation;
            }


        // Activation Robi

        $query  = "SELECT COUNT(msisdn) as activation, d_date as date FROM cpaClick_robi WHERE d_date BETWEEN '".$lastweek."' AND '".$today."' GROUP BY date";

        $result             = $pdo->query($query);
        $robiactivation     = $result->fetchAll(PDO::FETCH_OBJ);

        foreach ($robiactivation as $robi_a) {
                $robiactivationArtr[$robi_a->date] = $robi_a->activation;
            }


        $extendedDate   = date('Y-m-d', strtotime('+1 days', strtotime($today))); 

        $period = new DatePeriod(
             new DateTime($lastweek),
             new DateInterval('P1D'),
             new DateTime($extendedDate)
        );

        $FBchartData = array();

        // $totalActivation = 0;

        foreach ($period as $key => $value) {
            $totalActivation = 0;
            $date   = $value->format('Y-m-d') ;
            if($activationArtr[$date]){
                $bla_count = $activationArtr[$date];
            }
            else{
                $bla_count = 0;
            }

            if($robiactivationArtr[$date]){
                $robia_count = $robiactivationArtr[$date];
            }
            else{
                $robia_count = 0;
            }

            $totalActivation    = $bla_count + $robia_count;

            $fbchartData [] = array(
                                'date'          => date("d", strtotime($date)), 
                                'activation'    => $totalActivation
                            );
        }

        $fbchartData =  json_encode($fbchartData);

        return $fbchartData;
    }
    
    // var_dump(FBactivationChart());
    //var_dump(getCyCasDailyDeactivationData());
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Reporting Panel || Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="Full Reporting Panel for Adbox Services" name="description" />
        <meta content="Raju Rayhan" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!--Morris Chart CSS -->
        <link rel="stylesheet" href="assets/plugins/morris/morris.css">

        <!-- App css -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/metismenu.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/style.css" rel="stylesheet" type="text/css" />

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
                                <h4 class="header-title m-t-0 m-b-20">Dashboard</h4>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card-box widget-inline">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="widget-inline-box text-center">
                                                <h3 class="m-t-10"><i class="text-success mdi mdi-movie"></i> <b><?php echo getFunBoxDailyActivationData(); ?></b></h3>
                                                <p class="text-muted">Funbox Activation Today</p>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <div class="widget-inline-box text-center">
                                                <h3 class="m-t-10"><i class="text-danger mdi mdi-movie"></i> <b><?php echo getFunBoxDailyDeactivationData(); ?></b></h3>
                                                <p class="text-muted">Funbox Deactivation Today</p>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <div class="widget-inline-box text-center">
                                                <h3 class="m-t-10"><i class="text-success mdi mdi-gamepad-variant"></i> <b><?php echo getCyCasDailyActivationData(); ?></b></h3>
                                                <p class="text-muted">CYCAS Activation Today</p>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <div class="widget-inline-box text-center b-0">
                                                <h3 class="m-t-10"><i class="text-danger mdi mdi-gamepad-variant"></i> <b><?php echo getCyCasDailyDeactivationData(); ?></b></h3>
                                                <p class="text-muted">CYCAS Deactivation Today</p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end row -->


                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card-box">
                                    <h6 class="m-t-0">Cycas Games Report (Last 7 days)</h6>
                                    <div class="text-center">
                                        <ul class="list-inline chart-detail-list">
                                            <li class="list-inline-item">
                                                <p class="font-normal"><i class="fa fa-circle m-r-10 text-success"></i>Activation</p>
                                            </li>
                                            <li class="list-inline-item">
                                                <p class="font-normal"><i class="fa fa-circle m-r-10 text-danger"></i>Deactivation</p>
                                            </li>
                                        </ul>
                                    </div>
                                    <div id="cycas-chart" style="height: 300px;"></div>
                                </div>
                            </div> <!-- end col -->

                            <div class="col-lg-6">
                                <div class="card-box">
                                    <h6 class="m-t-0">Funbox Activation (Last 7 days)</h6>
                                    <div class="text-center">
                                        <ul class="list-inline chart-detail-list">
                                            <li class="list-inline-item">
                                                <p class="font-weight-bold"><i class="fa fa-circle m-r-10 text-success"></i>Activation</p>
                                            </li>
                                        </ul>
                                    </div>
                                    <div id="fb-activation-chart" style="height: 300px;"></div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->


                        <!-- <div class="row">
                            <div class="col-sm-12">
                                <div class="card-box">
                                    <h6 class="m-t-0">Contacts</h6>
                                    <div class="table-responsive">
                                        <table class="table table-hover mails m-0 table table-actions-bar">
                                            <thead>
                                                <tr>
                                                    <th style="min-width: 95px;">
                                                        <div class="checkbox checkbox-primary checkbox-single m-r-15">
                                                            <input id="action-checkbox" type="checkbox">
                                                            <label for="action-checkbox"></label>
                                                        </div>
                                                    </th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Products</th>
                                                    <th>Start Date</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="checkbox checkbox-primary m-r-15">
                                                            <input id="checkbox2" type="checkbox">
                                                            <label for="checkbox2"></label>
                                                        </div>

                                                        <img src="assets/images/users/avatar-2.jpg" alt="contact-img" title="contact-img" class="rounded-circle thumb-sm" />
                                                    </td>

                                                    <td>
                                                        Tomaslau
                                                    </td>

                                                    <td>
                                                        <a href="#" class="text-muted">tomaslau@dummy.com</a>
                                                    </td>

                                                    <td>
                                                        <b><a href="" class="text-dark"><b>356</b></a> </b>
                                                    </td>

                                                    <td>
                                                        01/11/2003
                                                    </td>

                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div> -->

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

        <!--Morris Chart-->
        <script src="assets/plugins/morris/morris.min.js"></script>
        <script src="assets/plugins/raphael/raphael-min.js"></script>

        <!-- Dashboard init -->
        <script src="assets/pages/jquery.dashboard.js"></script>

        <!-- App js -->
        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>

    </body>
    <script type="text/javascript">
        
        $(document).ready(function(){
            var cycasChart  = <?php echo cycasChartData(); ?>;
            var fbChart     = <?php echo FBactivationChart(); ?>;
            
            Morris.Bar({
              element: 'cycas-chart',
              // stacked: true,
              data: cycasChart,
              xkey: 'date',
              ykeys: ['activation', 'deactivation'],
              labels: ['Activation', 'Deactivation'],
              barColors: ['#4fc55b', '#d57171']
            });

            Morris.Bar({
              element: 'fb-activation-chart',
              // stacked: true,
              data: fbChart,
              xkey: 'date',
              ykeys: ['activation'],
              labels: ['Activation'],
              barColors: ['#4fc55b']
            });
        });
    </script>
</html>