<?php
class Customer
{
	/**
	* @var integer The ID of contract.
	*/
	public $id_contract;

	/**
	* @var mixed The ID or name of customer.
	*/
	public $id_name_customer;

	/**
	* @var array The status of services.
	*/
	public $service_status;

	/**
	* Get from DB and return in JSON the ids and the numbers of the customers contracts
	*
	* @return json Array of contract`s ids and numbers.
	*/
	public function getContractId()
	{
		$contracts = [];
		$where = " se.`status` IN(".implode(",",$this->service_status).") ";
		$where .= " AND (cu.`id_customer` = ".DB::sqlEsc($this->id_name_customer)." OR cu.`name_customer` = ".DB::sqlEsc($this->id_name_customer).") ";

		$res = DB::query("SELECT co.`id_contract`,co.`number`
				FROM `obj_customers` cu
					LEFT JOIN `obj_contracts` co ON co.`id_customer` = cu.`id_customer`
					LEFT JOIN `obj_services` se ON se.`id_contract` = co.`id_contract`
				WHERE ".$where."
				GROUP BY co.`id_contract`");

		if (DB::$rows <= 0) $contracts["error"] = "По вашему запросу \"".$this->id_name_customer."\" ничего не найдено.";
		else while ($row = $res->fetch_assoc()) $contracts[$row["id_contract"]] = $row["number"];

		return json_encode($contracts);
	}

	/**
	* Get from DB and return in JSON the information of the contract
	*
	* @return json Array of contract`s ids and numbers.
	*/
	public function getContract()
	{
		$services = [];

		$res = DB::query("SELECT co.`number`,co.`date_sign`
					,cu.`name_customer`,cu.`company`
					,se.`title_service`
				FROM `obj_customers` cu
					LEFT JOIN `obj_contracts` co ON co.`id_customer` = cu.`id_customer`
					LEFT JOIN `obj_services` se ON se.`id_contract` = co.`id_contract`
				WHERE co.`id_contract` = ".DB::sqlEsc($this->id_contract));

		if (DB::$rows <= 0) $services["error"] = "Странно, ничего не найдено...";
		else while ($row = $res->fetch_assoc())
		{
			$services["number"] = $row["number"];
			$services["date_sign"] = $row["date_sign"];
			$services["name_customer"] = $row["name_customer"];
			$services["company"] = $row["company"];
			$services["title_service"][] = $row["title_service"];
		}

		return json_encode($services);
	}
}
?>