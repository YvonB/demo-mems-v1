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

use \GDS\Demo\Repository; // On importe la classe Repository.

syslog(LOG_DEBUG, 'Proceeding... ' . print_r($_SERVER, TRUE) . "\n\n" . print_r($_GET, TRUE));

$obj_repo = new Repository(); // On crée une nouvelle instance de cette classe
$obj_repo->createPost($str_co2, $str_co, $str_nh3); // Pour crée une entité avec les valeurs des params d'url, ie les 3 gazs polluants.

header("Location: /");

