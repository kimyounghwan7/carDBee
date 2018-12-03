<?php

function url_for($script_path) {
    if ($script_path[0] != '/') {
        $script_path = "/" . $script_path;
    }
    return $script_path;
}

function redirect_to($location) {
    header("Location: " . $location);
    exit;
}

function log_out_admin() {
    unset($_SESSION['admin_id']);
    unset($_SESSION['last_login']);
    unset($_SESSION['username']);
    // session_destroy(); // optional: destroys the whole session
    return true;
}

log_out_admin();

redirect_to(url_for('a_team/a_team1/carDBee/login.php'));

?>
