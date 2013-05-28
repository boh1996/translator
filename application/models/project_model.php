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

	/**
	 * Assosiactes a language with a project
	 * 
	 * @param integer $project_id  The project to associate with
	 * @param integer $language_id The language to make a link with the project
	 * @return boolean
	 */
	public function add_language ( $project_id, $language_id ) {
		if ( $this->project_language_exists($project_id, $language_id) ) return true;

		$this->db->insert("project_languages",array(
			"project_id" 	=> $project_id,
			"language_id" 	=> $language_id
		));

		return true;
	}

	/**
	 * This function returns a projects standard language
	 * @since 1.0
	 * @access public
	 * @param  integer $project_id The project to find base language for
	 * @return integer
	 */
	public function base_language ( $project_id ) {
		$this->db->from("projects");
		$this->db->where(array(
			"id"	=> $project_id
		))->select("base_language_id");
		$query = $this->db->get();

		if ( ! $query->num_rows() ) return 1;

		$row = $query->row();

		return ($row->base_language_id !== false && $row->base_language_id != null) ? $row->base_language_id : 1;
	}

	/**
	 * Fetches all the database
	 * 
	 * @param  Project $Project The project to fetch keys for
	 * @return array
	 */
	public function project_files_keys ( Project $Project ) {
		$files = array();

		foreach ( $Project->files as $file ) {
			$files[] = $file->id;
		}

		array_unique($files);

		if ( ! is_array($files) || ( is_array($files) && count($files) == 0 ) ) {

		}

		$this->db->from("language_keys");
		$this->db->where_in("language_file_id", $files);
		$this->db->select("id,approve_first");

		$keysQuery = $this->db->get();

		if ( ! $keysQuery->num_rows() ) return false;

		$keys = array();

		foreach ( $keysQuery->result() as $key ) {
			$keys[] = $key;
		}

		return $keys;
	}

	/**
	 * Fetches all the different translation for a projects language
	 * 
	 * @param  Project $Project  The project to fetch translations for
	 * @param  integer  $language The language to fetch the translations for
	 * @return array
	 */
	public function project_files_translations ( Project $Project, $language ) {
		$files = array();

		foreach ( $Project->files as $file ) {
			$files[] = $file->id;
		}

		array_unique($files);

		if ( ! is_array($files) || ( is_array($files) && count($files) == 0 ) ) {
			return false;
		}

		$this->db->from("language_keys");
		$this->db->where_in("language_file_id", $files);
		$this->db->select("
			language_keys.id as language_key_id,
			language_keys.approve_first,
			language_key_translations.id as language_key_translation_id,
			language_key_translations.approved"
		);
		$this->db->where("language_key_translations.language_id",$language);
		$this->db->join("language_key_translations", "language_key_translations.language_key_id = language_keys.id");

		$translationsQuery = $this->db->get();
		$translations = array();

		foreach ( $translationsQuery->result() as $translation ) {
			$translations[$translation->language_key_id] = $translation;
		}

		return $translations;
	}

	/**
	 * Calculates the status for each language of a project
	 * 
	 * @param  Project $Project   The project
	 * @param  array<integer>  $languages The different languages
	 * @return array
	 */
	public function project_languages_progress ( Project $Project, $languages ) {
		if ( isset($Project->files) && is_array($Project->files) && count($Project->files) > 0 ) {
			$languagesStatus = array();

			foreach ( $languages as $language) {
				$translations = self::project_files_translations($Project,$language);
				$keys = self::project_files_keys($Project);

				$status = array(
					"count" => count($keys),
					"done" => 0,
					"missing_approval" => 0,
					"missing" => 0,
					"done_count" => 0,
					"missing_approval_count" => 0,
					"missing_count" => 0
				);

				foreach ( $keys as $key ) {
					if ( is_array($translations) && isset($translations[$key->id]) ) {
						$translation = $translations[$key->id];

						if ( ( $translation->approve_first == 1 && $translation->approved == 1 ) || $translation->approve_first == 0 ) {
							$status["done_count"]++;
						} else {
							$status["missing_approval_count"]++;
						}
					} else {
						$status["missing_count"]++;
					}
				}

				$status["done"] = ( $status["done_count"] > 0 ) ? round($status["done_count"]/count($keys)*100) : false;
				$status["missing"] = ( $status["missing_count"] > 0 ) ? round($status["missing_count"]/count($keys)*100) : false;
				$status["missing_approval"] = ( $status["missing_approval_count"] > 0 ) ? round($status["missing_approval_count"]/count($keys)*100) : false;

				$languagesStatus[$language] = $status;
			}

			return $languagesStatus;
		} else {
			return false;
		}
	}
}
?>