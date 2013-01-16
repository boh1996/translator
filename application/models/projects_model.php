<?php
class Projects_Model extends CI_Model {

	/**
	 * This function returns an array containing all the available projects,
	 * as array.
	 * @since 1.0
	 * @access public
	 * @return array
	 */
	public function projects_array () {
		$query = $this->db->from("projects")->get();

		if ( $query->num_rows() ) {
			return $query->result_array();
		} else {
			return array();
		}
	}
}
?>