<?php
require_once 'includes/database.class.php';

$data = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$fromDate  = $_POST["fromDate"];
	$toDate    = $_POST["toDate"];
	$operator  = $_POST["operator"];
	$service   = $_POST["service"];
	$adv   	   = $_POST["adv"];

	$db 	= new database($operator, $service, $fromDate, $toDate, $adv);
	$db->connect();
	$data = $db->getCPAData();
	// var_dump($data);
	$db->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>CPA Report</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</head>
<body>

	<div class="container">
		<h2>CPA Report</h2>
		<div class="row">
			<div class="col-md-12">
				<form class="form-inline" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<label class="sr-only" for="inlineFormInputName2">From Date</label>
					<input type="date" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" name="fromDate" value="<?php if(isset($fromDate)) echo $fromDate ?>">

					<label class="sr-only" for="inlineFormInputGroupUsername2">To Date</label>
					<div class="input-group mb-2 mr-sm-2">

						<input type="date" class="form-control" id="inlineFormInputGroupUsername2"  name="toDate" value="<?php if(isset($toDate)) echo $toDate ?>">
					</div>

					<div class="input-group mb-2 mr-sm-2">
						<select class="form-control" name="operator" required>
							<option value="" selected="">Operator</option>
							<option value="blink">Blink</option>
							<option value="airtel">Airtel</option>
							<option value="robi">Robi</option>
						</select>
					</div>

					<div class="input-group mb-2 mr-sm-2">                
						<select class="form-control" name="service" required>
							<option value="" selected="">Service</option>
							<option value="fb">FunBox</option>
							<option value="cycas">CYCAS</option>
						</select>
					</div>

					<div class="input-group mb-2 mr-sm-2">                
						<select required="" name="adv" class="form-control" id="advertiser" data-toggle="tooltip" data-placement="auto" title="" data-original-title="Advertiser">
							<option value="" selected="">Advertiser</option>
							<option value="bitterstrawberry">BitterStrawberry</option>
							<option value="tiger">Tiger Ads</option>
							<option value="kimia">Kimia Solvers</option>
							<option value="level23">Level23</option>
							<option value="google">Google Ads</option>
						</select>
					</div>



					<button type="submit" name="submit" class="btn btn-primary mb-2">Submit</button>
				</form>
			</div>
			<div class="col-md-12">
				<?php
					if($data){

						$total = 0;
						// foreach ($data as $d) {
						// 	echo $d->date.'--'.$d->activation;
						// 	echo '<br>';
						// }
						echo "<h2>Activation Report: ". mb_strtoupper($operator) ." - ". $adv ." - " . $service . "</h2>";
						echo "<table class='table table-hover'>
			            <tr>
			            <th>Date</th>
			            <th>Activation</th>
			            </tr>";
			            foreach ($data as $d) {
			            	echo "<tr>";
					        echo "<td>" . $d->date . "</td>";
					        echo "<td>" . $d->activation . "</td>";
					        echo "</tr>";

					        $total += $d->activation;
						}
						echo "<tr>";
						echo "<th>Total</th>";
						echo "<th>". $total ."</th>";
						echo "</tr>";			            
			            echo "</table>";
					}
				?>
			</div>
		</div>
	</div>

</body>
</html>