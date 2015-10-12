<?php

class Initialiser{
	public function __construct(){
		$this->setFiles();
	}
	public function setFiles(){
		global $page, $database, $session, $cookie, $mail, $form, $options;
		require_once('../includes/config.php');
		require_once('../includes/page.php');
		require_once('../includes/database.php');
		require_once('../includes/session.php');
		require_once('../includes/cookie.php');
		require_once('../includes/mail.php');
		require_once('../includes/form.php');
	}
	public function setLogin($string){
		global $page, $database, $session, $cookie, $mail, $form, $options;
		if($string == 'admin')
			require_once('admin_authenticate.php');
		else if($string == 'dealer')
			require_once('dealer_authenticate.php');
		else if($string == 'sales')
			require_once('sales_authenticate.php');
	}
}

$init = new Initialiser();

?>