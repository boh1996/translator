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
			$this->db->insert("language_key_translations", array(
				"language_key_id" => $language_key_id,
				"translation_id" => $translation_id,
				"language_id" => $language_id,
				"approved" => (int)$approved
			));
		}
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