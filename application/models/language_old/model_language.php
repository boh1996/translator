<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Model for CodeIgniter frontend language files editor.
 *
 * Tested with CodeIgniter 2.x
 * @author		Eliza Witkowska (http://codebusters.pl/en/)
 * @version		2.2
 * @license		MIT License
 * @link		http://blog.codebusters.pl/en/entry/codeigniter-frontend-language-files-editor/
 * @link 		https://github.com/kokers/Codeigniter-Frontend-Language-Files-Editor
 */

class Model_language extends CI_Model {

	/**
	 * Get list of languages based on /application_folder/languge/
	 * and number of php files in it
	 *
	 * @return	array
	 * @since 1.0
	 * @access public
	 */
	public function get_languages () {
		$dir = APPPATH."language/";
		$dh  = opendir($dir);
		$i = 0;
		while ( false !== ($filename = readdir($dh)) ) {
			if ( $filename !== '.' && $filename !== '..' && is_dir($dir.$filename)){
				$files[$i]['dir'] = $filename;
				$files[$i]['count'] = $this->get_count_lfiles($filename);
				$i++;
			}
		}
		return (!empty($files)) ? $files : FALSE;
	}

	/**
	 * Get list of files from language directory
	 *
	 * @param string $dir The directory to search in
	 * @return	array
	 * @since 1.0
	 * @access public
	 */
	public function get_list_lfiles ( $dir ) {
		if ( !is_dir(APPPATH."language/$dir/") ) {
			return FALSE;
		}
		$dir = APPPATH."language/$dir/";
		$dh  = opendir($dir);
		while ( false !== ($filename = readdir($dh)) ) {
			if ( $filename !== '.' && $filename !== '..' && !is_dir($dir.$filename) && pathinfo($filename, PATHINFO_EXTENSION) =='php' && substr($filename,0,7) != 'backup_' ) {
				$files[] = $filename;
			}
		}
		return (!empty($files)) ? $files : FALSE;
	}

	/**
	 * Get number of files from language directory
	 *
	 * @param string $language The name of the language to list the files of
	 * @return	int
	 * @since 1.0
	 * @access public
	 */
	public function get_count_lfiles ( $language ) {
		if ( !is_dir(APPPATH."language/".$language."/") ) {
			return FALSE;
		}
		$dir = APPPATH."language/".$language."/";
		$dh  = opendir($dir); 
		$i = 0 ;
		while (false !== ($filename = readdir($dh))) {
			if ( $filename !== '.' && $filename !== '..' && !is_dir($dir.$filename) && pathinfo($filename, PATHINFO_EXTENSION) == 'php' && substr($filename,0,7) != 'backup_') {
				$i++;
			}
		}
		return (int) $i;
	}

	/**
	 * Get list of languages where file exist
	 *
	 * @param string $filename The name of the file to search for
	 * @since 1.0
	 * @access public
	 * @return	array
	 */
	public function file_in_language ( $filename ) {
		$languages = $this->get_languages();
		if( $languages !== FALSE ) {
			foreach ( $languages as $language ) {
				$files = get_filenames(APPPATH."language/".$language['dir']."/");
				if ( in_array($filename,$files) ) {
					$results[] = $language['dir'];
				}
			}
			return $results;
		}
		return FALSE;
	}

	/**
	 * Get list of keys for file from database
	 *
	 * @param string $filename The name of the file to search for in the database
	 * @return	array
	 * @since 1.0
	 * @access public
	 */
	public function get_keys_from_db ( $filename ) {
		$this->db->select('key as `keys`');
		$query = $this->db->get_where('language_keys', array('filename' => $filename));
		if ( $query->num_rows() ) {
			$result = $query->result();
			foreach ( $result as $row ) {
				$tab[] = $row->keys;
			}
		}
		return ( !empty($row) ) ? $tab : FALSE;
   }

	/**
	 * Retrives all the comments for a file
	 *
	 * @param string $filename The name of the file to retrive comments for
	 * @return	array
	 * @since 1.0
	 * @access public
	 */
	public function get_comments_from_db ( $filename ) {
		$this->db->select('key as `keys`,comment');
		$query = $this->db->get_where('language_keys', array('filename' => $filename));
		if ( $query->num_rows() ) {
			$result = $query->result();
			foreach ( $result as $row ) {
				$tab[$row->keys] = $row->comment;
			}
		}
		return (!empty($row)) ? $tab : FALSE;
	}


	/**
	 * Update all keys in database, by removing previous and adding new.
	 *
	 * @param array $keys An array of keys to add
	 * @param string $filename The name of the file to update the keys for
	 * @return	bool
	 * @since 1.0
	 * @access public
	 */
	public function update_all_keys ( $keys, $filename ) {
		$this->delete_all_keys($filename);
		return $this->add_keys($keys,$filename);
	}

	/**
	 * Add keys to database
	 *
	 * @param array $keys An array of keys to add
	 * @param string $filename The filename where the keys are located
	 * @return	bool
	 * @since 1.0
	 * @access public
	 */
	public function add_keys ( $keys, $filename) {
		if(!is_array($keys)){
			return FALSE;
		}
		foreach ($keys as $key){
			$data[] = array(
				'key' => $key,
				'filename' => $filename
			);
		}
		$this->db->insert_batch('language_keys',$data);
		return ( $this->db->affected_rows() ) ? TRUE : FALSE;
	}


	/**
	 * Delete keys from database if file does not exists in any language
	 * 
	 * @param  string $filename The name of the file to search if exists
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function delete_keys ( $filename ) {
		$languages = $this->get_languages();
		if ($languages !== FALSE ) {
			foreach ($languages as $language ) {
				$files = get_filenames(APPPATH."language/".$language['dir']."/");
				if ( in_array($filename,$files) ) {
					return FALSE;
				}
			}
			if ( $this->delete_all_keys($filename) ) {
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}

	/**
	 * Delete keys from database
	 * 
	 * @param  string $filename The name of the file to remove all the keys for
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function delete_all_keys ( $filename ) {
		$this->db->delete('language_keys', array(
			'filename' => $filename
		));
		return ( $this->db->affected_rows() ) ? TRUE : FALSE;
	}

	/**
	 * Removes one key that is associated with a file
	 * 
	 * @param  string $key      The name of the key to remove
	 * @param  string $filename The name of the file that where the key is located
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function delete_one_key ( $key, $filename ) {
		$this->db->delete('language_keys',array(
			'filename' => $filename,
			'key' => $key
		));
		return ( $this->db->affected_rows() ) ? TRUE : FALSE;
	}

	/**
	 * This functoon saves all comments for a file to the database
	 * 
	 * @param array $comments An array of comments to save, in the format "language_key" => "comment"
	 * @param string $filename    The name of the file the comments are associated with
	 * @since 1.0
	 * @access public
	 * @return boolean
	 */
	public function add_comments ( $comments, $filename ) {
		if ( !is_array($comments) ) {
			return FALSE;
		}
		$this->db->trans_start();
		foreach ( $comments as $key => $comment ) {
			$this->db->where('key', $key);
			$this->db->where('filename', $filename);
			$this->db->update('language_keys',array(
				'comment'=>$comment
			));
		}
		$this->db->trans_complete();
		return ( $this->db->trans_status() ) ? TRUE : FALSE;
	}

	/**
	 * This function inserts the translation tokens into the database
	 * 
	 * @param array $tokens The tokens to insert
	 * @param integer $key_id    The language key id that these tokens contains to
	 * @return boolean
	 * @since 2.2
	 * @access public
	 */
	public function add_tokens ( $tokens, $key_id ) {
		if ( !is_array($tokens) ) {
			return FALSE;
		}
		$this->db->trans_start();
 
		foreach ( $tokens as $variable => $description ) {
			$this->db->insert("language_tokens",array(
				"description" => $description,
				"variable" => $variable,
				"language_key_id" => $key_id
			));
		}

		$this->db->trans_complete();
		return ( $this->db->trans_status() ) ? TRUE : FALSE;
	}

	/**
	 * This function collects all the translation tokens for all of the selected keys
	 * 
	 * @param  array  $keys The keys to search for
	 * @since 2.2
	 * @return boolean|array
	 */
	public function get_tokens_from_db_using_keys (array $keys) {
		$tokens = array();
		$this->db->trans_start();
		foreach ($keys as $key) {
			$query = $this->db->select('id')->from("language_keys")->where(array("key" => $key))->get();
			if ($query->num_rows() > 0) {
			   	$row = $query->row(); 
			   	$id = $row->id;

			   	$this->db->select('description,variable')->where(array("language_key_id" => $id));
			 	$query = $this->db->get("language_tokens");
			 	if ($query->num_rows() > 0) {
			 		$tokens[$key] = $query->result_array();
			 	}
			}
		}	
		$this->db->trans_complete();
		return (count($tokens) > 0) ? $tokens : FALSE;
	}

}
/* End of file model_language.php */