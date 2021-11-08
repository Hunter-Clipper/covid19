<?php
main();
function main () {
    $country = htmlspecialchars($_GET['country']);

	if (strcmp($country, 'USA') == 0){
		$country2 = 'United%20States';
	} 
	elseif (strcmp($country, 'UK') == 0){
		$country2 = 'United%20Kingdom';
	} 
	else{
		$country2 = $country;
	}


	$apiCall = 'https://disease.sh/v3/covid-19/countries/' . $country;
	$json_string = curl_get_contents($apiCall);
	$obj = json_decode($json_string);
	$totalInfections = $obj->cases;
	$totalDeaths = $obj->deaths;
    $flag = $obj->countryInfo->flag;

	$file='https://raw.githubusercontent.com/owid/covid-19-data/master/public/data/vaccinations/country_data/'. $country2 . '.csv';
	$json = csvToJson($file);
	$objVax = json_decode($json);
	$fullVax = (end($objVax)->people_fully_vaccinated);

	echo '<html>
			<head>
				<title>COVID-19 '. $country .'</title>
				<link rel="icon" href="https://images.squarespace-cdn.com/content/v1/5c4085e585ede1f50f94a4b9/1581018457505-JM3FO6WMFN9BGP3IOE8D/2019-nCoV-CDC-23312_without_background.png" type="image/png">
				<link href="css/bootstrap.min.css" rel="stylesheet">
				<link rel="stylesheet" href="css/myStyle.css">
				<script src="js/bootstrap.min.js"></script>
			</head>
			<div class = "container">
			<div class = "col">
			  <div class="jumbotron">';
			    echo " <a href='index.php' type='button' class='btn btn-secondary'>GLOBAL CASES</a> ";
				echo " <img src='". $flag ."' style='float: right;' alt='flag' width=250>";
				echo ' <h1><b>Covid-19 Cases '. $country .'</b></h1> ';
				echo '<h3>'. $country .' Confirmed Cases: ' . number_format($totalInfections) . '</h3>
				<h3>'. $country .' Confirmed Tests Ran: ' . number_format($obj->tests) . '</h3>
				<h3>'. $country .' Confirmed Deaths: ' . number_format($totalDeaths) . '</h3>
				<h3>'. $country .' Death Rate: ' . number_format($totalDeaths / $totalInfections, 4) * 100 . '%</h3>
				<h3>'. $country .' Full Vaxinations: ' . number_format($fullVax) . '</h3>
				<p>Numbers are updated as of: ' . date("m-d-Y");
			  echo '</div>';

			  echo '<p><b>Disclaimer 1: </b> These number are as accurate as they can be from various APIs reporting statistic on the COVID-19 pandemic. Some reports may vary.</p>
			  <p><b>Disclaimer 2: </b> For reports that are reporting 0 are "null" in the APIs there for the number has not been reported from the CDC or WHO.</p>
			  <p><b>Disclaimer 3: </b> These number are as accurate as they can be from various APIs reporting statistic on the COVID-19 pandemic. Some reports may vary.</p>
			  <table class="table table-dark">
			<thead>
			  <tr>
				<th scope="col">#</th>
				<th scope="col">State</th>
				<th scope="col">Infections</th>
				<th scope="col">Recovered</th>
				<th scope="col">Deaths</th>
				<th scope="col">D:I Ratio</th>
			  </tr>
			</thead>
			<tbody>';
			if ($country == "USA"){
				$apiCall = 'https://disease.sh/v3/covid-19/states?sort=cases';
				$json_string = curl_get_contents($apiCall);
				$array = json_decode($json_string, TRUE);
				usort($array, function($a, $b) {
					if($a['cases']==$b['cases']) return 0;
					return $a['cases'] < $b['cases']?1:-1;
				});
				$data = json_encode($array, JSON_PRETTY_PRINT);
				$covid = json_decode($data);
				for ($x = 0; $x <= 62; $x++) {
					echo '<tr>
							  <th scope="row">' . ($x + 1) . '</th>
							  <td id="state">'. $covid[$x]->state . '</td>
							  <td id="infections">' . number_format($covid[$x]->cases) . '</td>
							  <td id="recovered">' . number_format($covid[$x]->recovered) . '</td>
							  <td id="deaths">' . number_format($covid[$x]->deaths) . '</td>
							  <td id="ratio">' . round($covid[$x]->deaths / $covid[$x]->cases, 2) . '%' . '</td>
						  </tr>';
				  }
			}	else{
				echo '<tr>
							  <th scope="row">1</th>
							  <td id="state">No</td>
							  <td id="infections">Current</td>
							  <td id="recovered">Data</td>
							  <td id="deaths">Found</td>
							  <td id="ratio">for '. $country .'</td>
							  <td id="vaxxed"></td>
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
