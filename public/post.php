<?php
/**
 * Record an entry in the Guest Book
 *
 * @author Tom Walder <tom@docnet.nu>
 */
require_once('../vendor/autoload.php');
// require_once('../config.php');
define('GDS_ACCOUNT', ' !! your service account name here !! ');
define('GDS_KEY_FILE', dirname(__FILE__) . '/key.p12');
define('POST_LIMIT', 10);

// Filter vars
// $str_name = substr(filter_input(INPUT_POST, 'guest-name', FILTER_SANITIZE_STRING), 0, 30);
$str_co2 = $_GET['ValeurCO2'];
// $str_message = substr(filter_input(INPUT_POST, 'guest-message', FILTER_SANITIZE_STRING), 0, 1000);
 $str_co = $_GET['ValeurCO'];

// $str_as = (string)base_convert(substr(filter_input(INPUT_POST, 'guest-as', FILTER_SANITIZE_STRING), 0, 20), 36, 10);
// if(!in_array($str_as, [date('YmdH'), date('YmdH', strtotime('-1 hour'))])) {
//     syslog(LOG_WARNING, 'Skipping potential AV spam from [' . $_SERVER['REMOTE_ADDR'] . ']: ' . print_r($_POST, TRUE));
//     header("Location: /?spam=maybe");
//     exit();
// }

// use \GDS\Demo\Spammy;
use \GDS\Demo\Repository;

// VERY crude anti-spam-bot check
// if(Spammy::anyLookSpammy([$str_co2, $str_co])) {
//     syslog(LOG_WARNING, 'Skipping potential spam from [' . $_SERVER['REMOTE_ADDR'] . ']: ' . print_r($_POST, TRUE));
//     header("Location: /?spam=maybe");
// } else {
    syslog(LOG_DEBUG, 'Proceeding... ' . print_r($_SERVER, TRUE) . "\n\n" . print_r($_GET, TRUE));
    $obj_repo = new Repository();
    $obj_repo->createPost($str_co2, $str_co, $_SERVER['REMOTE_ADDR']);
    header("Location: /");
// }
