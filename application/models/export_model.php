<?php
class Export_Model extends CI_Model {

	public function fetch_files_data ( $Project ) {
		$file_ids = array();
		$language_ids = array();

		foreach ( $Project->files as $file ) {
			$file_ids[$file->id] = $file->id;
		}

		foreach ( $Project->languages as $language ) {
			$language_ids[] = $language->id;
		}

		$this->db->from("language_keys");
		$this->db->where_in("language_file_id", $file_ids);
		$keys_query = $this->db->get();

		if ( ! $keys_query->num_rows() ) return false;

		$this->load->library("key");

		$keys = array();
		$key_ids = array();

		foreach ( $keys_query->result_array() as $row ) {
			$Key = new Key();
			$Key->id = $row["id"];
			$keys[$Key->id] = $Key->Convert_From_Database($row);
			$key_ids[] = $Key->id;
		}

		$translations = $this->fetch_keys_translations($key_ids, $language_ids);

		if ( $translations !== false ) {
			foreach ( $translations as $index => $translation ) {
				$language_key_id = $translation["language_key_id"];
				$File = self::_find_by_id($Project->files, $keys[$language_key_id]["file"]);

				// If the translation for this key need approval, and the key hasn't been apporved, then skip the translation
				if ( ! (approve_first_key_bool($Project->approve_first,$File->approve_first,$keys[$language_key_id]["approve_first"]) === true && $translation["approved"] == 0 ) ) {
					$keys[$language_key_id]["translations"][$translation["language"]] = $translation;
				}
			}
		}

		$tokens = $this->fetch_keys_tokens($key_ids);

		if ( $tokens !== false ) {
			foreach ( $tokens as $index => $token ) {
				$keys[$token["key"]]["tokens"][$token["id"]] = $token;
			}
		}

		print_r($keys);
	}

	/**
	 * Loops through a collection of objects, and finds the one that matches the searched "id" property value
	 * 
	 * @param  arrray<object<*>> $collection The collection to search in
	 * @param  integer $id         The id to search for
	 * @return object<*>
	 */
	private function _find_by_id ( $collection, $id ) {
		foreach ( $collection as $key => $object ) {
			if ( $object->id == $id ) return $object;
		}

		return false;
	}

	/**
	 * Fetches all the selected tokens
	 * 
	 * @param  array<integer> $keys The keys to select tokens for
	 * @return array<array>
	 */
	public function fetch_keys_tokens ( $keys ) {
		$this->db->from("language_key_tokens");
		$this->db->where_in("language_key_id", $keys);
		$this->db->join("tokens", "language_key_tokens.token_id = tokens.id");
		$select = array(
			"language_key_tokens.language_key_id",
			"tokens.id",
			"tokens.token",
			"tokens.description"
		);
		$this->db->select(implode(",", $select));
		$query = $this->db->get();

		if ( ! $query->num_rows() ) return false;

		$tokens = array();
		$this->load->library("token");
		$Token = new Token();

		foreach ( $query->result_array() as $row ) {
			$row["key"] = $row["language_key_id"];
			unset($row["language_key_id"]);
			$tokens[] = $Token->Convert_From_Database($row);
		}

		return $tokens;
	}

	/**
	 * Fetches translations based on the selected language keys and languages
	 * 
	 * @param  array<integer> $keys      The language keys to select translations for
	 * @param  array<integer> $languages The languages to select translations for
	 * @return array<array>
	 */
	public function fetch_keys_translations ( $keys, $languages ) {
		$this->db->from("language_key_translations");
		$this->db->where_in("language_key_translations.language_key_id", $keys);
		$this->db->where_in("language_key_translations.language_id", $languages);
		$this->db->join("translations", "language_key_translations.translation_id = translations.id");
		$select = array(
			"translations.language_id",
			"translations.id",
			"translations.translation",
			"translations.user_id",
			"translations.time_created",
			"translations.last_updated",
			"language_key_translations.approved",
			"language_key_translations.language_key_id"
		);
		$this->db->select(implode(",", $select));
		$query = $this->db->get();

		if ( ! $query->num_rows() ) return false;

		$translations = array();
		$this->load->library("translation");
		$Translation = new Translation();

		foreach ( $query->result_array() as $row ) {
			$translations[] = $Translation->Convert_From_Database($row);
		}

		return $translations;
	}

	/**
	 * Fetches all the rows of the files in $files
	 * 
	 * @param  array<integer> $files The files to fetch
	 * @return array<object>
	 */
	public function fetch_files ( $files ) {
		$this->db->from("language_files");
		$this->db->where_in($files);
		$query = $this->db->get();

		if ( ! $query->num_rows() ) return false;

		$rows = array();

		foreach ( $query->result() as $row ) {
			$rows[] = $row;
		}

		return $rows;
	}

	/**
	 * Fetches a column, from each row in an array
	 * 
	 * @param  array<object> $array  The array to fech {column} from
	 * @param  string $column The column to fetch
	 * @return array
	 */
	public function get_column ( $array, $column ) {
		$data = array();
		foreach ( $array as $row ) {
			$data[] = $row->{$column};
		}

		return $data;
	}

	/**
	 * Fetches all the language keys for a file
	 * 
	 * @param  integer $file_id The file to fetch 
	 * @return array<object>
	 */
	public function fetch_file_keys ( $file_id ) {
		$this->db->from("language_keys");
		$this->db->where(array(
			"language_file_id"	=> $file_id
		));
		$query = $this->db->get();

		if ( ! $query->num_rows() ) return false;

		$keys = array();

		foreach ( $query->result() as $row ) {
			$keys[] = $row;
		}

		return $keys;
	}

	/**
	 * Fetches a list of all the languages assigned to a project
	 * 
	 * @param  integer $project_id The project to fetch languages for
	 * @return array<object>
	 */
	public function fetch_project_languages ( $project_id ) {
		$this->db->from("project_languages");
		$this->db->where(array(
			"project_id"	=> $project_id
		));
		$query = $this->db->get();

		if ( ! $query->num_rows() ) return false;

		$languages = array();

		foreach ( $query->result() as $row ) {
			$languages[] = $row;
		}

		return $languages;
	}

	/**
	 * Fetches a list of the available project files
	 * 
	 * @param  integer $project_id The project to fetch files for.
	 * @return array<object>
	 */
	public function fetch_project_files_ids ( $project_id ) {
		$this->db->from("project_language_files");
		$this->db->where(array(
			"project_id"	=> $project_id
		));
		$query = $this->db->get();

		if ( ! $query->num_rows() ) return false;

		$files = array();

		foreach ( $query->result() as $row ) {
			$files[] = $row;
		}

		return $files;
	}
}
?>