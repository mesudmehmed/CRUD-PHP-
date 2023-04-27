<?php
// Initialize the session
session_start();

include 'header.php';
include 'menu.php';
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
    <!-- Page content-->
    <div class="container">
        <h1 class="my-5"><i class="fa-sharp fa-solid fa-user-secret"></i> Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
    </div>
    <!-- Bootstrap core JS-->
<?php include 'footer.php' ?>