<?php
class Key_Model extends CI_Model {

	/**
	 * Retrieves the project a language_key is associated with
	 * 
	 * @param  integer $language_key_id The language key database row id, of the key to retrieve the project id for
	 * @return integer
	 */
	public function get_project_id ( $language_key_id ) {
		$this->db->from("language_keys");
		$this->db->select("language_file_id");
		$this->db->where(array(
			"id" => $language_key_id
		));

		$languageKeyQuery = $this->db->get();

		if ( ! $languageKeyQuery->num_rows() ) return false;

		$row = $languageKeyQuery->row();

		$this->db->from("project_language_files");
		$this->db->select("project_id");
		$this->db->where(array(
			"language_file_id" => $row->language_file_id
		));

		$fileQuery = $this->db->get();

		if ( ! $fileQuery->num_rows() ) return false;

		$row = $fileQuery->row();

		return $row->project_id;
	}
}
?>