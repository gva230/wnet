<?php
class DB
{
	/**
	* @static
	* @var object The instance of this class.
	*/
	private static $_instance;

	/**
	* @static
	* @var integer The number of affected rows in a previous MySQL operation.
	*/
	public static $rows;

	private function __construct()
	{
		$this->connect = new mysqli("mysql_server", "mysql_user", "mysql_password", "mysql_db");

		if ($this->connect->connect_errno) die("[".$this->connect->connect_errno."]: ".$this->connect->connect_error);

		$this->connect->set_charset("utf8");
	}

	private function __clone() {}
	private function __wakeup() {}

	/**
	* Return the instance of this class
	*
	* @static
	* @return object The instance of this class.
	*/
	public static function getInstance()
	{
		if (self::$_instance === null) self::$_instance = new self();
		return self::$_instance;
	}

	/**
	* Returns the result of SQL-query
	*
	* @static
	* @param string $sql String of SQl-query.
	* @return object|boolean Returns a mysqli_result object.
	*/
	public static function query($sql)
	{
		$obj = self::$_instance;
		$res = $obj->connect->query($sql) or trigger_error("MySQL error in query: \"".$sql."\"", E_USER_ERROR);
		self::$rows = $obj->connect->affected_rows;
		return $res;
	}

	/**
	* Escapes special characters in a string for use in an SQL statement
	*
	* @static
	* @param string $string Unescaped string.
	* @return string Returns escaped string.
	*/
	public static function sqlEsc($string)
	{
		return "'".self::$_instance->connect->real_escape_string($string)."'";
	}
}
?>