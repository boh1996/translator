<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'libraries/API_Controller.php');  

/**
 * Translation Object Controller
 *
 * The Translation Controller/Endpoint
 *
 * @uses 			API Controller
 * @package        	CodeIgniter
 * @subpackage    	Libraries
 * @category    	Libraries
 * @author        	Bo Thomsen
 * @version 		1.0
 */
class API_Translation extends API_Controller {

	/**
	 * This function is called on any request send to this endpoint,
	 * it loads up all the needed files
	 * @since 1.0
	 * @access public
	 */
	public function __construct () {
		parent::__construct();
		$this->load->library("translation");
	}

	/**
	 * This endpoint outputs a translation,
	 * found by it's :id
	 * @param integer :id The database id of the translation to retrieve
	 * @since 1.0
	 * @access public
	 */
	public function index_get () {
		if ( ! $this->get('id') ) {  
           	self::error($this->config->item("api_bad_request_code"));
            return; 
        }

        $Translation = new Translation();

        if ( ! $Translation->Load( $this->get("id") ) ) {
        	self::error($this->config->item("api_not_found_code"));
        	return;
        }

        $this->response( $Translation->Export() );
	}

	/**
	 * This function batch saves translations
	 * @since 1.0
	 * @access public
	 */
	public function translations_post () {
		if ( ! $this->post("translations") || (is_array($this->post("translations")) && count($this->post("translations")) > 0) || ! $this->post("project_id") || ! $this->post("language_id") ) {
			$this->load->library("project");
			$this->load->library("language");
			$this->load->library("translation");
			$this->load->library("key");
			$this->load->library("mustache");
			$Project = new Project();
			$Language = new Language();
			$errors = array();
			$errorTranslations = array();
			if ( ! $Project->Load($this->post("project_id")) ) {
				self::error($this->config->item("api_bad_request_code"));
				return;
			}

			if ( ! $Language->Load($this->post("language_id")) ) {
				self::error($this->config->item("api_bad_request_code"));
				return;
			}

			foreach ( $this->post("translations") as $translation ) {
				$error = false;
				$Key = new Key();
				$TranslationObject = new Translation();
				$objects = array();

				if ( ! isset($translation["language_key_id"]) || ( isset($translation["language_key_id"]) && ! $Key->Load($translation["language_key_id"]) ) ) {
					$error = true;
					$errors[] = array("message" => $this->lang->line("errors_key_not_found"),"code" => 404);
				}

				if ( ! isset($translation["translation"]) ) {
					$error = true;
					if ( $Key->is_loaded() ) {
						$errors[] = array("message" => $this->mustache->render($this->lang->line("errors_missing_translation_for_key"), array(
							"key" => $Key->key
						)), "code" => 400);
					} else {
						$errors[] = array("message" => $this->lang->line("errors_missing_translation"),"code" => 400);
					}
				}

				if ( isset($Key->tokens) && is_array($Key->tokens) ) {
					if ( isset($translation["translation"]) ) {
						$missing = false;
						foreach ( $Key->tokens as $token ) {
							if ( strrpos($translation["translation"], $token->token) === false ) {
								$missing = true;
								if ( $Key->is_loaded() ) {
									$errors[] = array("message" => $this->mustache->render($this->lang->line("errors_error_missing_token_key"), array(
										"token" => $token->token,
										"key" => $Key->key
									)),"code" => 400);
								} else {
									$errors[] = array("message" => $this->mustache->render($this->lang->line("errors_error_missing_token"), array(
										"token" => $token->token,
									)),"code" => 400);
								}
							}
						}

						if ( $missing === true ) {
							$error = true;
						}
					}
				}

				if ( $error === false && $Key->is_loaded() ){
					$data = array(
						"language" => $Language->id,
						"translation" => self::_string_security($translation["translation"]),
						"user" => $this->user->id
					);
					if ( isset($Key->tokens) ) {
						$data["tokens"] = $Key->tokens;
					}
					$TranslationObject->Import($data);
				}

				if ( $error === false ) {
					if ( ! $TranslationObject->Save() ) {
						$error = true;
						if ( $Key->is_loaded() ) {
							$errors[] = array("message" => $this->mustache->render($this->lang->line("errors_error_saving_translation_for_key"), array(
								"key" => $Key->key
							)),"code" => 500);
						} else {
							$errors[] = array("message" => $this->lang->line("errors_error_saving_translation"),"code" => 500);
						}
					} else {
						$this->load->model("translation_model");
						$approval = ( isset($translation["approval"]) && ($translation["approval"] == "true" || $translation["approval"] === true) ) ? (bool)$translation["approval"] : false;
						$this->translation_model->link_translation($Key->id, $TranslationObject->id, $Language->id, $approval);
						$objects[] = $TranslationObject->Export(array(
							"language",
							"translation",
							"tokens"
						));
					}
				}

				if ( $error !== false && $Key->is_loaded() ) {
					$errorTranslations[] = $Key->id;
				} else {
					$errorTranslations[] = 0;
				}
			}

			if ( count( $errors ) !== 0 ) {
				if ( is_array($this->post("translations")) && count($errorTranslations) != count($this->post("translations")) ) {
					$this->response(array(
						"errors" => $errors,
						"error_keys" => $errorTranslations,
						"translations" => $objects
					),$this->config->item("api_created_code"));
				} else {
					$this->response(array(
						"errors" => $errors,
						"error_keys" => $errorTranslations
					),$this->config->item("api_bad_request_code"));
				}
			} else {
				$this->response(array("translations" => $objects),$this->config->item("api_created_code"));
			}

		} else {
			self::error($this->config->item("api_bad_request_code"));
			return;
		}
	}

	/**
	 * This function secures the string for insertion
	 * @since 1.0
	 * @access private
	 * @param  string $string The string to secure
	 * @return string
	 */
	private function _string_security ( $string ) {
		$string = str_replace("&nbsp;", " ", $string);
		$string = htmlentities($string);
		$string = htmlspecialchars($string);
		return $string;
	}

	/**
	 * This endpoint saves a new translation
	 * @since 1.0
	 * @access public
	 */
	public function index_post () {
		$Translation = new Translation();

		if ( ! $Translation->Import($this->post()) ) {
			self::error($this->config->item("api_bad_request_code"));
			return;
		}

		if ( ! $Translation->Save() ) {
			self::error($this->config->item("api_error_while_saving_code"));
			return;
		}

		$this->response($Translation->Export(),$this->config->item("api_created_code"));

	}

	/**
	 * This function updates a translation with the new data
	 * @since 1.0
	 * @access public
	 */
	public function index_put () {
		$Translation = new Translation();

		if ( ! $this->get('id') ) {  
           	self::error($this->config->item("api_bad_request_code"));
            return; 
        }

        if ( ! $Translation->Load( $this->get("id") ) ) {
        	self::error($this->config->item("api_not_found_code"));
        	return;
        }

		if ( ! $Translation->Import($this->put(), true) ) {
			self::error($this->config->item("api_bad_request_code"));
			return;
		}

		if ( ! $Translation->Save() ) {
			self::error($this->config->item("api_error_while_saving_code"));
			return;
		}

		$this->response($Translation->Export(), $this->config->item("api_accepted_code"));
	}

	/**
	 * This endpoint changes the approval status for a translation
	 * @since 1.0
	 * @access public
	 */
	public function approval_post () {
		if ( ! $this->get('language_key_id') || ! $this->get("translation_id") || ! $this->post("status") || ! $this->get("language_id") ) {  
            self::error($this->config->item("api_bad_request_code"));
            return; 
        }

        $this->load->model("translation_model");

        $this->translation_model->change_approval($this->get("language_key_id"),$this->get("language_id"),$this->get("translation_id"),$this->post("status"));
	}

	/**
	 * This endpoints deletes a Translation, found by it's database :id
	 * @param integer :id The database id of the translation
	 * @since 1.0
	 * @access public
	 */
	public function index_delete () {
		if ( ! $this->get('id') ) {  
            self::error($this->config->item("api_bad_request_code"));
            return; 
        }

        $Translation = new Translation();

        if ( ! $Translation->Load( $this->get("id") ) ) {
        	self::error($this->config->item("api_not_found_code"));
        	return;
        }

        $Translation->Delete();
	}
}