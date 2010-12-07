<?php
/**
 * Creates a form attribute which is able to select a theme
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 * @version		SVN: $Id$
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * lists of values - this will go away after the refactoring is complete
 */
include_once ICMS_ROOT_PATH."/class/xoopslists.php";

/**
 * A select box with available themes
 *
 * @category	ICMS
 * @package     Form
 * @subpackage  Elements
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 */
class icms_form_elements_select_Theme extends icms_form_elements_Select {
	/**
	 * Constructor
	 *
	 * @param	string	$caption
	 * @param	string	$name
	 * @param	mixed	$value	Pre-selected value (or array of them).
	 * @param	int		$size	Number or rows. "1" makes a drop-down-list
	 */
	public function __construct($caption, $name, $value = null, $size = 1) {
		parent::__construct($caption, $name, $value, $size);
		$this->addOptionArray(icms_view_theme_Factory::getThemesList());
	}
}

