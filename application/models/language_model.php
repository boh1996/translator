<?php
class Language_Model extends CI_Model {

	/**
	 * This function checks if a language exists
	 * @since 1.0
	 * @access public
	 * @param  integer $language_id The language to check for
	 * @return boolean
	 */
	public function language_exists ( $language_id ) {
		$this->db->from("languages")->where(array("id" => $language_id));
		$query = $this->db->get();

		return ($query->num_rows() > 0);
	}
}
?>