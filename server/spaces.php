<?php

/**
 * Page for showing car parking spaces
 *
 * @author	Humphrey Shotton
 * @version	1.1 (2014-03-21)
 */

require_once ('init.php');

// Get the ID of the carpark through the GET request.
$id = intval($_GET['id']);

// Check the ID exists and get park information.
$pstmt = DB::get()->prepare("SELECT *, (SELECT count(*) FROM spaces WHERE space_park_id = park_id) AS ps, 
	(".get_num_space_query("park_id").") AS spaces FROM parks WHERE park_id = ?");
$pstmt->bindValue(1, $id, PDO::PARAM_INT);
$pstmt->execute();

// Go back to homepage if carpark does not exist on database.
if ($pstmt->rowCount() != 1){
	header ('Location: '.Conf::URL_BASE);
	exit();
}

// Get the carpark data
$park = $pstmt->fetch(PDO::FETCH_ASSOC);

$nav_selected = 2;
$breadcrumb = '<li class="active">'.$park['park_name'].'</li>';
require_once ('includes/header.php');

?>
<div class="row block block-spaces-header">
	<div class="col-md-<?php echo (isset($park['park_location']) && $park['park_location'] != null) ? '6' : '12'; ?> col-xs-12 left">
        <h1><?php print $park['park_name']; ?></h1>
			
        <p><?php print $park['park_desc']; ?></p>
			
        <div class="stats">
            <span class="alert alert-small alert-warning total"><?php print $park['ps']; ?> Total Spaces</span>
            <span class="alert alert-small alert-info available"><?php print $park['ps'] - $park['spaces']; ?></span>
            Available Spaces 
        </div>
	</div>
	
	<?php if(isset($park['park_location']) && $park['park_location'] != null){ ?>
		<a target="_blank" href="https://www.google.com/maps/search/<?php echo $park['park_location']; ?>" 
            class="col-md-6 col-xs-12 image-float" style="
			background:url('http://maps.googleapis.com/maps/api/staticmap?&center=<?php echo $park['park_location']; ?>&maptype=roadmap&markers=color:blue%7Clabel:P%7C<?php echo $park['park_location']; ?>&zoom=15&size=1200x600&key=<?php echo Conf::MAPS_API_KEY; ?>');
			background-size:cover;background-position:center;border:1px solid rgb(230,230,230);">
		</a>
	<?php } ?>
	
</div>
<div class="progress progress-big block">
	<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" 
			style="width: <?php echo ($park['spaces']/$park['ps'])*100;?>%;"><?php echo round($park['spaces'] * 100 / $park['ps']); ?>% full</div>
</div>

<div class="tbrow block">
	<div class="row row-header">
		<div class="col-xs-4">Space</div>
		<div class="col-xs-4">Status</div>
		<div class="col-xs-4">Last Updated</div>
	</div>
	<?php
	/*
	Alternative Query
	$query = "SELECT  a.*, c.*
			FROM updates a
			INNER JOIN
			(
				SELECT update_space_id, MAX(update_time) max_date
				FROM    updates
				GROUP BY update_space_id
			) b ON a.update_space_id = b.update_space_id AND
					a.update_time = b.max_date
			LEFT JOIN spaces c ON space_id = a.update_space_id
			WHERE space_park_id = ".$id;*/

	// Get the status of the car parking spaces
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
			WHERE space_park_id = ".$id;

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
        <a class="btn btn-danger" href="?id=<?php echo $park['park_id']; ?>">
            <span class="glyphicon glyphicon-refresh" style="margin-right:5px;"></span>
            Turn off Auto Refresh</a>
        <script>
         function refresh() {
             window.location.reload(true);
         }
     
         setTimeout(refresh, 5000);
         </script>
    <?php } else { ?>
        <a class="btn btn-success" href="?id=<?php echo $park['park_id']; ?>&refresh">
            <span class="glyphicon glyphicon-refresh" style="margin-right:5px;"></span>
            Turn on Auto Refresh</a>
    <?php } ?>
<?php
require_once ('includes/footer.php');
?>