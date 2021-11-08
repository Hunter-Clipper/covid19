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
				<link rel="icon" href="https://images.squarespace-cdn.com/content/v1/5c4085e585ede1f50f94a4b9/1581018457505-JM3FO6WMFN9BGP3IOE8D/2019-nCoV-CDC-23312_without_background.png" type="image/png">
				<link href="css/bootstrap.min.css" rel="stylesheet">
				<link rel="stylesheet" href="css/myStyle.css">
				<script src="js/bootstrap.min.js"></script>
			</head>
			<div class = "container">
			<div class = "col">
			  <div class="jumbotron">';
			  	echo " <img src='https://images.squarespace-cdn.com/content/v1/5c4085e585ede1f50f94a4b9/1581018457505-JM3FO6WMFN9BGP3IOE8D/2019-nCoV-CDC-23312_without_background.png' style='float:right' alt='flag' width=200>";
				echo '<h1><b>Covid-19 Global Cases</b></h1> 
				<h3>Global Confirmed Cases: ' . number_format($totalInfections) . '</h3>
				<h3>Global Confirmed Deaths: ' . number_format($totalDeaths) . '</h3>
				<h3>Global Confirmed Recoveries: ' . number_format($TotalRecovered) . '</h3>
				<h3>Global Recovery Rate: ' . number_format($TotalRecovered / $totalInfections, 4) * 100 . '%</h3>
				<h3>Global Death Rate: ' . number_format($obj->deaths / $obj->cases, 4) * 100 . '%</h3>
				<p>Numbers are updated as of: ' . date("m-d-Y") .'
			  </div>';
			echo '<p><b>Disclaimer 1: </b> These number are as accurate as they can be from various APIs reporting statistic on the COVID-19 pandemic. Some reports may vary.</p>
			<p><b>Disclaimer 2: </b> For reports that are reporting 0 are "null" in the APIs there for the number has not been reported from the CDC or WHO.</p>
			<p><b>Disclaimer 3: </b> These number are as accurate as they can be from various APIs reporting statistic on the COVID-19 pandemic. Some reports may vary.</p>
			<p><b>Note: </b> For more details for each country click the corresponding links below.</p>
			<table class="table table-dark">
			  <thead>
				<tr>
				  <th scope="col">#</th>
				  <th scope="col">Country</th>
				  <th scope="col">Infections</th>
				  <th scope="col">Recovered</th>
				  <th scope="col">Deaths</th>
				</tr>
			  </thead>
			  <tbody>';
			  foreach ($covid as $row){
				  $x = $x +1;
				echo '<tr>
				<th scope="row">' . ($x) . '</th>
				<td id="country"><a href="country.php?country='. $row->country .'">' . $row->country . '</a></td>
				<td id="infections">' . number_format($row->cases) . '</td>
				<td id="recovered">' . number_format($row->recovered) . '</td>
				<td id="deaths">' . number_format($row->deaths) . '</td>
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

function csvToJson($fname) {
    // open csv file
    if (!($fp = fopen($fname, 'r'))) {
        die("Can't open file...");
    }
    
    //read csv headers
    $key = fgetcsv($fp,"1024",",");
    
    // parse csv rows into array
    $json = array();
        while ($row = fgetcsv($fp,"1024",",")) {
        $json[] = array_combine($key, $row);
    }
    
    // release file handle
    fclose($fp);
    
    // encode array to json
    return json_encode($json);
}
?>
