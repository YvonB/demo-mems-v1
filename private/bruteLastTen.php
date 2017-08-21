<!-- ========== Pour visualiser les 10 derniéres résultats brute en ppm =========== -->
            <div class="row">
                <div class="col-md-8" >
                    <h2>Results</h2>
                    <div class="panel panel-default" style="background-color: #cdf;">
                        <div class="panel-body">

                            <?php
                                try 
                                    {   
                                        // On crée un objet de type Repository.
                                        $obj_repo = new \GDS\Demo\Repository();
                                        // Chercher les 10 dernières valeurs insérées
                                        $arr_posts = $obj_repo->getRecentPosts();

                                        // Les afficher
                                        foreach ($arr_posts as $obj_post) 
                                        {

                                            // Effectuez une belle chaîne d'affichage de date et heure
                                            $int_posted_date = strtotime($obj_post->posted);
                                            $int_date_diff = time() - $int_posted_date;

                                            if ($int_date_diff < 3600) 
                                            {
                                                $str_date_display = round($int_date_diff / 60) . ' minute(s)';
                                            } 
                                            else if ($int_date_diff < (3600 * 24)) 
                                            {
                                                $str_date_display = round($int_date_diff / 3600) . ' heure(s)';
                                            } 
                                            else 
                                            {
                                                $str_date_display = date('\a\t jS M Y, H:i', $int_posted_date);
                                            }

                                            echo '<div class="post">';
                                            if(isset($obj_post->co2) AND !empty($obj_post->co2))
                                                {
                                                    echo '<div class="gas">Taux de CO2: <strong>', htmlspecialchars($obj_post->co2),'</strong><em>cm³/m³</em>    ', '</div>';
                                                }
                                            if(isset($obj_post->co) AND !empty($obj_post->co))
                                                {
                                                    echo '<div class="gas">  |  Taux de CO: <strong>', htmlspecialchars($obj_post->co),'</strong><em>cm³/m³</em>    ', '</div>';
                                                }
                                            if(isset($obj_post->nh3) AND !empty($obj_post->nh3))
                                                {
                                                    echo '<div class="gas">  |  Taux de NH3: <strong>', htmlspecialchars($obj_post->nh3), '</strong><em>cm³/m³</em>    ', '<br><span class="time">', $str_date_display, '</span></div>';
                                                }
                                            echo '</div>';
                                        }

                                        $int_posts = count($arr_posts);

                                        echo '<div class="post"><em>Showing last ', $int_posts, '</em></div>';

                                    } 
                                catch (\Exception $obj_ex)
                                {
                                    syslog(LOG_ERR, $obj_ex->getMessage());
                                    echo '<em>Whoops, something went wrong!</em>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        <!-- ================================ Fin Aff brute =============================== -->