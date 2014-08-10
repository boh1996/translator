<?php
class DB_Session {

	/**
	 * An instancer of the CodeIgniter db library
	 *
	 * @var object
	 */
	public $db = null;

	/**
	 * An instance of CodeIgniter
	 *
	 * @var object
	 */
	private $_ci = null;

	/**
	 * The DB session data
	 *
	 * @var object<stdClass>
	 */
	public $session = null;

	/**
	 * The session token retrieved from the users cookie
	 *
	 * @var string
	 */
	public $session_token = null;

	/**
	 * If session data has been loaded
	 *
	 * @var boolean
	 */
	public $loaded = false;

	/**
	 * If the first session attempt was valid
	 *
	 * @var boolean
	 */
	public $valid = false;

	/**
	 * Class constructor loads up the needed ressources and starts the validation process
	 *
	 * @param object $db An optional custom db ressource
	 */
	public function __construct ( $db = null ) {
		$this->_ci =& get_instance();
		$this->_ci->config->load("session");

		if ( ! is_null($db) ) {
			$this->db = $db;
		} else {
			$this->db =& $this->_ci->db;
		}

		$this->_ci->load->helper("request");
		$this->_ci->load->helper("rand");

		$this->_check_session();

		print_r($this->session);
	}

	/**
	 * Loads up all the data for the session token in $this->session_token
	 *
	 * @return boolean If the process was a success
	 */
	protected function _load () {
		$this->db->from($this->_ci->config->item("sessions_table"));
		$this->db->where(array(
			$this->_ci->config->item("session_token_row") => $this->session_token
		));
		$token_query = $this->db->get();

		if ( ! $token_query->num_rows() ) {
			$this->loaded = false;
			return false;
		}

		$token = $token_query->row();

		$this->db->from($this->_ci->config->item("session_data_table"));
		$this->db->where(array(
			$this->_ci->config->item("session_data_link_row") => $token->{$this->_ci->config->item("session_data_unique_row")}
		));
		$data_query = $this->db->get();

		$this->session = $token;

		if ( $data_query->num_rows() > 0 ) {
			foreach ( $data_query->result() as $row ) {
				$this->session->data[$row->key] = $row->value;
			}
		}

		$this->loaded =  true;
		return true;
	}

	/**
	 * Checks if user session data exists, and is valid
	 *
	 * @return boolean
	 */
	protected function _check_session () {
		if ( request_cookie($this->_ci->config->item("session_cookie_name")) ) {
			$this->session_token = request_cookie($this->_ci->config->item("session_cookie_name"));
			if ( $this->_load() ) {
				if ( ! $this->_validate() ) {
					return $this->_create_session();
				}

				$this->valid = true;
				return true;
			} else {
				return $this->_create_session();
			}
		} else {
			return $this->_create_session();
		}
	}

	/**
	 * Generates a random session token
	 *
	 * @return string
	 */
	protected function _rand () {
		return Rand_Str(32);
	}

	/**
	 * Checks if a session exists in the database
	 *
	 * @return boolean
	 */
	protected function _exists () {
		$this->db->from($this->_ci->config->item("sessions_table"));
		$this->db->where(array(
			$this->_ci->config->item("session_token_row") => $this->session_token
		));
		$query = $this->db->get();

		return $query->num_rows() > 0;
	}

	/**
	 * Initializes a new session and inserts the appropiate data
	 *
	 * @return boolean
	 */
	protected function _create_session () {
		$this->session_token = $this->_rand();

		if ( $this->_exists() ) {
			return $this->_create_session();
		}

		$this->db->insert($this->_ci->config->item("sessions_table"), $this->_create_token_data());

		if (! $this->_load() ) {
			return $this->_create_session();
		}

		request_cookie($this->_ci->config->item("session_cookie_name"), $this->session_token, time() + $this->_ci->config->item("session_life_time"));

		return $this->loaded;
	}

	/**
	 * Creates the data to insert, when a new session is created
	 *
	 * @return array
	 */
	protected function _create_token_data () {
		$device_profile = $this->_create_device_profile();

		return array(
			$this->_ci->config->item("session_token_row") => $this->session_token,
			"time_created" => time(),
			"device_profile" => json_encode($device_profile),
			"device_profile_hash" => md5(json_encode($device_profile))
		);
	}

	protected function _create_device_profile () {

	}

	public function get ( $key ) {

	}

	public function set ( $key, $value ) {

	}

	protected function _token_valid () {
		$max_time = $this->session->time_created + $this->_ci->config->item("session_life_time");
		return ( time() <= $max_time ) ? true : false;
	}

	protected function _valid_device_profile () {
		$device_profile = $this->_create_device_profile();
		return md5(json_encode($device_profile)) == $this->session->device_profile_hash;
	}

	protected function _validate () {
		if ( ! $this->_token_valid() ) {
			return false;
		}

		if ( ! $this->_valid_device_profile() ) {
			return false;
		}

		return true;
	}
}
?>