<?php
class User_Control{

	/**
	 * A pointer to the current instance of CodeIgniter
	 * @var object
	 * @since 1.0
	 * @access private
	 */
	private $_CI = NULL;

	/**
	 * The current user language
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $language = "english";

	/**
	 * The current user
	 * @since 1.0
	 * @access public
	 * @var object
	 */
	public $user = null;

	/**
	 * The standard language files to load
	 * @since 1.0
	 * @access public
	 * @var array
	 */
	public $languageFiles = array();

	/**
	 * This function calls all the needed security functions
	 * @since 1.0
	 * @access public
	 */
	public function __construct(){
		$this->_CI =& get_instance();

		$this->_CI->load->config("settings");
		
		
		if (isset($_GET["language"]) && array_key_exists($_GET["language"], $this->_CI->config->item("languages"))) {
			$this->language = $_GET["language"];
		}
		if (empty($this->language)) {
			$this->language = $this->_CI->config->item("language");
		}
		$this->_CI->load->library("user");
		$this->user = new User();
		$this->user->Load(1);
		self::batch_load_lang_files($this->languageFiles);
	}

	/**
	 * This function loads up lang files using an array
	 * @param  array  $files The array of file without extension and _lang
	 * @since 1.0
	 * @access public
	 */
	public function batch_load_lang_files ( array $files ) {
		$this->languageFiles = array_unique(array_merge($this->languageFiles,$files));
 		foreach ($files as $file) {
 			if (is_file(FCPATH."application/language/".$this->language."/".$file."_lang.php")) {
 				$this->_CI->lang->load($file, $this->language);
 			}
 		}
	}

	/**
	 * This function changes the current language and reloads the standard language files
	 * @since 1.0
	 * @access public
	 * @param string $language The language to change too
	 */
	public function ReloadLanguageFiles ($language) {
		if (array_key_exists($language , $this->_CI->config->item("languages"))) {
			$this->language = $language;
			self::batch_load_lang_files($this->languageFiles);
		}
	}

	/**
	 * This function replaces all the template variables with the desired value
	 * @param array $variables An array of the keys to replace and the values to replace them with
	 * @param string $template  The template as string
	 * @return string
	 * @since 1.0
	 * @access private
	 */
	public function Template ( $variables, $template ) {
		$content = $template;
		foreach ($variables as $variable => $value) {
			$content = str_replace($variable, $value, $content);
		}
		return $content;
	}

	/**
	 * This function checks if http should be enabled
	 * @since 1.0
	 * @access public
	 */
	public function CheckHTTPS ($url) {
		$url = str_replace("http://", "", $url);
		$url = str_replace("https://","", $url);
		return ($this->_CI->config->item("https") == true) ? "https://" . $url:  "http://" . $url;
	}

	/**
	 * This function can merge Víew data and Standard view data
	 * @since 1.0
	 * @access public
	 * @param array $params The view data
	 * @return array
	 */
	public function ControllerInfo ($params = null) {
		$languages = $this->_CI->config->item("languages");
		$settings = array(
			"languages" => $languages,
			"language" => $this->language,
			"languageString" => $languages[$this->language],
			"base_url" => $this->CheckHTTPS(base_url()),
			"asset_url" => $this->CheckHTTPS(base_url().$this->_CI->config->item("asset_url")),
			"jquery_url" => $this->_CI->config->item("jquery_url"),
			"jqueryui_version" => $this->_CI->config->item("jqueryui_version"),
		);
		if (!is_null($params)) {
			return array_unique(array_merge($params, $settings));
		} else {
			return array_unique($settings);
		}
	}
}
?>