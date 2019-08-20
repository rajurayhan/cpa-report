<?php
require_once 'includes/database.class.php';

$data = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$fromDate  = $_POST["fromDate"];
	$toDate    = $_POST["toDate"];
	$operator  = $_POST["operator"];
	$service   = $_POST["service"];
	$adv 	   = null; 
	$db 	= new database($operator, $service, $fromDate, $toDate, $adv, 'baseDuration');
	$db->connect();
	$data = $db->getDurationData();
	//var_dump($data);
	$db->close();
	foreach ($data as $d) {		
		$msisdn 	= $d->msisdn;              
        $subDate 	= date_create($d->subs_date);
		$unsubDate 	= date_create($d->unsubs_date);
		$diff 		= date_diff($subDate,$unsubDate);

		$duration 	= $diff->format("%a");
		$processedData[] = array(
				'msisdn' 		=> $msisdn,
				'days'   		=> $duration, 
				'subs_date'		=> $d->subs_date, 
				'unsubs_date'	=> $d->unsubs_date
		);
	}
	//var_dump($processedData);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Base Duration Report</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">

	<link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" />

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" />

	<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>	

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>	
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script> 
	
	
	<style type="text/css">
    	.output{
    		margin-top: 30px;
    	}
    	.table{
    		margin-top: 25px;
    	}

    	@media (min-width: 576px){
    		.form-inline .form-control {
			    margin-left: 4px !important;
			    width: 24% !important;
			}
    	}
		
    </style>
</head>
<body>

	<div class="container">
		
		<div class="row">
			<div class="col-lg-12">
				<h1>Subscribers Duration</h1>
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
					<button type="submit" name="submit" class="btn btn-primary mb-2">Submit</button>
				</form>
			</div>
		</div>
		<div class="row output">
			<div class="col-lg-12">
				<?php if(isset($processedData) && sizeof($processedData)>0){ 
					echo '<h4>'.$fileName.'</h4>'; ?>
				<table class="table table-hover">
					<thead>
						<tr>
							<th>MSISDN</th>
							<th>Duration (Day)</th>
							<th>Subscribed at</th>
							<th>Unsubscribed at</th>
						</tr>
					</thead>
					<tbody>
				<?php }?>
						<?php if(isset($processedData) && sizeof($processedData)>0){ 
							foreach ($processedData as $data) { ?>
								<tr>
									<td><?php echo $data['msisdn']; ?></td>
									<td><?php echo $data['days']; ?></td>
									<td><?php echo $data['subs_date']; ?></td>
									<td><?php echo $data['unsubs_date']; ?></td>
								</tr>
							<?php 	
								}
							?>
							
						<?php } ?>

					</tbody>
				</table>
			</div>
		</div>
	</div>

</body>
	<script type="text/javascript">
		$(document).ready(function() {
			var fileName = '<?php echo $fileName?>';
		    $('.table').DataTable( {
		    	"iDisplayLength": 25,
		        dom: 'Bfrtip',
		        buttons: [
		            {
		                extend: 'excelHtml5',
		                title: fileName
		            },
		            {
		                extend: 'csvHtml5',
		                title: fileName
		            }
		        ]
		    } );
		} );
	</script>
</html>