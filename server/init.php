<?php

/** 
 * Initialization file for Raspberry Pi car parking sensor.
 * 
 * @author	Humphrey Shotton
 * @version	1.0 (2014-01-16)
 */

// The directory of the init file.
$base = dirname(__FILE__);
 
require_once($base . '/config.php');
require_once($base . '/includes/functions.php');

date_default_timezone_set('Europe/London');

/**
 * Class for organising database connection 
 */
class DB {
	private static $db_con;
	
	public static function connect(){
		try{
			// Attempts to connect to the database using 
			self::$db_con = new PDO( 'mysql:host='.Conf::DB_HOST.';dbname='.Conf::DB_DATABASE.';charset=utf8;', 
					Conf::DB_USER, Conf::DB_PASS );			
		} catch (PDOException $err) {
			// Catches an error in the login details for the database, and exits.
			die('Error in connecting to MySQL database.');
		} catch (Exception $err) {
			die('Error in initialization');
		}
	}
	
	public static function get(){
		return self::$db_con;
	}
}

// Connect to the database
DB::connect();

?>