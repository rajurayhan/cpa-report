<?php
    session_start();
    if(!isset($_SESSION['user_name']) || empty($_SESSION['user_name'])){
        header('location: login.php');
        exit;
    }
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
                                                <h3 class="m-t-10"><i class="text-primary mdi mdi-access-point-network"></i> <b>8954</b></h3>
                                                <p class="text-muted">Lifetime total sales</p>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <div class="widget-inline-box text-center">
                                                <h3 class="m-t-10"><i class="text-custom mdi mdi-airplay"></i> <b>7841</b></h3>
                                                <p class="text-muted">Income amounts</p>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <div class="widget-inline-box text-center">
                                                <h3 class="m-t-10"><i class="text-info mdi mdi-black-mesa"></i> <b>6521</b></h3>
                                                <p class="text-muted">Total users</p>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <div class="widget-inline-box text-center b-0">
                                                <h3 class="m-t-10"><i class="text-danger mdi mdi-cellphone-link"></i> <b>325</b></h3>
                                                <p class="text-muted">Total visits</p>
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
                                    <h6 class="m-t-0">Total Revenue</h6>
                                    <div class="text-center">
                                        <ul class="list-inline chart-detail-list">
                                            <li class="list-inline-item">
                                                <p class="font-normal"><i class="fa fa-circle m-r-10 text-primary"></i>Series A</p>
                                            </li>
                                            <li class="list-inline-item">
                                                <p class="font-normal"><i class="fa fa-circle m-r-10 text-muted"></i>Series B</p>
                                            </li>
                                        </ul>
                                    </div>
                                    <div id="dashboard-bar-stacked" style="height: 300px;"></div>
                                </div>
                            </div> <!-- end col -->

                            <div class="col-lg-6">
                                <div class="card-box">
                                    <h6 class="m-t-0">Sales Analytics</h6>
                                    <div class="text-center">
                                        <ul class="list-inline chart-detail-list">
                                            <li class="list-inline-item">
                                                <p class="font-weight-bold"><i class="fa fa-circle m-r-10 text-primary"></i>Mobiles</p>
                                            </li>
                                            <li class="list-inline-item">
                                                <p class="font-weight-bold"><i class="fa fa-circle m-r-10 text-info"></i>Tablets</p>
                                            </li>
                                        </ul>
                                    </div>
                                    <div id="dashboard-line-chart" style="height: 300px;"></div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->


                        <div class="row">
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

                                            <tr>
                                                <td>
                                                    <div class="checkbox checkbox-primary m-r-15">
                                                        <input id="checkbox1" type="checkbox">
                                                        <label for="checkbox1"></label>
                                                    </div>

                                                    <img src="assets/images/users/avatar-1.jpg" alt="contact-img" title="contact-img" class="rounded-circle thumb-sm" />
                                                </td>

                                                <td>
                                                    Chadengle
                                                </td>

                                                <td>
                                                    <a href="#" class="text-muted">chadengle@dummy.com</a>
                                                </td>

                                                <td>
                                                    <b><a href="" class="text-dark"><b>568</b></a> </b>
                                                </td>

                                                <td>
                                                    01/11/2003
                                                </td>

                                            </tr>

                                            <tr>
                                                <td>
                                                    <div class="checkbox checkbox-primary m-r-15">
                                                        <input id="checkbox3" type="checkbox">
                                                        <label for="checkbox3"></label>
                                                    </div>

                                                    <img src="assets/images/users/avatar-3.jpg" alt="contact-img" title="contact-img" class="rounded-circle thumb-sm" />
                                                </td>

                                                <td>
                                                    Stillnotdavid
                                                </td>

                                                <td>
                                                    <a href="#" class="text-muted">stillnotdavid@dummy.com</a>
                                                </td>
                                                <td>
                                                    <b><a href="" class="text-dark"><b>201</b></a> </b>
                                                </td>

                                                <td>
                                                    12/11/2003
                                                </td>

                                            </tr>

                                            <tr>
                                                <td>
                                                    <div class="checkbox checkbox-primary m-r-15">
                                                        <input id="checkbox4" type="checkbox">
                                                        <label for="checkbox4"></label>
                                                    </div>

                                                    <img src="assets/images/users/avatar-4.jpg" alt="contact-img" title="contact-img" class="rounded-circle thumb-sm" />
                                                </td>

                                                <td>
                                                    Kurafire
                                                </td>

                                                <td>
                                                    <a href="#" class="text-muted">kurafire@dummy.com</a>
                                                </td>

                                                <td>
                                                    <b><a href="" class="text-dark"><b>56</b></a> </b>
                                                </td>

                                                <td>
                                                    14/11/2003
                                                </td>

                                            </tr>

                                            <tr>
                                                <td>
                                                    <div class="checkbox checkbox-primary m-r-15">
                                                        <input id="checkbox5" type="checkbox">
                                                        <label for="checkbox5"></label>
                                                    </div>

                                                    <img src="assets/images/users/avatar-5.jpg" alt="contact-img" title="contact-img" class="rounded-circle thumb-sm" />
                                                </td>

                                                <td>
                                                    Shahedk
                                                </td>

                                                <td>
                                                    <a href="#" class="text-muted">shahedk@dummy.com</a>
                                                </td>

                                                <td>
                                                    <b><a href="" class="text-dark"><b>356</b></a> </b>
                                                </td>

                                                <td>
                                                    20/11/2003
                                                </td>

                                            </tr>

                                            <tr>
                                                <td>
                                                    <div class="checkbox checkbox-primary m-r-15">
                                                        <input id="checkbox6" type="checkbox">
                                                        <label for="checkbox6"></label>
                                                    </div>

                                                    <img src="assets/images/users/avatar-6.jpg" alt="contact-img" title="contact-img" class="rounded-circle thumb-sm" />
                                                </td>

                                                <td>
                                                    Adhamdannaway
                                                </td>

                                                <td>
                                                    <a href="#" class="text-muted">adhamdannaway@dummy.com</a>
                                                </td>

                                                <td>
                                                    <b><a href="" class="text-dark"><b>956</b></a> </b>
                                                </td>

                                                <td>
                                                    24/11/2003
                                                </td>

                                            </tr>


                                            </tbody>
                                        </table>
                                    </div>
                                </div>
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

        <!--Morris Chart-->
        <script src="assets/plugins/morris/morris.min.js"></script>
        <script src="assets/plugins/raphael/raphael-min.js"></script>

        <!-- Dashboard init -->
        <script src="assets/pages/jquery.dashboard.js"></script>

        <!-- App js -->
        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>

    </body>
</html>