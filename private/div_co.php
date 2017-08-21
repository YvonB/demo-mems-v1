<?php    
    // Pour notre lib
    require_once('../vendor/autoload.php');
?>
<html>
<head>
	<!-- script pour la courbe -->
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
	<!-- ********************** -->
</head>	
</html>
<div align="center">  
        <h4>Monoxyde de Carbone</h4>
        <div id="co" style="height: 400px; min-width: 310px"></div>
</div>

<!-- pour récupérer les valeurs dans la BD -->
<?php
        try
            {   
                // ========Appel de notre modèle

                // On crée un objet de type Repository.
                $obj_repo = new \GDS\Demo\Repository();
                // Chercher juste la dernière valeure insérée récemment.
                $arr_posts = $obj_repo->getLatestRecentPost();

                // =========fin appel de notre modèle

               // val de co en ppm
                if(isset($arr_posts)){$ppm_co = $arr_posts->co;}   
            }
            catch(\Exception $obj_ex)
                {
                    syslog(LOG_ERR, $obj_ex->getMessage());
                    echo '<em>Whoops, something went wrong!</em>';
                }
     
    ?>
<!-- ========================= fin récupération ========================= -->


<!-- ======================= le script de la courbe lui même =============== -->
<script type="text/javascript">
   $(document).ready(function () {
    Highcharts.setOptions({
        global: {
            useUTC: false
        }
    });

    Highcharts.chart('co', {
        chart: {
            type: 'spline',
            animation: Highcharts.svg, // Ne pas animer dans l'ancien IE
            marginRight: 10,
            events: {
                load: function () {

                    // Configurer la mise à jour du graphique chaque 4 seconde
                    var series = this.series[0];
                    setInterval(function () {
                        var x = (new Date()).getTime(), // heure actuelle
                            y = <?php echo $ppm_co ; ?>; // les valeurs en ppm sur l'axe des abscisses
                        series.addPoint([x, y], true, true);
                    }, 4000);
                }
            }
        },
        title: {
            text: 'Live co data'
        },
        xAxis: {
            type: 'datetime',
            tickPixelInterval: 150 // 15 sec : l'intervalle de temps sur l'axe des abscisses
        },
        yAxis: {
            title: {
                text: 'Value in ppm'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            formatter: function () {
                return '<b>' + this.series.name + '</b><br/>' +
                    Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
                    Highcharts.numberFormat(this.y, 2);
            }
        },
        legend: {
            enabled: false
        },
        exporting: {
            enabled: false
        },
        series: [{
            name: 'co',
            data: (function () {
                var data = [],
                    time = (new Date()).getTime(),
                    i;

                for (i = -19; i <= 0; i += 1) {
                    data.push({
                        x: time + i * 1000,
                        y: <?php echo $ppm_co ; ?> // les valeurs en ppm sur l'axe des abscisses
                    });
                }
                return data;
            }())
        }]
    });
});
</script>
<!-- ================== fin script ======================== -->