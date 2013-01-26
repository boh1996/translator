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
}
?>