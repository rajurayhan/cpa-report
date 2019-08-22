<?php
    session_start();
    if(isset($_SESSION['user_name']) || !empty($_SESSION['user_name'])){
        header('location: index.php');
        exit;
    }
    // DB Credentials
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    // define('DB_PASSWORD', '');
    define('DB_PASSWORD', 'mysql@1');
    // define('DB_NAME', 'sms');
    define('DB_NAME', 'gpwap');

    $loginError = 0;
  
    // Attempt to connect to MySQL
    try {
      $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );
    } catch(PDOException $e) {
      die("ERROR: Could not connect. " . $e->getMessage());
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username      = $_POST['username']; 
        $password   = $_POST['password'];

        $sql = "SELECT * FROM user_login WHERE user_name ='".$username."' AND password = '".$password."' LIMIT 1";

        //echo $sql;
        //echo '<br>';
        $result = $pdo->query($sql);
        $row = $result->fetch(PDO::FETCH_OBJ);
        
        if($row){
            session_start();
            $_SESSION['user_name'] = $username;
            $pdo = null;
            // echo $_SESSION['user_name'];
            header('location: index.php');
          }
        else{
            $loginError = 1;
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Reporting Panel || Login</title>
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

        <script src="assets/js/modernizr.min.js"></script>

    </head>


    <body>

        <section>
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">

                        <div class="wrapper-page">

                            <div class="m-t-40 card-box">
                                <div class="text-center">
                                    <h2 class="text-uppercase m-t-0 m-b-30">
                                        <a href="index.php" class="text-success">
                                            <span><img src="assets/images/logo.png" alt="" height="30"></span>
                                        </a>
                                    </h2>
                                    <!--<h4 class="text-uppercase font-bold m-b-0">Sign In</h4>-->
                                </div>
                                <div class="account-content">
                                    <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

                                        <div class="form-group m-b-20">
                                            <div class="col-xs-12">
                                                <label for="username">Username</label>
                                                <input class="form-control" type="text" name="username" id="username" required="" placeholder="Enter your username">
                                            </div>
                                        </div>

                                        <div class="form-group m-b-20">
                                            <div class="col-xs-12">
                                                <!-- <a href="pages-forget-password.html" class="text-muted pull-right font-14">Forgot your password?</a> -->
                                                <label for="password">Password</label>
                                                <input class="form-control" type="password" name="password" required="" id="password" placeholder="Enter your password">
                                            </div>
                                        </div>

                                        <div class="form-group account-btn text-center m-t-10">
                                            <div class="col-xs-12">
                                                <button class="btn btn-lg btn-primary btn-block" type="submit">Sign In</button>
                                            </div>
                                        </div>
                                        <?php if($loginError){ ?>
                                            <div class="form-group account-btn text-center m-t-10">
                                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    Whoops! Wrong Username or Password
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            </div>
                                        <?php } ?>

                                    </form>

                                    <div class="clearfix"></div>

                                </div>
                            </div>
                            <!-- end card-box-->

                        </div>
                        <!-- end wrapper -->

                    </div>
                </div>
            </div>
        </section>


        <!-- jQuery  -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/metisMenu.min.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>

        <!-- App js -->
        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>

    </body>
</html>