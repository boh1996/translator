<?php
class Project_Model extends CI_Model {

	/**
	 * This function checks if a project exists
	 * @since 1.0
	 * @access public
	 * @param  integer $project_id The project to check for
	 * @return boolean
	 */
	public function project_exists ( $project_id ) {
		$this->db->from("projects")->where(array("id" => $project_id));
		$query = $this->db->get();

		return ($query->num_rows() > 0);
	}

	/**
	 * This function checks if a project has a language assigned
	 * @since 1.
	 * @access public
	 * @param  integer $project_id  The projects database id
	 * @param  integer $language_id The language database id
	 * @return boolean
	 */
	public function project_language_exists ( $project_id, $language_id ) {
		$this->db->from("project_languages")->where(array(
			"project_id" 	=> $project_id,
			"language_id"	=> $language_id
		));
		$query = $this->db->get();

		return ($query->num_rows() > 0);
	}
}
?>