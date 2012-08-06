<?php
	/** peanut **
	 * the small php framework
	 * (c) Phelipe de Sterlich | Moorea Software
	 * -----
	 * file: system/classes/template.php
	 * classe gestione templates
	 **/

	class Template extends base
	{
		protected $templateName = "";
		private $pageVars = array();

		function __construct($templateName = "")
		{
			$this->templateName = $templateName;
		}

		/**
		 * trova un eventuale percorso alternativo per il template
		 *
		 * @return string
		 * @author Phelipe de Sterlich
		 **/
		function _findAlternateName($originalName)
		{
			$name = explode(DS, $originalName);
			if (count($name) > 1) $name[0] = str_replace("_", DS, $name[0]);
			return implode(DS, $name);
		}

		function _render($fileName, $vars, $baseDir)
		{
			$fileFound = true;
			$altFileName = $this->_findAlternateName($fileName);

			if (file_exists(APP.DS.$baseDir.DS.$fileName)) {
				$fullPath = APP.DS.$baseDir.DS.$fileName;
			} else if (file_exists(APP.DS.$baseDir.DS.$altFileName)) {
				$fullPath = APP.DS.$baseDir.DS.$altFileName;
			} else if (file_exists(SYSTEM.DS.$baseDir.DS.$fileName)) {
				$fullPath = SYSTEM.DS.$baseDir.DS.$fileName;
			} else {
				$fileFound = false;
			}

			if ($fileFound) {
				extract($vars);
				ob_start();
				require ($fullPath);
				return ob_get_clean();
			} else {
				die (__("system.template_file_not_found", array(":filename" => $fileName)));
			}
		}

		function set($var, $val = null)
		{
			if (is_array($var)) {
				foreach ($var as $key => $value) {
					$this->pageVars[$key] = $value;
				}
			} else {
				$this->pageVars[$var] = $val;
			}
			return $this;
		}

		function element($elementName, $params = array())
		{
			return $this->_render($elementName, $params, "elements");
		}

		function render($baseDir = "views")
		{
			return $this->_render($this->templateName, $this->pageVars, $baseDir);
		}

	}
	

?>