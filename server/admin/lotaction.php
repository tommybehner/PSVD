<?php

// File for setting up the parking lots or editing them.


require_once ('../init.php');

if (isset($_POST['lot_action']) && $_POST['lot_action'] == 'add') {
	
	// Checks that all the required keys are present in the post data
	$keys = array("lot_name", "lot_description", "lot_location");
	foreach ($keys as $key)
		if (!array_key_exists($key, $_POST))
			json_error('Incomplete post data.');
	
	// Register the space on the database.
	$query2 = "INSERT INTO lots (lot_name, lot_desc, lot_location) VALUES (?, ?, ?)";
	$stmt2 = DB::get() -> prepare($query2);
	$stmt2 -> bindValue(1, make_safe($_POST["lot_name"]), PDO::PARAM_STR);
	$stmt2 -> bindValue(2, make_safe($_POST["lot_description"]), PDO::PARAM_STR);
	$stmt2 -> bindValue(3, make_safe($_POST["lot_location"]), PDO::PARAM_STR);
	$stmt2 -> execute();
    
} else if (isset($_POST['lot_action']) && $_POST['lot_action'] == 'update') {
	
	// Checks that all the required keys are present in the post data
	$keys = array("lot_id", "lot_name", "lot_description", "lot_location");
	foreach ($keys as $key)
		if (!array_key_exists($key, $_POST))
			json_error('Incomplete post data.');

	if( is_int ( trim($_POST[ 'lot_id' ]) ) )
		json_error('Lot id is not a number. (update)');
    
	// Register the space on the database.
	$query2 = "UPDATE lots SET lot_name = ?, lot_desc = ?, lot_location = ? WHERE lot_id = ?";
	$stmt2 = DB::get() -> prepare($query2);
	$stmt2 -> bindValue(1, make_safe($_POST["lot_name"]), PDO::PARAM_STR);
	$stmt2 -> bindValue(2, make_safe($_POST["lot_description"]), PDO::PARAM_STR);
	$stmt2 -> bindValue(3, make_safe($_POST["lot_location"]), PDO::PARAM_STR);
	$stmt2 -> bindValue(4, intval($_POST["lot_id"]), PDO::PARAM_INT);
	$stmt2 -> execute();
	
} else if (isset($_POST['lot_action']) && $_POST['lot_action'] == 'delete') {
	
	// Checks that all the required keys are present in the post data
	$keys = array("lot_id");
	foreach ($keys as $key)
		if (!array_key_exists($key, $_POST))
			json_error('Incomplete post data.');
		
	// Register the space on the database.
	$query2 = "DELETE FROM lots WHERE lot_id = ?";
	$stmt2 = DB::get() -> prepare($query2);
	$stmt2 -> bindValue(1, intval($_POST["lot_id"]), PDO::PARAM_INT);
	$stmt2 -> execute();
	
}

header("Location: lots.php");
?>
