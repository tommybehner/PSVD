<?php

// Page for showing parking spaces


require_once ('init.php');

// Get the ID of the parking lot through the GET request.
$id = intval($_GET['id']);

// Check the ID exists and get lot information.
$pstmt = DB::get()->prepare("SELECT *, (SELECT count(*) FROM spaces WHERE space_lot_id = lot_id) AS ps, 
	(".get_num_space_query("lot_id").") AS spaces FROM lots WHERE lot_id = ?");
$pstmt->bindValue(1, $id, PDO::PARAM_INT);
$pstmt->execute();

// Go back to homepage if parking lot does not exist on database.
if ($pstmt->rowCount() != 1){
	header ('Location: '.Conf::URL_BASE);
	exit();
}

// Get the parking lot data
$lot = $pstmt->fetch(PDO::FETCH_ASSOC);

$nav_selected = 2;
$breadcrumb = '<li class="active">'.$lot['lot_name'].'</li>';
require_once ('includes/header.php');

?>
<div class="row block block-spaces-header">
	<div class="col-md-<?php echo (isset($lot['lot_location']) && $lot['lot_location'] != null) ? '6' : '12'; ?> col-xs-12 left">
        <h1><?php print $lot['lot_name']; ?></h1>
			
        <p><?php print $lot['lot_desc']; ?></p>
			
        <div class="stats">
            <span class="alert alert-small alert-warning total"><?php print $lot['ps']; ?> Total Spaces</span>
            <span class="alert alert-small alert-info available"><?php print $lot['ps'] - $lot['spaces']; ?></span>
            Available Spaces 
        </div>
	</div>
	
	<?php if(isset($lot['lot_location']) && $lot['lot_location'] != null){ ?>
		<a target="_blank" href="https://www.google.com/maps/search/<?php echo $lot['lot_location']; ?>" 
            class="col-md-6 col-xs-12 image-float" style="
			background:url('http://maps.googleapis.com/maps/api/staticmap?&center=<?php echo $lot['lot_location']; ?>&maptype=roadmap&markers=color:blue%7Clabel:P%7C<?php echo $lot['lot_location']; ?>&zoom=15&size=1200x600&key=<?php echo Conf::MAPS_API_KEY; ?>');
			background-size:cover;background-position:center;border:1px solid rgb(230,230,230);">
		</a>
	<?php } ?>
	
</div>
<div class="progress progress-big block">
	<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" 
			style="width: <?php echo ($lot['spaces']/$lot['ps'])*100;?>%;"><?php echo round($lot['spaces'] * 100 / $lot['ps']); ?>% full</div>
</div>

<div class="tbrow block">
	<div class="row row-header">
		<div class="col-xs-4">Space</div>
		<div class="col-xs-4">Status</div>
		<div class="col-xs-4">Last Updated</div>
	</div>

	<?php

	// Get the status of the parking spaces
	$query = "SELECT * 
			FROM spaces a
			LEFT JOIN (
				SELECT *
				FROM updates b
				WHERE update_time = (
					SELECT max( update_time )
					FROM updates um
					WHERE um.update_space_id = b.update_space_id
				)
				GROUP BY b.update_space_id
			) b ON a.space_id = b.update_space_id
			WHERE space_lot_id = ".$id;

	$stmt = DB::get()->query($query);
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

	// Print the information about the spaces
	$i = 0;
	foreach ($rows as $row){
		$i++;
		?>
		<div class="row">
			<div class="col-xs-4"><?php echo $i; ?><span style="float:right;" class="piinfo badge"><?php echo 'pi'.$row['space_pi_id'].'-'.$row['space_area_code']; ?></span></div>
			<div class="col-xs-4"><?php echo $row['update_status'] == 0 ? '<span class="alert alert-small alert-success">Empty</span>' : '<span class="alert alert-small alert-danger">Filled</span>'; ?></div>
			<div class="col-xs-4"><?php echo isset($row['update_time']) ? ago($row['update_time']) : 'Never'; ?></div>
		</div>
		<?php
	} ?>
</div>
    <?php if(isset($_GET['refresh'])){ ?>
        <a class="btn btn-danger" href="?id=<?php echo $lot['lot_id']; ?>">
            <span class="glyphicon glyphicon-refresh" style="margin-right:5px;"></span>
            Turn off Auto Refresh</a>
        <script>
         function refresh() {
             window.location.reload(true);
         }
     
         setTimeout(refresh, 5000);
         </script>
    <?php } else { ?>
        <a class="btn btn-success" href="?id=<?php echo $lot['lot_id']; ?>&refresh">
            <span class="glyphicon glyphicon-refresh" style="margin-right:5px;"></span>
            Turn on Auto Refresh</a>
    <?php } ?>
<?php
require_once ('includes/footer.php');
?>