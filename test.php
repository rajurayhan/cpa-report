<?php

    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    // define('DB_PASSWORD', '');
    define('DB_PASSWORD', 'mysql@1');

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

    echo $fbchartData;
?>