<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

/**
 * This object holds a language key
 * 
 * @author Bo Thomsen <bo@illution.dk>
 * @version 1.0
 * @license http://opensource.org/licenses/MIT MIT License
 * @uses Std_Library This object is dependent of the Illution ORM
 */
class Key extends Std_Library {

	/**
	 * The datebase id of the object
	 * @since 1.0
	 * @access public
	 * @var integer
	 */
	public $id = null;

	/**
	 * The language key
	 * @since 1.0
	 * @access public
	 * @access public
	 * @var string
	 */
	public $key = null;

	/**
	 * A description of the language key
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public $description = null;

	/**
	 * An array containig the available tokens for this language key
	 * @since 1.0
	 * @access public
	 * @var array
	 */
	public $tokens = null;

	/**
	 * The "file" where the language key is saved too
	 * @since 1.0
	 * @access public
	 * @var object
	 */
	public $file = null;

	/**
	 * An array containing the available translations for this key
	 * @since 1.0
	 * @access public
	 * @var array
	 */
	public $translations = null;

	/**
	 * If a translation should be approved first, even if it's the only translation
	 * or the best voted
	 * @var boolean
	 * @since 1.0
	 * @access public
	 */
	public $approve_first = true;

	### Class Settings ###

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "language_keys";

	/**
	 * This is the constructor, it configurates the std library
	 * @since 1.0
	 * @access private
	 */
	public function __construct () {
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS_ABORT_ON_NULL = true;
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS = 	array(
			"key",
			"file"
		);
		$this->_INTERNAL_LOAD_FROM_CLASS = array(
			"file" 				=> "File",
			"tokens"			=> "Token",
			"translations"		=> "Translation"
		);
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array(
			"id",
			"tokens",
			"translations"
		);
		$this->_INTERNAL_SIMPLE_LOAD = 		array(
			"file" => true
		);
		$this->_INTERNAL_ROW_NAME_CONVERT = array(
			"language_file_id" 				=> "file",
		);
		$this->_INTERNAL_LINK_PROPERTIES = array(
			"tokens"			=> array("language_key_tokens",	array("language_key_id" => "id"),array("token_id"), array("token_id","language_key_id")),
			"translations"		=> array("language_key_translations",	array("language_key_id" => "id"),array("translation_id"), array("translation_id","language_key_id")),
 		);
		parent::__construct();
	}
}