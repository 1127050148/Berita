<html>
<head>
<title>HTMLData Sample | jQuery Plugin for FusionCharts Free</title>
<script type="text/javascript" src="../JS/jquery-1.4.2.js"></script>
<script type="text/javascript" src="../JS/jquery.fusioncharts.js"></script>
</head>
<body>
	<center>
	<div>Total Penjualan Komputer 2010 (dalam unit)</div>

	<table id="myHTMLTable" border="0" align="center" cellpadding="5">
	<tr bgcolor="#FF9900"> <th>Bulan</th> <th>Total Penjualan</th></tr>
<?php
      include "fungsi_indotgl.php";
      mysql_connect("localhost", "root", "") ;
      mysql_select_db("pintar");

      $result = mysql_query("SELECT bulan, SUM(total_penjualan) as total FROM penjualan GROUP BY bulan");
      while ($data = mysql_fetch_array($result)) {
        $bulan=konversi_bulan($data[bulan]);
	       echo "<tr bgcolor='#D5F35B'>
              <td>$bulan</td>
              <td align='center'>$data[total]</td>
              </tr>";
      }
?>

	</table>
	
	<script type="text/javascript">
	$('#myHTMLTable').convertToFusionCharts({
		swfPath: "../Charts/",
		type: "MSColumn3D",
		data: "#myHTMLTable",
		dataFormat: "HTMLTable"
	});
	</script>

	</center>
</body>
</html>

