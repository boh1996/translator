<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Lang extends CI_Lang {

	/**
    * Fetch a single line of text from the language array
    * Un-quotes a quoted string if it's not .
	 *
	 * @access	public
	 * @param	string	$line	the language line
	 * @param  array $params Optional "tokens/variables" to replace in the line
	 * @return	string   Un-quotes a quoted string if it's not an array.
	 */
	public function line ($line, $params = null) {
		$value = ($line == '' OR ! isset($this->language[$line])) ? FALSE : $this->language[$line];

		if ($value === FALSE)
		{
			log_message('error', 'Could not find the language line "'.$line.'"');
			return "$line";
		}

		if (!is_null($params)){
            $value = $this->_ni_line($value, $params); 
        }

		return is_array($value) ? $value : stripslashes($value);
	}

	   /**
     * This function replaces the specified tokens in the language line
     * @since 1.0
     * @access public
     * @param  stirng $str    The string to replace the tokesn in
     * @param  array $params The tokens to replace and their replacements
     * @return string
     */
    private function _ni_line($str, $params){
        $return = $str;
        
        $params = is_array($params) ? $params : array($params);   
        
        $search = array();
        $cnt = 1;
        foreach($params as $param){
            $search[$cnt] = "/\\${$cnt}/";
            $cnt++;
        }
                
        unset($search[0]);
        
        $return = preg_replace($search, $params, $return);
        
        return $return;
    }

}

/* End of file MY_Lang.php */
/* Location: ./application/core/MY_Lang.php */
