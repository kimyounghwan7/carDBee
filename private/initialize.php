<?php

ob_start(); /* output buffering is turned on */

session_start(); /* turn on sessions */

/* 
 * Assign file paths to PHP constants
 * __FILE__ returns the current path to this file
 * dirname() returns the path to the parent directory
 */
define("PRIVATE_PATH", dirname(__FILE__));
define("PROJECT_PATH", dirname(PRIVATE_PATH));
define("PUBLIC_PATH", PROJECT_PATH . '/public');
define("SHARED_PATH", PRIVATE_PATH . '/shared');
define("HOME_PATH", '/index.php');
define("WHAT_WE_DO_PATH", '/what-we-do.php');
define("TEAM_MEMBERS_PATH", '/team-members.php');
define("CHOOSE_A_CARD_PATH", '/choose-a-card.php');

require_once('functions.php');              /* basic functions */
require_once('database.php');               /* database connection functions */
require_once('query_functions.php');        /* database query functions */
require_once('validation_functions.php');   /* validations functions */
require_once('auth_functions.php');         /* authentication functions */

$db = db_connect();
$errors = [];           /* global array for error messages */     

?>