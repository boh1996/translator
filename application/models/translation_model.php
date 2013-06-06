<?php
class Translation_Model extends CI_Model {

	/**
	 * This function updates the approval status for a translation
	 * @since 1.0
	 * @access public
	 * @param  integer $language_key_id The language key to update
	 * @param  integer $language_id     The language the translation is in
	 * @param  integer $translation_id  The translation to approve/decline
	 * @param  boolean $status          true means approve, false means decline
	 * @return boolean
	 */
	public function change_approval ( $language_key_id, $language_id, $translation_id, $status ) {
		$this->db->where(array(
			"language_id" 		=> $language_id,
			"language_key_id" 	=> $language_key_id,
			"translation_id" 	=> $translation_id
		));
		$this->db->update("language_key_translations",array(
			"approved" => $status
		));

		return $this->db->affected_rows() > 0;
	}

	/**
	 * Checks if the user has access to edit the project, a translation is associated with
	 * 
	 * @param  integer $user_id         The user that requests access
	 * @param  integer $language_key_id The language key, the translation is for
	 * @param  array $modes           The required modes
	 * @return boolean
	 */
	public function user_has_access_to ( $user_id, $language_key_id, $modes ) {
		$this->load->model("user_roles_model");
		$this->load->model("key_model");

		$project_id = $this->key_model->get_project_id($language_key_id);

		if ( $project_id === false ) return false;

		$user_modes = $this->user_roles_model->get_user_project_modes( $user_id, $project_id);

		if ( count(array_intersect($user_modes,$modes)) == 0 ) {
    		return false;
   		}

   		return true;
	}

	/**
	 * Fetches the id of the language key, a translation is associated for
	 * 
	 * @param  integer $translation_id The translation to check for
	 * @return integer
	 */
	public function get_translation_language_key ( $translation_id ) {
		$this->db->from("language_key_translations");
		$this->db->select("language_key_id");
		$this->db->where(array(
			"translation_id" => $translation_id,
		));

		$query = $this->db->get();

		if ( ! $query->num_rows() ) return false;

		$row = $query->row();

		return $row->language_key_id;
	}

	/**
	 * This function adds a link between the language_key, translation for the specific language
	 * @since 1.0
	 * @access public
	 * @param  integer $language_key_id
	 * @param  integer $translation_id
	 * @param  integer $language_id
	 * @param  boolean $approved
	 */
	public function link_translation ( $language_key_id, $translation_id, $language_id, $approved ) {
		if ( ! self::link_exists($language_key_id, $translation_id, $language_id) ) {
			if ( $approved == true ) {
				self::unapprove_others($language_key_id, $language_id);
			}

			if ( $this->config->item("multiple_translations_per_key") == false ) {
				self::remove_other_translations($language_key_id, $language_id);
			}

			$this->db->insert("language_key_translations", array(
				"language_key_id" => $language_key_id,
				"translation_id" => $translation_id,
				"language_id" => $language_id,
				"approved" => (int)$approved
			));
		} else {
			$this->db->where(array(
				"language_key_id" => $language_key_id,
				"translation_id" => $translation_id,
				"language_id" => $language_id,
			));
			$this->db->update("language_key_translations", array(
				"approved" => (int)$approved
			));
		}
	}

	/**
	 * Finds all translations associated with a language_key and language pair
	 * 
	 * @param  integer $language_key_id The language key to finds pairs for
	 * @param  integer $language_id     The language to pair with
	 * @return array<integer>
	 */
	public function find_translations ( $language_key_id, $language_id ) {
		$this->db->from("language_key_translations");
		$this->db->where(array(
			"language_key_id"	=> $language_key_id,
			"language_id"		=> $language_id
		));
		$this->db->select("translation_id");
		$query = $this->db->get();

		if ( ! $query->num_rows() ) return array();

		$translations = array();

		foreach ( $query->result() as $row ) {
			$translations[] = $row->translation_id;
		}

		return $translations;
	}

	/**
	 * This function removes all older translations, when a new is created
	 * 
	 * @param  integer $language_key_id The language key where a new translation has been added
	 * @param  integer $language_id     The language the new translation is in
	 */
	public function remove_other_translations ( $language_key_id, $language_id ) {
		$this->db->from("language_key_translations");
		$this->db->where(array(
			"language_key_id"	=> $language_key_id,
			"language_id"		=> $language_id
		));
		$this->db->delete();

		$translations = self::find_translations($language_key_id, $language_id);

		if ( count($translations) > 0 ) {
			$this->db->from("translation_tokens");
			$this->db->where_in("translation_id", $translations);
			$this->db->delete();
		}
	}

	/**
	 * Removes all links to a translation
	 * 
	 * @param  integer $translation_id The translation which is being deleted
	 */
	public function remove_translation ( $translation_id ) {
		$this->db->from("language_key_translations");
		$this->db->where(array(
			"translation_id"	=> $translation_id
		));
		$this->db->delete();

		$this->db->from("translation_tokens");
		$this->db->where(array(
			"translation_id"	=> $translation_id
		));
		$this->db->delete();
	}

	/**
	 * Removes approval status from older translations
	 * 
	 * @param  integer $language_key_id The language key which is being translated
	 * @param  integer $language_id     The language key the translation is for
	 */
	public function unapprove_others ( $language_key_id, $language_id ) {
		$this->db->where(array(
			"language_key_id"	=> $language_key_id,
			"language_id"		=> $language_id
		));
		$this->db->update("language_key_translations",array(
			"approved" => 0
		));
	}

	/**
	 * This function checks if a link between the language_key, translation and language_id already exists
	 * @param  integer $language_key_id
	 * @param  integer $translation_id
	 * @param  integer $language_id
	 * @since 1.0
	 * @access public
	 * @return boolean
	 */
	public function link_exists ( $language_key_id, $translation_id, $language_id ) {
		$this->db->from("language_key_translations");
		$this->db->where(array(
			"language_key_id" => $language_key_id,
			"language_id" => $language_id,
			"translation_id" => $translation_id
		));
		$query = $this->db->get();
		return ( $query->num_rows() > 0);
	}
}
?>