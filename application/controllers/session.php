<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Session extends CI_Controller{

	public function index () {
		$this->load->library("db_session");
	}
}