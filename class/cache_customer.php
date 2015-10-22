<?php
class Cache_Customer
{
	/**
	* @static
	* @var object The instance of Memcache class.
	*/
	private static $_memcache;

	/**
	* Create object of Customer class and add this in memcache.
	*
	* @static
	* @return object New instance of Customer class.
	*/
	private static function createObject()
	{
		$obj = new Customer;
		self::$_memcache->set("customer_object", $obj, MEMCACHE_COMPRESSED, 60*60);
		return $obj;
	}

	/**
	* Get object of Customer class from memcache if this exists.
	*
	* @static
	* @return object Instance of Customer class from memcache if this exists.
	*/
	public static function getObject()
	{
		if (class_exists("Memcache"))
		{
			self::$_memcache = new Memcache;
			if (!self::$_memcache->connect("localhost", 11211)) return self::createObject();

			if (self::$_memcache->get("customer_object")) return self::$_memcache->get("customer_object");
			else return self::createObject();
		}
		else return self::createObject();
	}
}
?>