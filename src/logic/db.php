<?php
class database {
	private static $servername = "localhost";
	private static $username = "root";
	private static $password = "";
	private static $dbname = "medhub";
	
	private static $instance;
	
	private $conn;

	private function __construct() {}

	public static function get_instance() : database {
        if (!isset(self::$instance)) {
            self::$instance = new database();
        }

        return self::$instance;
	}

	public static function get_connection() {
		$db = self::get_instance();
		$db->conn = new mysqli(database::$servername, database::$username, database::$password, database::$dbname);

		if ($db->conn->connect_error) {
			die("Connection failed: " . $db->conn->connect_error);
		}

		return $db->conn;
	}
}
?>