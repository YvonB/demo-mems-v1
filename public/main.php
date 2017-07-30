<?php
// Définitions des constantes modèles pour l'accès au datatore
define('GDS_ACCOUNT', ' !! your service account name here !! ');
define('GDS_KEY_FILE', dirname(__FILE__) . '/key.p12');
define('POST_LIMIT', 10);

// Pour rafraîchir la page à chaque 7 seconde
// $page = $_SERVER['PHP_SELF'];
// $sec = "30";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Detection de pollution</title>
    <!-- <meta http-equiv = "refresh" content = "<?php echo $sec ?> ; URL ='<?php echo $page ?>' " charset="utf-8"> -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/demo.css">
    <meta name="author" content="Tom Walder">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1><img src="/img/datastore-logo.png" id="gds-logo" /> PHP & <span class="hidden-xs">Google</span> Cloud Datastore</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <h2>Qu'est-ce que c'est?</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla est purus,<br> ultrices in porttitor
            in, accumsan non quam. Nam consectetur porttitor rhoncus.<br> Curabitur eu est et leo feugiat
            auctor vel quis lorem.</p>
            <p>Ut et ligula dolor, sit amet consequat lorem. Aliquam porta eros sed
            velit imperdiet egestas.</p>
        </div>
        <div class="col-md-4">
            <h2>Resources</h2>
            <p><a href="https://github.com/YvonB/demo-mems-v1" target="_blank"><span aria-hidden="true" class="glyphicon glyphicon-new-window"></span> Pollution detection demo (Ce site web)</a></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8" >
            <h2>Results</h2>
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php
                        try {
                            

                                // Inclusion
                                require_once('../vendor/autoload.php');
                                

                                // Chercher les dernières valeurs insérées
                                $obj_repo = new \GDS\Demo\Repository();
                                $arr_posts = $obj_repo->getRecentPosts();

                                // Les afficher
                                foreach ($arr_posts as $obj_post) {

                                    // Effectuez une belle chaîne d'affichage de date et heure
                                    $int_posted_date = strtotime($obj_post->posted);
                                    $int_date_diff = time() - $int_posted_date;
                                    if ($int_date_diff < 3600) {
                                        $str_date_display = round($int_date_diff / 60) . ' minutes';
                                    } else if ($int_date_diff < (3600 * 24)) {
                                        $str_date_display = round($int_date_diff / 3600) . ' hours';
                                    } else {
                                        $str_date_display = date('\a\t jS M Y, H:i', $int_posted_date);
                                    }

                                    echo '<div class="post">';
                                    echo '<div class="gas">Taux de CO2: ', htmlspecialchars($obj_post->co2),'[ppm]    ', '</div>';
                                    echo '<div class="gas">Taux de CO: ', htmlspecialchars($obj_post->co),'[ppm]    ', '</div>';
                                    echo '<div class="gas">Taux de NH3: ', htmlspecialchars($obj_post->nh3), '[ppm]    ', '<br><span class="time">Il y a ', $str_date_display, '</span></div>';
                                    echo '</div>';
                                }
                                $int_posts = count($arr_posts);
                                echo '<div class="post"><em>Showing last ', $int_posts, '</em></div>';
                        } catch (\Exception $obj_ex) {
                            syslog(LOG_ERR, $obj_ex->getMessage());
                            echo '<em>Whoops, something went wrong!</em>';
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h2>Voir plus de contenu</h2>
            <p>Dans ce cas, connectez-vous ou inscrivez-vous.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="well">
                <h3>SignIn</h3>
                <form method="POST" action="/post.php">
                    <div class="form-group">
                        <input type="text" class="form-control" name="guest-name" id="guest-name" placeholder="Name" maxlength="30" />
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="guest-name" id="guest-name" placeholder="Password" />
                    </div>
                    <input type="hidden" name="guest-as" id="guest-as" value="<?php echo base_convert(date('YmdH'), 10, 36); ?>" />
                    <button type="submit" class="btn btn-primary">Se Connecter</button>
                </form>
            </div>
        </div>

    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
</body>
</html>