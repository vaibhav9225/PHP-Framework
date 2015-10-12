<?php

class Initialiser{
	private $type;
	public function __construct(){
		$this->setFiles();
	}
	public function setFiles(){
		global $page, $database, $session, $cookie, $mail, $form, $options;
		require_once('includes/config.php');
		require_once('includes/page.php');
		require_once('includes/database.php');
		require_once('includes/session.php');
		require_once('includes/cookie.php');
		require_once('includes/mail.php');
		require_once('includes/form.php');
		require_once('includes/options.php');
	}
	public function setHeader(){
		global $page, $database, $session, $cookie, $mail, $form, $options;
		$string = $this->type;
		if($string == 'admin')
			require_once('admin_header.php');
		else if($string == 'dealer')
			require_once('dealer_header.php');
		else if($string == 'sales')
			require_once('sales_header.php');
	}
	public function setLogin($string){
		global $page, $database, $session, $cookie, $mail, $form, $options;
		$this->type = $string;
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