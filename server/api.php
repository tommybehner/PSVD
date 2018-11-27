<?php

// API for acessing data via JSON.


require_once ('init.php');

// Set the content as json
header('Content-type: application/json; charset=UTF-8');

$stmt = DB::get()->prepare('SELECT *, (SELECT count(*) FROM spaces WHERE space_lot_id = lot_id) AS ps, 
			('.get_num_space_query("lot_id").') AS spaces FROM lots');
$stmt->execute();
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo '{
    "parkinglots":[';

foreach ($res as $row){
	echo '{
        "id": '.intval($row['lot_id']).',
        "name": "'.($row['lot_name']).'",
        "desc": "'.($row['lot_desc']).'",
        "totalspaces": '.intval($row['ps']).',
        "usedspaces": '.intval($row['spaces']).',
        "location": "'.($row['lot_location']).'"
    }';
	if ($row != end($res)) echo ',';
}
echo ']
}';

?>