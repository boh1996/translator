<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

/**
 * This object holds a translation
 * 
 * @author Bo Thomsen <bo@illution.dk>
 * @version 1.0
 * @license http://opensource.org/licenses/MIT MIT License
 * @uses Std_Library This object is dependent of the Illution ORM
 */
class Translation extends Std_Library {

	/**
	 * The datebase id of the object
	 * @since 1.0
	 * @access public
	 * @var integer
	 */
	public $id = null;

	/**
	 * The language of the translation
	 * @since 1.0
	 * @access public
	 * @var object
	 */
	public $language = null;

	/**
	 * The actual translation
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $translation = null;

	/**
	 * The user who created the translation
	 * @since 1.0
	 * @access public
	 * @var object
	 */
	public $user = null;

	/**
	 * The UNIX timestamp when the translation was created
	 * @since 1.0
	 * @access public
	 * @var integer
	 */
	public $time_created = null;

	/**
	 * An array containing the used tokens for this translation
	 * @since 1.0
	 * @access public
	 * @var array
	 */
	public $tokens = null;

	/**
	 * The UNIX timestamp when the translation last was updated
	 * @since 1.0
	 * @access public
	 * @var integer
	 */
	public $last_updated = null;

	### Class Settings ###

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "translations";

	/**
	 * This is the constructor, it configurates the std library
	 * @since 1.0
	 * @access private
	 */
	public function __construct () {
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS_ABORT_ON_NULL = true;
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS = 	array(
			"translation",
			"language",
			"user"
		);
		$this->_INTERNAL_LOAD_FROM_CLASS = array(
			"user" 				=> "User",
			"language"			=> "Language",
			"tokens"			=> "Token"
		);
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array(
			"id",
			"tokens"
		);
		$this->_INTERNAL_FORCE_ARRAY = 	array(
			"tokens",
		);
		$this->_INTERNAL_CREATED_USER_PROPERTY 		= "user";
		$this->_INTERNAL_CREATED_USER_PROPERTY 		= "user";
		$this->_INTERNAL_ROW_NAME_CONVERT = array(
			"user_id" 				=> "user",
			"language_id"			=> "language"
		);
		$this->_INTERNAL_IMPORT_IGNORE = array(
			"user"
		);
		$this->_INTERNAL_LAST_UPDATED_PROPERTY 		= "last_updated";
		$this->_INTERNAL_CREATED_TIME_PROPERTY 		= "time_created";
		$this->_INTERNAL_EXPORT_FORMATING = array(
			"time_created" => array("date","d/m-Y - H:i:s"),
			"last_updated" => array("date","d/m-Y - H:i:s")
		);
		$this->_INTERNAL_LINK_PROPERTIES = array(
			"tokens"			=> array("translation_tokens",	array("translation_id" => "id"),array("token_id"), array("token_id","translation_id"))
 		);
		parent::__construct();
	}
}