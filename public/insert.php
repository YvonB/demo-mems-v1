<?php
/**
 * Enregistrer une entrée dans la BDD
 *
 * @author Yvon Benahita
 */
require_once('../vendor/autoload.php');
// Définitions des constantes modèles pour l'accès au datatore
define('GDS_ACCOUNT', ' !! your service account name here !! ');
define('GDS_KEY_FILE', dirname(__FILE__) . '/key.p12');
define('POST_LIMIT', 10);

$str_co2 = $_GET['ValeurCO2'];
$str_co = $_GET['ValeurCO'];
$str_nh3 = $_GET['ValeurNH3'];

use \GDS\Demo\Repository;

    syslog(LOG_DEBUG, 'Proceeding... ' . print_r($_SERVER, TRUE) . "\n\n" . print_r($_GET, TRUE));
    $obj_repo = new Repository();
    $obj_repo->createPost($str_co2, $str_co, $str_nh3);
    header("Location: /");

