<?php
main();
function main () {
	$apiCall = 'https://disease.sh/v3/covid-19/all';
	$json_string = curl_get_contents($apiCall);
	$obj = json_decode($json_string);
	$totalInfections = $obj->cases;
	$totalDeaths = $obj->deaths;
	$TotalRecovered = $obj->recovered;

	$apiCall = 'https://disease.sh/v3/covid-19/countries';
	$json_string = curl_get_contents($apiCall);
	
	$array = json_decode($json_string, TRUE);
	usort($array, function($a, $b) {
		return $a['cases'] < $b['cases'];
	});
	$data = json_encode($array, JSON_PRETTY_PRINT);
	$covid = json_decode($data);
	echo '<html>
			<head>
				<title>COVID-19 Global</title>
				<link rel="icon" href="css/covid.png" type="image/png">
				<link href="css/bootstrap.min.css" rel="stylesheet">
				<link rel="stylesheet" href="css/myStyle.css">
				<script src="js/bootstrap.min.js"></script>
			</head>
			<div class = "container">
			<div class = "col">
			  <div class="jumbotron">';
			    echo " <a href='state.php' type='button' class='btn btn-secondary' style='float: right;'>USA STATE CASES</a>";
				echo '<h1><b>Covid-19 Global Cases</b></h1> 
				<h3>Global Confirmed Cases: ' . number_format($totalInfections) . '</h3>
				<h3>Global Confirmed Deaths: ' . number_format($totalDeaths) . '</h3>
				<h3>Global Confirmed Recoveries: ' . number_format($TotalRecovered) . '</h3>
				<h3>Global Recovery Rate: ' . round($TotalRecovered / $totalInfections, 2) . '%</h3>
				<h3>Global Death Rate: ' . round($obj->deaths / $obj->cases, 2) . '%</h3>
				<p>Numbers are updated as of: ' . date("m-d-Y") .'
			  </div>';
			echo '<p><b>Disclaimer: </b> these number are as accurate as they can be from various APIs reporting statistic on the COVID-19 pandemic. Some reports may vary.</p>
			<table class="table table-dark">
			  <thead>
				<tr>
				  <th scope="col">#</th>
				  <th scope="col">Country</th>
				  <th scope="col">Infections</th>
				  <th scope="col">Recovered</th>
				  <th scope="col">Deaths</th>
				  <th scope="col">D:I Ratio</th>
				</tr>
			  </thead>
			  <tbody>';
				for ($x = 0; $x <= 210; $x++) {
				  echo '<tr>
							<th scope="row">' . ($x + 1) . '</th>
							<td id="country"><a href="#">' . $covid[$x]->country . '</a></td>
							<td id="infections">' . number_format($covid[$x]->cases) . '</td>
							<td id="recovered">' . number_format($covid[$x]->recovered) . '</td>
							<td id="deaths">' . number_format($covid[$x]->deaths) . '</td>
							<td id="ratio">' . round($covid[$x]->deaths / $covid[$x]->cases, 2) . '%' . '</td>
						</tr>';
				}
				  
			echo '</tbody>
			</table>
			</div>
			</div>
			<footer class="page-footer font-small pt-4">
			  <p style="text-align:center;">2021 Copyright | Hunter Clipper</p>
			</footer>
			</html>';
	
}
function curl_get_contents($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
?>
