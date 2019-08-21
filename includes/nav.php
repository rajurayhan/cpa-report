<div class="topbar">

    <!-- LOGO -->
    <div class="topbar-left">
        <a href="index.php" class="logo">
            <span>
                <img src="assets/images/logo.png" alt="">
            </span>
            <i>
                <img src="assets/images/logo_sm.png" alt="">
            </i>
        </a>
    </div>

    <nav class="navbar-custom">

        <ul class="list-unstyled topbar-right-menu float-right mb-0">
            
            <li class="dropdown notification-list">
                <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
                aria-haspopup="false" aria-expanded="false">
                <img src="https://avatars1.githubusercontent.com/u/6824950?s=460&v=4" alt="user" class="rounded-circle"> <span class="ml-1"> <?php echo ucfirst($_SESSION['user_name']); ?> <i class="mdi mdi-chevron-down"></i> </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                <!-- item-->
                <div class="dropdown-item noti-title">
                    <h6 class="text-overflow m-0">Welcome !</h6>
                </div>

                <!-- item-->
                <a href="logout.php" class="dropdown-item notify-item">
                    <i class="ti-power-off"></i> <span>Logout</span>
                </a>

            </div>
        </li>

    </ul>

    <ul class="list-inline menu-left mb-0">
        <li class="float-left">
            <button class="button-menu-mobile open-left waves-light waves-effect">
                <i class="mdi mdi-menu"></i>
            </button>
        </li>
    </ul>

</nav>

</div>
<!-- Top Bar End -->


<!-- ========== Left Sidebar Start ========== -->
<div class="left side-menu">
    <div class="user-details">
        <div class="pull-left">
            <img src="https://avatars1.githubusercontent.com/u/6824950?s=460&v=4" alt="" class="thumb-md rounded-circle">
        </div>
        <div class="user-info">
            <a href="#"><?php echo ucfirst($_SESSION['user_name']); ?></a>
            <p class="text-muted m-0">Administrator</p>
        </div>
    </div>

    <!--- Sidemenu -->
    <div id="sidebar-menu">
        <!-- Left Menu Start -->
        <ul class="metismenu" id="side-menu">
            <li class="menu-title">Navigation</li>
            <li>
                <a href="index.php">
                    <i style="color: #fd6732" class="ti-home"></i><span> Dashboard </span>
                </a>
            </li>

            <li>
                <a href="activation.php">
                    <i style="color: green" class="ti-check"></i><span> Activation Report</span>
                </a>
            </li>

            <li>
                <a href="deactivation.php">
                    <i style="color: red" class="ti-close"></i><span> Deactivation Report</span>
                </a>
            </li>

            <li>
                <a href="duration.php">
                    <i style="color: blue" class="ti-timer"></i><span> Duration Report</span>
                </a>
            </li>

        </ul>

    </div>
    <!-- Sidebar -->
    <div class="clearfix"></div>

</div>