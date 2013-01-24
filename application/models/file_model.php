<?php
class File_Model extends CI_Model {

	/**
	 * This function calculates the translation progress of a language file
	 * @param  integer $file_id     The file to calculate the progress for
	 * @param  integer $language_id The language to calculate the translation progress for
	 * @return boolean| array(
	 *         "count",
	 *         "missing_approval",
	 *         "done",
	 *         "missing_approval_count",
	 *         "done_count"
	 * )
	 * @since 1.0
	 * @access public
	 */
	public function progress ( $file_id, $language_id ) {
		$query = $this->db->from("language_keys")->where(array(
			"language_file_id"	=> $file_id
		))->select("id,approve_first")->get();

		$key_ids = array();
		$keys = array();

		if ( ! $query->num_rows() ) return false;

		foreach ( $query->result() as $key ) {
			$key_ids[] = $key->id;
			$keys[$key->id] = (bool)$key->approve_first;
		}

		$translationQuery = $this->db->from("language_key_translations")->where_in("language_key_id", $key_ids)->where(array("language_id" => $language_id))->select("approved, language_key_id")->get();

		$done = 0;
		$missing_approval = 0;

		if ( ! $translationQuery->num_rows() ) {
			return array("done" => false,"missing_approval" => false,"missing" => 100,"done_count" => 0, "missing_approval_count" => 0, "count" => count($keys));
		} else {
			foreach ( $translationQuery->result() as $translation) {
				if ( $keys[$translation->language_key_id] === true ) {
					if ( $translation->approved == true ) {
						$done++;
					} else {
						$missing_approval++;
					}
				} else {
					$done++;
				}
			}
		}

		return array(
			"done" => ($done > 0) ? round($done/count($keys)*100) : false, 
			"missing_approval" => ($missing_approval > 0) ? round($missing_approval/count($keys)*100) : false, 
			"count" => count($keys),
			"done_count" => $done,
			"missing_approval_count" => $missing_approval
		);
	}

	/**
	 * This function retrieves all the language keys for a file
	 * @since 1.0
	 * @access public
	 * @param  integer $file_id The language file id
	 * @return array
	 */
	public function language_keys ( $file_id, $select = "key,description, time_created, last_updated" ) {
		$query = $this->db->from("language_keys")->where(array(
			"language_file_id"	=> $file_id
		))->select($select)->get();

		if ( ! $query->num_rows() ) return false;

		return $query->result_array(); 
	}

	/**
	 * This function returns an array containing language file keys,
	 * with a base translation from the selected language
	 * @since 1.0
	 * @access public
	 * @param  integer $file_id     The langauge file id
	 * @param  integer $language_id The base language
	 * @return array
	 */
	public function project_language_keys ( $file_id, $base_language_id, $language_id ) {
		$keys_temp = self::language_keys( $file_id, "id, key,description, time_created, last_updated, approve_first" );

		$keys = array();

		foreach ( $keys_temp as $key) {
			$keys[$key["id"]] = $key;
		}


		$approve_first_keys = array();
		$other_keys = array(); 

		foreach ( $keys as $index => $key ) {
			if ( (bool)$key["approve_first"] == true ) {
				$approve_first_keys[] = $key["id"];
			} else {
				$other_keys[] = $key["id"];				
			}
		}

		if ( $base_language_id != $language_id ) {
			$languages = array($base_language_id, $language_id);
		} else {
			$languages = array($language_id);
		}

		$results = array();

		$query = $this->db->from("language_key_translations")->where_in("language_key_id", $approve_first_keys)->where(array(
			"approved" => true,
		))->where_in("language_key_translations.language_id",$languages)->select("translations.translation, language_key_translations.language_key_id, language_key_translations.language_id ")->join("translations", "translations.id = language_key_translations.translation_id")->get();

		if ( $query->num_rows() > 0 ) {
			$results = $query->result_array();
		}

		$query = $this->db->from("language_key_translations")->where_in("language_key_id", $other_keys)->where_in("language_key_translations.language_id",$languages)->select("translations.translation, language_key_translations.translation_id, language_key_translations.language_id ")->join("translations", "translations.id = language_key_translations.language_key_id")->get();

		if ( $query->num_rows() > 0 ) {
			$results = array_merge($results,$query->result_array());
		}

		$this->db->from("language_key_tokens");
		$this->db->where_in("language_key_id", array_merge($approve_first_keys, $other_keys));
		$this->db->join("tokens", "tokens.id = language_key_tokens.token_id");
		$this->db->select("token,description, language_key_tokens.language_key_id");
		$query = $this->db->get();

		if ( $query->num_rows() > 0) {
			foreach ( $query->result() as $row) {
				$keys[$row->language_key_id]["tokens"][] = array("token" => $row->token, "description" => $row->description);
			}
		}

		foreach ( $results as $row ) {
			if ( $row["language_id"] == $base_language_id ) {
				$keys[$row["language_key_id"]]["base_translation"] = $row["translation"];
			}
			if ( $row["language_id"] == $language_id ) {
				$keys[$row["language_key_id"]]["translation"] = $row["translation"];	
			}
		}

		foreach ( $keys as $index => $key ) {
			unset($keys[$index]["approve_first"]);
		}

		return array_values($keys);
	}
}
?>