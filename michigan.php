<?php
main();
function main () {
	$apiCall = 'https://covidtracking.com/api/states';
	$json_string = curl_get_contents($apiCall);
	$obj = json_decode($json_string);
	$totalInfections = $obj[22]->positive;
	$totalDeaths = $obj[22]->death;
	$TotalRecovered = $obj[22]->recovered;

	echo '<html>
			<head>
				<title>COVID-19 MICHIGAN</title>
				<link rel="icon" href="css/covid.png" type="image/png">
				<link href="css/bootstrap.min.css" rel="stylesheet">
				<link rel="stylesheet" href="css/myStyle.css">
				<script src="js/bootstrap.min.js"></script>
			</head>
			<div class = "container">
			<div class = "col">
			  <div class="jumbotron">';
			    echo " <a href='index.php' type='button' class='btn btn-secondary' style='float: right;'>GLOBAL CASES</a>";
				echo ' <h1><b>Covid-19 Cases Michigan</b></h1> ';
				echo " <a href='state.php' type='button' class='btn btn-secondary' style='float: right;'>USA STATE CASES</a>";
				echo '<h3>Michigan Confirmed Cases: ' . number_format($totalInfections) . '</h3>
				<h3>Michigan Confirmed Tests Ran: ' . number_format($obj[22]->totalTestResults) . '</h3>
				<h3>Michigan Confirmed Deaths: ' . number_format($totalDeaths) . '</h3>
				<h3>Michigan Confirmed Recoveries: ' . number_format($TotalRecovered) . '</h3>
				<h3>Michigan Recovery Rate: ' . round($TotalRecovered / $totalInfections, 2) . '%</h3>
				<h3>Michigan Death Rate: ' . round($totalDeaths / $totalInfections, 2) . '%</h3>
				
				<p>Numbers are updated as of: ' . date("m-d-Y") . ' ' . date("h:i a").'
			  </div>';
			echo '<p><b>Disclaimer 1: </b> For reports that are reporting 0 are "null" in the APIs there for the number has not been reported from the CDC or WHO.</p>
			<p><b>Disclaimer 2: </b> these number are as accurate as they can be from various APIs reporting statistic on the COVID-19 pandemic. Some reports may vary.</p>
			</div>
			</div>
			<footer class="page-footer font-small pt-4">
			  <p style="text-align:center;">© 2020 Copyright | Hunter Clipper</p>
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
