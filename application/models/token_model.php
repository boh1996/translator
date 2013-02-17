<?php
class Token_Model extends CI_Model {

	/**
	 * This function returns all the available tokens for a project
	 * @since 1.0
	 * @access public
	 * @param  ingeter $project_id The project to retrieve tokens for
	 * @return array
	 */
	public function get_project_tokens ( $project_id ) {
		$this->db->from("tokens");
		$this->db->where(array(
			"project_id" => $project_id
		));
		$query = $this->db->get();

		if ( $query->num_rows() > 0 ) {
			$result = array();

			foreach ($query->result() as $row) {
				$result[] = array(
					"token" => $row->token,
					"description" => $row->description,
					"id" => $row->id
				);
			}

			return $result;
		} else {
			return false;
		}
	}
}
?>