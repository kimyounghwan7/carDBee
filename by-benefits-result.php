<?php //require_once('../private/initialize.php'); ?>

<?php

ob_start(); 			/* output buffering is turned on */
session_start(); 		/* turn on sessions */

/* database credentials */
define("DB_SERVER", "(DESCRIPTION =(ADDRESS =(PROTOCOL = TCP)
(HOST =203.249.87.162)(PORT = 1521))
(CONNECT_DATA = (SID = orcl)))");
define("DB_USER", "b289040");
define("DB_PASS", "qkrwjdals");

/* database */
function db_connect() {
    $connection = OCILogon(DB_USER, DB_PASS, DB_SERVER);
    confirm_db_connect($connection);
    return $connection;
}
function db_disconnect($connection) {
    if(isset($connection)) {
        OCILogoff($connection);
    }
}
function confirm_db_connect($connection) {
    if(!$connection) {
        $msg = "Database connection failed: ";
        exit($msg);
    }
}
function confirm_result_set($result_set) {
    if (!$result_set) {
        exit("Database query failed.");
    }
}

/* Basic functions */
function url_for($script_path) {
    if ($script_path[0] != '/') {
        $script_path = "/" . $script_path;
    }
    return $script_path;
}
function u($string="") {
    return urlencode($string);
}
function raw_u($string="") {
    return rawurlencode($string);
}
function h($string="") {
    return htmlspecialchars($string);
}
function error_404() {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit();
}
function error_500() {
    header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
    exit();
}
function redirect_to($location) {
    header("Location: " . $location);
    exit;
}
function is_post_request() {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}
function is_get_request() {
    return $_SERVER['REQUEST_METHOD'] == 'GET';
}
function display_errors($errors=array()) {
    $output = '';
    if(!empty($errors)) {
        $output .= "<div class=\"errors\">";
        $output .= "Please fix the following errors:";
        $output .= "<ul>";
        foreach($errors as $error) {
            $output .= "<li>" . h($error) . "</li>";
        }
        $output .= "</ul>";
        $output .= "</div>";
    }
    return $output;
}
function get_and_clear_session_message() {
    if(isset($_SESSION['message']) && $_SESSION['message'] != '') {
        $msg = $_SESSION['message'];
        unset($_SESSION['message']);
        return $msg;
    }
}
function display_session_message() {
    $msg = get_and_clear_session_message();
    if(!is_blank($msg)) {
        return '<div id="message">' . h($msg) . '</div>';
    }
}

/* query functions for this page */
function find_cards_by_benefit_id($id) {
	global $db;
	$sql = "SELECT * FROM card ";
	$sql .= "WHERE benefit_id='" . $id . "' ";
  
	$result = OCIParse($db, $sql);
	confirm_result_set($result);
	OCIExecute($result);
  
	return $result;
}

function find_benefit_by_card_id($id) {
	global $db;
	
	$sql = "SELECT * FROM benefits ";
	$sql .= "WHERE id IN ";
	$sql .= "(SELECT benefit_id FROM card ";
	$sql .= "WHERE id='" . $id . "') ";
  
	$result = OCIParse($db, $sql);
	confirm_result_set($result);
	OCIExecute($result);
	OCIFetchInto($result, $benefit);
	OCIFreeStatement($result);
  
	return $benefit;
}

$db = db_connect();
$errors = [];           /* array for error messages */     

?>

<?php 

if (is_post_request()) {
    $id = $_POST['benefit_id'];
    $card_set = find_cards_by_benefit_id($id);
} else {
    redirect_to(url_for('a_team/a_team1/carDBee/by-benefits.php'));
}

?>

<!DOCTYPE HTML>
<html>
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>carDBee</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="author" content="" />

  <!-- Facebook and Twitter integration -->
	<meta property="og:title" content=""/>
	<meta property="og:image" content=""/>
	<meta property="og:url" content=""/>
	<meta property="og:site_name" content=""/>
	<meta property="og:description" content=""/>
	<meta name="twitter:title" content="" />
	<meta name="twitter:image" content="" />
	<meta name="twitter:url" content="" />
	<meta name="twitter:card" content="" />

	<!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
	<link rel="shortcut icon" href="favicon.ico">

	<link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500,700" rel="stylesheet">
	
	<!-- Animate.css -->
	<link rel="stylesheet" href="css/animate.css">
	<!-- Icomoon Icon Fonts-->
	<link rel="stylesheet" href="css/icomoon.css">
	<!-- Bootstrap  -->
	<link rel="stylesheet" href="css/bootstrap.css">
	<!-- Flexslider  -->
	<link rel="stylesheet" href="css/flexslider.css">
	<!-- Flaticons  -->
	<link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">
	<!-- Owl Carousel -->
	<link rel="stylesheet" href="css/owl.carousel.min.css">
	<link rel="stylesheet" href="css/owl.theme.default.min.css">
	<!-- Theme style  -->
	<link rel="stylesheet" href="css/style.css">

	<!-- Modernizr JS -->
	<script src="js/modernizr-2.6.2.min.js"></script>
	<!-- FOR IE9 below -->
	<!--[if lt IE 9]>
	<script src="js/respond.min.js"></script>
	<![endif]-->

	</head>
	<body>
	<div id="colorlib-page">
		<a href="#" class="js-colorlib-nav-toggle colorlib-nav-toggle"><i></i></a>
		<aside id="colorlib-aside" role="complementary" class="border js-fullheight">
			<h1 id="colorlib-logo"><a href="index.php">Card Bee</a></h1>
			<nav id="colorlib-main-menu" role="navigation">
				<ul>
					<li><a href="index.php">Home</a></li>
					<li><a href="what-we-do.php">What we do</a></li>
					<li><a href="team-members.php">Team members</a></li>
					<li class="colorlib-active"><a href="choose-a-card.php">Choose a card</a></li>
					<li><a href="staff.php">Admin</a></li>
				</ul>
			</nav>

			<div class="colorlib-footer">
				<p><small>&copy; <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="icon-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --> </span> <span>Demo Images: <a href="http://nothingtochance.co/" target="_blank">nothingtochance.co</a></span></small></p>
				<ul>
					<li><a href="#"><i class="icon-facebook2"></i></a></li>
					<li><a href="#"><i class="icon-twitter2"></i></a></li>
					<li><a href="#"><i class="icon-instagram"></i></a></li>
					<li><a href="#"><i class="icon-linkedin2"></i></a></li>
				</ul>
			</div>

		</aside>

		<div id="colorlib-main">
			<div class="colorlib-services">
				<div class="colorlib-narrow-content">
					<div class="row">
						<div class="col-md-6 col-md-offset-3 col-md-pull-3 animate-box" data-animate-effect="fadeInLeft">
							<span class="heading-meta">Choose a card</span>
                            <h2 class="colorlib-heading">By benefits</h2>

                            <a class="back-link" href="<?php echo url_for('recommend-card-by-benefits.php'); ?>">&laquo; Back</a>
                            <p class="colorlib-lead">Cards for you</p>
                            <table class="list">
                            <tr>
                                <th>Name</th>
                                <th>Detail</th>
                            </tr>

                            <?php while(OCIFetchInto($card_set, $card)) { ?>
                                <?php $benefit = find_benefit_by_card_id($card[0]); ?>
                            <tr>
                                <td><?php echo h($card[1]); ?></td>
                                <td><?php echo h($benefit[1]); ?></td>
                            </tr>
                            <?php } ?>
                            </table>
                            <?php OCIFreeStatement($card_set); ?>
                            
						</div>
					</div>	
				</div>
            </div>
            
			<div id="get-in-touch" class="colorlib-bg-color">
				<div class="colorlib-narrow-content">
					<div class="row">
						<div class="col-md-6 animate-box" data-animate-effect="fadeInLeft">
							<h2>Learn more about these cards!</h2>
                        </div>
					</div>
					<div class="row">
						<div class="col-md-6 col-md-offset-3 col-md-pull-3 animate-box" data-animate-effect="fadeInLeft">
							<p><a href="#" class="btn btn-primary btn-learn">Go find out!</a></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- jQuery -->
	<script src="js/jquery.min.js"></script>
	<!-- jQuery Easing -->
	<script src="js/jquery.easing.1.3.js"></script>
	<!-- Bootstrap -->
	<script src="js/bootstrap.min.js"></script>
	<!-- Waypoints -->
	<script src="js/jquery.waypoints.min.js"></script>
	<!-- Flexslider -->
	<script src="js/jquery.flexslider-min.js"></script>
	<!-- Sticky Kit -->
	<script src="js/sticky-kit.min.js"></script>
	<!-- Owl carousel -->
	<script src="js/owl.carousel.min.js"></script>
	<!-- Counters -->
	<script src="js/jquery.countTo.js"></script>
	
	
	<!-- MAIN JS -->
	<script src="js/main.js"></script>

	</body>
</html>

<?php db_disconnect($db); ?>