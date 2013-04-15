<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Front extends CI_Controller{

	/**
	 * This function recives all the calls when a page is requested
	 * @since 1.0
	 * @access public
	 */
	public function _remap ( $method = NULL, $params = array() ) {
		$this->load->model("projects_model");

		$this->user_control->batch_load_lang_files(array(
			"front"
		));

		$languages = $this->config->item("languages");
		$language = $this->user_control->language;
		$this->load->view("header_view",$this->user_control->ControllerInfo(array(
			"style_includes" => array(
				"css/style.css",
				"css/front.css",
				"css/webkit-form.css"
			)
		)));
		$data = array(
			"method" => $method,
			"params" => json_encode($params),
			"projects" => $this->projects_model->projects_array()
		);
		$this->load->view("front_view",$this->user_control->ControllerInfo($data));
		$this->load->view("footer_view",$this->user_control->ControllerInfo(array(
			"script_includes" => array(
				"functions.js",
				"urlhandling.js",
				"translate.js",
				"routes.js",
				"application.js"
			)
		)));
	}
}