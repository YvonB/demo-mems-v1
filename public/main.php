<?php
define('GDS_ACCOUNT', ' !! your service account name here !! ');
define('GDS_KEY_FILE', dirname(__FILE__) . '/key.p12');
define('POST_LIMIT', 10);

// Pour rafraîchir la page à chaque 7 seconde
$page = $_SERVER['PHP_SELF'];
$sec = "30";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Detection de pollution</title>
    <meta http-equiv = "refresh" content = "<?php echo $sec ?> ; URL ='<?php echo $page ?>' " charset="utf-8">
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
            <h2>What the?</h2>
            <p>So here is a super simple web app using the <a href="https://github.com/tomwalder/php-gds">php-gds</a> library to access Google Datastore.</p>
            <p>I'm not using any advanced features - it's just to illustrate how easy it is to get going.</p>
        </div>
        <div class="col-md-4">
            <h2>Resources</h2>
            <p><a href="https://github.com/tomwalder/php-gds" target="_blank"><span aria-hidden="true" class="glyphicon glyphicon-new-window"></span> The php-gds library on GitHub</a></p>
            <p><a href="https://github.com/tomwalder/php-gds-demo" target="_blank"><span aria-hidden="true" class="glyphicon glyphicon-new-window"></span> The php-gds-demo application (this web site)</a></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h2>Totally Epic Web Scale Guest Book</h2>
            <p>Yeah, you know it - the ubiquitous guest book example application.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="well">
                <h3>Sign Guest Book</h3>
                <form method="POST" action="/post.php">
                    <div class="form-group">
                        <input type="text" class="form-control" name="guest-name" id="guest-name" placeholder="Name" maxlength="30" />
                    </div>
                    <div class="form-group">
                        <textarea rows="3" class="form-control" name="guest-message" id="guest-message" placeholder="Message" maxlength="1000"></textarea>
                    </div>
                    <input type="hidden" name="guest-as" id="guest-as" value="<?php echo base_convert(date('YmdH'), 10, 36); ?>" />
                    <button type="submit" class="btn btn-primary">Post</button>
                </form>
            </div>
            <?php
                $bol_spam = FALSE;
                if(isset($_GET['spam']) && 'maybe' == $_GET['spam']) {
                    echo '<span class="alert alert-warning">Perhaps you are trying to spam? Post ignored.</spam>';
                    $bol_spam = TRUE;
                }
            ?>
        </div>
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php
                        try {
                            if(!$bol_spam) {

                                // Includes
                                require_once('../vendor/autoload.php');
                                // require_once('../config.php');

                                // Grab posts
                                $obj_repo = new \GDS\Demo\Repository();
                                $arr_posts = $obj_repo->getRecentPosts();

                                // Show them
                                foreach ($arr_posts as $obj_post) {

                                    // Work out a nice datetime display string
                                    $int_posted_date = strtotime($obj_post->posted);
                                    $int_date_diff = time() - $int_posted_date;
                                    if ($int_date_diff < 3600) {
                                        $str_date_display = round($int_date_diff / 60) . ' minutes ago';
                                    } else if ($int_date_diff < (3600 * 24)) {
                                        $str_date_display = round($int_date_diff / 3600) . ' hours ago';
                                    } else {
                                        $str_date_display = date('\a\t jS M Y, H:i', $int_posted_date);
                                    }

                                    echo '<div class="post">';
                                    echo '<div class="message">', htmlspecialchars($obj_post->message), '</div>';
                                    echo '<div class="authored">By ', htmlspecialchars($obj_post->name), ' ', $str_date_display, '</div>';
                                    echo '</div>';
                                }
                                $int_posts = count($arr_posts);
                                echo '<div class="post"><em>Showing last ', $int_posts, '</em></div>';

                            }
                        } catch (\Exception $obj_ex) {
                            syslog(LOG_ERR, $obj_ex->getMessage());
                            echo '<em>Whoops, something went wrong!</em>';
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
</body>
</html>