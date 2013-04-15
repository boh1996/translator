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

	/**
	 * Returns an array of the projects that the user has access too
	 *
	 * @since 1.0
	 * @param  integer $user_id The databse id of the user to retrieve projects for
	 * @return array<Project>
	 */
	public function get_user_projects ( $user_id ) {
		$this->db->from("user_project_roles");
		$this->db->select("project_id");
		$this->db->where(array(
			"user_id"	=> $user_id
		));

		$query = $this->db->get();

		if ( ! $query->num_rows() ) return array();

		$projects = array();

		foreach ( $query->result() as $row ) {
			$projects[] = $row->project_id;
		}

		array_unique($projects);

		foreach ( $projects as $key => $id ) {
			$this->load->library("project");
			$Project = new Project();
			
			if ( $Project->Load($id) ) {
				$projects[$key] = $Project->Export();
			} else {
				unset($projects[$key]);
			}
		}

		return $projects;
	}
}
?>