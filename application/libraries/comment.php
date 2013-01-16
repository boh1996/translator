<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

/**
 * This object holds a comment
 * 
 * @author Bo Thomsen <bo@illution.dk>
 * @version 1.0
 * @license http://opensource.org/licenses/MIT MIT License
 * @uses Std_Library This object is dependent of the Illution ORM
 */
class Comment extends Std_Library {

	/**
	 * The datebase id of the object
	 * @since 1.0
	 * @access public
	 * @var integer
	 */
	public $id = null;

	/**
	 * A users comment
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public $comment = null;

	/**
	 * The user who created the comment
	 * @since 1.0
	 * @access public
	 * @var object
	 */
	public $user = null;

	/**
	 * The UNIX timestamp of the time when the comment was created
	 * @since 1.0
	 * @access public
	 * @var integer
	 */
	public $time_created = null;

	### Class Settings ###

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "comments";

	/**
	 * This is the constructor, it configurates the std library
	 * @since 1.0
	 * @access private
	 */
	public function __construct () {
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS_ABORT_ON_NULL = true;
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS = 	array(
			"comment",
			"user_id"
		);
		$this->_INTERNAL_ROW_NAME_CONVERT = array(
			"user_id" 	=> "user",
		);
		$this->_INTERNAL_LOAD_FROM_CLASS = array(
			"user"	=> "User"
		);
		$this->_INTERNAL_CREATED_TIME_PROPERTY 		= "time_created";
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array(
			"id"
		);
		$this->_INTERNAL_EXPORT_FORMATING = array(
			"time_created" => array("date","d/m-Y - H:i:s"),
		);
		parent::__construct();
	}
}