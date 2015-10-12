<?php

class OptionCreator{
	public function getCarriers($limit = ''){
		global $database, $page;
		$result = "";
		if($limit == '')
			$query = "SELECT `id`,`name` FROM `carriers` WHERE 1 ORDER BY `name`";
		else{
			$query = "SELECT `id`,`name` FROM `carriers` WHERE `id`!='$limit' ORDER BY `name`";
			$result .= "<option value='$limit' selected>".$page->capitalize($page->lower($database->otherValue('carriers', 'id', $limit, 'name')))."</option>";
		}
		$sql = $database->query($query,false);
		while($data = $database->fetchAssoc($sql)){
			$result .= "<option value=".$data['id'].">".$page->capitalize($page->lower($data['name']))."</option>";
		}
		return $result;
	}
	public function getDealers($limit = '', $filter = null){
		global $database, $page;
		$result = "";
		if($filter == null or $filter == ''){
			if($limit == '')
				$query = "SELECT `id`,`name` FROM `dealers` WHERE id!='-1' ORDER BY `name`";
			else{
				$query = "SELECT `id`,`name` FROM `dealers` WHERE `id`!='$limit' AND id!='-1' ORDER BY `name`";
				$result .= "<option value='$limit' selected>".$page->strip($database->otherValue('dealers', 'id', $limit, 'name'))."</option>";
			}
		}
		else{
			if($limit == '')
				$query = "
				SELECT `del`.`id`,`del`.`name`  
				FROM `dealers` AS `del` 
				JOIN `dealer_sim_details` AS  `dd`
				ON `dd`.`dealerId`=`del`.`id`
				WHERE `del`.`id`!='-1' AND `dd`.`salesId`='$filter'
				GROUP BY `del`.`id`
				ORDER BY `del`.`name`
				";
			else{
				$query = "
				SELECT `del`.`id`,`del`.`name` 
				FROM `dealers` AS `del` 
				JOIN `dealer_sim_details` AS  `dd`
				ON `dd`.`dealerId`=`del`.`id`
				WHERE `del`.`id`!='$limit' AND `dd`.`salesId`='$filter'
				GROUP BY `del`.`id`
				ORDER BY `del`.`name`
				";
				$result .= "<option value='$limit' selected>".$page->strip($database->otherValue('dealers', 'id', $limit, 'name'))."</option>";
			}
		}
		$sql = $database->query($query,false);
		while($data = $database->fetchAssoc($sql)){
			$result .= "<option value=".$data['id'].">".$page->strip($data['name'])."</option>";
		}
		return $result;
	}
	public function getSales($limit = ''){
		global $database;
		$result = "";
		if($limit == '')
			$query = "SELECT `id`,`name` FROM `sales` WHERE id!='-1' ORDER BY `name`";
		else{
			$query = "SELECT `id`,`name` FROM `sales` WHERE `id`!='$limit' AND id!='-1' ORDER BY `name`";
			$result .= "<option value='$limit' selected>".$database->otherValue('sales', 'id', $limit, 'name')."</option>";
		}
		$sql = $database->query($query,false);
		while($data = $database->fetchAssoc($sql)){
			$result .= "<option value=".$data['id'].">".$data['name']."</option>";
		}
		return $result;
	}
	public function getAdmins($limit = ''){
		global $database;
		$result = "";
		if($limit == '')
			$query = "SELECT `id`,`name` FROM `admins` WHERE 1 ORDER BY `name`";
		else{
			$query = "SELECT `id`,`name` FROM `admins` WHERE `id`!='$limit' ORDER BY `name`";
			$result .= "<option value='$limit' selected>".$database->otherValue('admins', 'id', $limit, 'name')."</option>";
		}
		$sql = $database->query($query,false);
		while($data = $database->fetchAssoc($sql)){
			$result .= "<option value=".$data['id'].">".$data['name']."</option>";
		}
		return $result;
	}
	public function getDistributers($limit = ''){
		global $database, $page, $session;
		$result = "";
		$dealerId = $session->getValue('dealerId');
		if($limit == '')
			$query = "SELECT `id`,`name` FROM `distributers` WHERE `dealerId`='$dealerId' ORDER BY `name`";
		else{
			$query = "SELECT `id`,`name` FROM `distributers` WHERE `id`!='$limit' AND `dealerId`='$dealerId' ORDER BY `name`";
			$result .= "<option value='$limit' selected>".$page->capitalize($page->lower($database->otherValue('distributers', 'id', $limit, 'name')))."</option>";
		}
		$sql = $database->query($query,false);
		while($data = $database->fetchAssoc($sql)){
			$result .= "<option value=".$data['id'].">".$page->capitalize($page->lower($data['name']))."</option>";
		}
		return $result;
	}
	public function getStates($limit = ''){
		global $database;
		$result = "";
		if($limit == '')
			$query = "SELECT `id`,`name` FROM `states` WHERE 1 ORDER BY `name`";
		else{
			$query = "SELECT `id`,`name` FROM `states` WHERE `id`!='$limit' ORDER BY `name`";
			$result .= "<option value='$limit' selected>".$database->otherValue('states', 'id', $limit, 'name')."</option>";
		}
		$sql = $database->query($query,false);
		while($data = $database->fetchAssoc($sql)){
			$result .= "<option value=".$data['id'].">".$data['name']."</option>";
		}
		return $result;
	}
	public function getModels($limit = ''){
		global $database;
		$result = "";
		if($limit == '')
			$query = "SELECT `id`,`model`,`company` FROM `phones` WHERE 1 ORDER BY `company`,`model`";
		else{
			$query = "SELECT `id`,`model`,`company` FROM `phones` WHERE `id`!='$limit' ORDER BY `company`,`model`";
			$result .= "<option value='$limit' selected>".$database->otherValue('phones', 'id', $limit, 'company')." -- ".$database->otherValue('phones', 'id', $limit, 'model')."</option>";
		}
		$sql = $database->query($query,false);
		while($data = $database->fetchAssoc($sql)){
			$result .= "<option value=".$data['id'].">".$data['company']." -- ".$data['model']."</option>";
		}
		return $result;
	}
	public function getMonths($limit = '', $bool=true){
		global $database, $page;
		$result = "";
		$array = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
		if($limit == ''){
			if($bool == true){
				$month = $page->timeline('F');
				$result .= "<option value='$month' selected>$month</option>";
				if (($key = array_search($month, $array)) !== false) {
					unset($array[$key]);
				}
			}
		}
		else{
			$result .= "<option value='$limit' selected>$limit</option>";
			if (($key = array_search($limit, $array)) !== false){
				unset($array[$key]);
			}
		}
		foreach($array as $month){
			$result .= "<option value='$month'>$month</option>";
		}
		return $result;
	}
	public function getYears($limit = '', $bool=true){
		global $database, $page;
		$result = "";
		$array = array("2012", "2013", "2014", "2015", "2016");
		if($limit == ''){
			if($bool == true){
				$year = $page->timeline('Y');
				$result .= "<option value='$year' selected>$year</option>";
				if (($key = array_search($year, $array)) !== false) {
					unset($array[$key]);
				}
			}
		}
		else{
			$result .= "<option value='$limit' selected>$limit</option>";
			if (($key = array_search($limit, $array)) !== false){
				unset($array[$key]);
			}
		}
		foreach($array as $year){
			$result .= "<option value='$year'>$year</option>";
		}
		return $result;
	}
}

$options = new OptionCreator();

?>