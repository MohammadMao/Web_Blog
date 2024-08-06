<?php

session_start();
// connect to database
include_once 'db_connection.php';
error_reporting(0);

// different navigation option depends on login state 
if (!isset($_SESSION['user_id'])){ ?>
    <li><a href="../views/login.html"><center><span class="material-symbols-outlined">login</span></center>Login</a></li>
<?php } elseif (isset($_SESSION['user_id'])){ ?>
    <li><a href="../views/dashboard.php"><center><span class="material-symbols-outlined">space_dashboard</span></center>Dashboard</a></li>
    <li><a href="../php_processes/logout.php"><center><span class="material-symbols-outlined">logout</span></center>Logout</a></li>
<?php } ?>
