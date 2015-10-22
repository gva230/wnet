<?php
spl_autoload_register(function ($class) {require_once "class/".mb_strtolower($class, "UTF-8").".php";});

if ($_SERVER["REQUEST_METHOD"] == "GET") include_once "html/index.htm";
else if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	DB::getInstance();

	$id_contract = 0;
	$status = [];

	if ((isset($_POST["work"])) and ($_POST["work"] != "")) $status[] = DB::sqlEsc("work");
	if ((isset($_POST["connecting"])) and ($_POST["connecting"] != "")) $status[] = DB::sqlEsc("connecting");
	if ((isset($_POST["disconnected"])) and ($_POST["disconnected"] != "")) $status[] = DB::sqlEsc("disconnected");
	if ((isset($_POST["search"])) and ($_POST["search"] != "")) $search = trim($_POST["search"]);
	if ((isset($_POST["id_contract"])) and ($_POST["id_contract"] != "")) $id_contract = (int) $_POST["id_contract"];

	if ($id_contract > 0)
	{
		$customer = Cache_Customer::getObject();
		$customer->id_contract = $id_contract;
		$out = $customer->getContract();
	}
	else if (!isset($search))
	{
		$error = [];
		$error["error"] = "Укажите id либо имя клиента.";
		$out = json_encode($error);
	}
	else if (empty($status))
	{
		$error = [];
		$error["error"] = "Укажите хотя бы один статус сервиса.";
		$out = json_encode($error);
	}
	else
	{
		$customer = Cache_Customer::getObject();
		$customer->id_name_customer = $search;
		$customer->service_status = $status;
		$out = $customer->getContractId();
	}

	print($out);
}
?>
