<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Détéction de Pollution</title>

	<!-- script pour la courbe -->
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	<script src="https://code.highcharts.com/stock/highstock.js"></script>
	<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
	<!-- ********************** -->
	<style type="text/css">
		.highcharts-credits
		{display: none;}
	</style>
</head>
<body>

	<!-- div permettant de visualiser la courbe -->
	<div id="container" style="height: 400px; min-width: 250px">
		
	</div>
	
	<!-- le script de la courbe lui même -->
	<script type="text/javascript">
		$.getJSON('https://www.highcharts.com/samples/data/jsonp.php?filename=new-intraday.json&callback=?', function (data) 
		{

    // create the chart
    Highcharts.stockChart('container', {


        title: {
            text: 'CO2 values by minute'
        },

        subtitle: {
            text: 'Using ordinal X axis'
        },

        xAxis: {
            gapGridLineWidth: 0
        },

        rangeSelector: {
            buttons: [{
                type: 'hour',
                count: 1,
                text: '1h'
            }, {
                type: 'day',
                count: 1,
                text: '1D'
            }, {
                type: 'all',
                count: 1,
                text: 'All'
            }],
            selected: 1,
            inputEnabled: false
        },

        series: [{
            name: 'CO2',
            type: 'area',
            data: data,
            gapSize: 5,
            tooltip: {
                valueDecimals: 2
            },
            fillColor: {
                linearGradient: {
                    x1: 0,
                    y1: 0,
                    x2: 0,
                    y2: 1
                },
                stops: [
                    [0, Highcharts.getOptions().colors[0]],
                    [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                ]
            },
            threshold: null
        }]
    });
});

	</script>
<!-- ***************************************** -->

</body>
</html>