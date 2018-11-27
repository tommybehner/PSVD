<?php

// Admin page for managing the parking lots.


require_once ('../init.php');

$nav_selected = 1;
$breadcrumb = '<li class="active">Parking Lot Management</li>';
require_once ('../includes/header.php');
?>

<h1><span class="glyphicon glyphicon-wrench"></span> Parking Lot Management</h1>

<div class="tbrow block">
	<div class="row row-header">
		<div class="col-xs-1">
			ID
		</div>
		<div class="col-xs-2">
			Name
		</div>
		<div class="col-xs-3">
			Description
		</div>
		<div class="col-xs-2">
			Location
		</div>
		<div class="col-xs-2">
			Spaces
		</div>
		<div class="col-xs-2">
			Action
		</div>
	</div>
	<?php

	$stmt = DB::get()->prepare('SELECT *,
		(SELECT count(*) FROM spaces WHERE space_lot_id = lot_id) AS lot_spaces FROM lots');
	$stmt->execute();
	$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

	foreach ($res as $row){
	?>
	<div class="row">
		<div class="col-xs-1">
			<?php echo $row['lot_id']; ?>
		</div>
		<div class="col-xs-2">
			<a href="<?php echo Conf::URL_BASE; ?>spaces.php?id=<?php echo $row['lot_id']; ?>">
                <?php echo $row['lot_name']; ?>
            </a>
		</div>
		<div class="col-xs-3">
			<?php echo $row['lot_desc']; ?>
		</div>
		<div class="col-xs-2">
			<?php echo $row['lot_location'] == null ? '<span class="badge">not set</span>' : $row['lot_location']; ?>
		</div>
		<div class="col-xs-2">
			<?php echo $row['lot_spaces']; ?>
		</div>
		<div class="col-xs-2">
			<button type="button" onclick="updateLotField(<?php echo $row['lot_id']; ?>, '<?php echo $row['lot_name']; ?>', '<?php echo $row['lot_desc']; ?>', 
			<?php echo $row['lot_spaces']; ?>, '<?php echo $row['lot_location']; ?>')" class="btn btn-xs btn-default">
				Edit
			</button>
			<button type="button" onclick="$('#del-confirm-<?php echo $row['lot_id']; ?>').show();" class="btn btn-xs btn-danger">
				Delete
			</button>
			<form id="del-confirm-<?php echo $row['lot_id']; ?>"  style="display:none;" action="lotaction.php" method="POST">
				<input type="text" value="<?php echo $row['lot_id']; ?>" name="lot_id" class="form-hidden" style="display:none;">
				<input type="text"  name="lot_action" value="delete" style="display:none;">
				<button type="button" onclick="submit();" class="btn btn-xs btn-danger" >
					Confirm Delete
				</button>
			</form>
		</div>
	</div>
	<?php
	}
	?>
</div>

<script type="text/javascript">
	function updateLotField(id, name, desc, spaces, location) {
		$('#lot_id').val(id);
		$('#lot_name').val(name);
		$('#lot_desc').val(desc);
		$('#lot_spaces').val(spaces);
		$('#lot_location').val(location);
		$('#lot_action').val('update');
		$('#lot_form_title').html('Edit Lot #' + id);
	}
</script>

<form class="form-signin" role="form" action="lotaction.php" method="POST">
	<h2 id="lot_form_title" class="form-signin-heading">Add Lot</h2>

	<input type="text" id="lot_action" name="lot_action" value="add" class="form-hidden">
	<input type="text" id="lot_id" name="lot_id" class="form-hidden" >

	<input type="text" id="lot_name" name="lot_name" class="form-control form-control-first" placeholder="Name" required="" autofocus="">
	<textarea id="lot_desc" name="lot_description" class="form-control form-control-last" placeholder="Lot Description"></textarea>
	
	<input type="text" id="lot_location" name="lot_location" class="form-control form-control-first form-control-last" placeholder="Location">
	
	<button class="btn btn-lg btn-primary btn-block" type="submit">
		Confirm
	</button>
</form>

<?php
require_once ('../includes/footer.php');
?>