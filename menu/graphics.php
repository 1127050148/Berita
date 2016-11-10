<script src="assets/highcharts/js/jquery.min.js"></script>
<script src="assets/highcharts/js/highcharts.js"></script>
<script type="text/javascript">
	var chart;
	$(document).ready(function() {
    	chart = new Highcharts.Chart({
    	chart: {
      		renderTo: 'container',
      		defaultSeriesType: 'column'
    	},
		title: {
			text: 'Accurate Percentage And Execution Time'
    	},
    	subtitle: {
    		text: 'K-Nearest Neighbour and Naive Bayes Method'
    	},
    	xAxis: {
    		categories: [
				'K-Nearest Neighbour',  
				'Naive Bayes'
			]
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Values'
			}
		},	
		legend: {
			layout: 'vertical',
			backgroundColor: '#FFFFFF',
			align: 'left',
			verticalAlign: 'top',
			x: 100,
			y: 70,
			floating: true,
			shadow: true
		},	
		tooltip: {
			formatter: function() {
				// return ''+ this.x +': '+ this.y +'';
				return 'The value for <b>' + this.x + '</b> is <b>' + this.y + '</b> in '+ this.series.name;
			}		
		},
		plotOptions: {
			column: {
				pointPadding: 0.2,
				borderWidth: 0
			}
		},	
		series: [{
				name: 'Accuracy (%)',
				data: [62.5, 54.17]

			}, {
				name: 'Execution Time (second)',
				data: [48.9456, 726.158]    
			}]
		});
    });
</script>

<div class = "container">
	<div class="page-header">
    	<h1>Compared Graphics</h1>
    </div>
	<div class = "row">
		<div id="container" style="width: 800px; height: 400px; margin: 0 auto"></div>
		</div>
		<hr>
		<center>
        	<footer>
            	<p>&copy; Siti Nurpadilah (1127050148).</p>
        	</footer>
    	</center>
	</div>        
</div>