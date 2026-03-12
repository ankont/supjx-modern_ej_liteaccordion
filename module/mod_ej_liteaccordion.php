<?php
/**
 * @package    Joomla.Module
 * @subpackage mod_ej_liteaccordion
 * @copyright  Copyright (C) 2006 - 2025 Andreas Kontarinis & Element-J.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.php
 * @version    2.0.0
 * @since      2025-06-02
 */

// No direct access
defined('_JEXEC') || die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper; // Για JHtml::script/stylesheet

// Ensure the helper file is loaded
require_once dirname(__FILE__) . '/helper.php';

// Get module parameters
// The $params object is already available in module context
$fldStyleScheme = $params->get('ejla_stylescheme', 'dark');
$fldStyleSchemeCustom = $params->get('ejla_stylescheme_custom', '');
$fld_load_jq = (int) $params->get('ejla_load_jq', 0);
$fldUseCache   = (int) $params->get('ejla_cache', 0);
$fldCacheTime  = (int) $params->get('ejla_cache_time', 900);

// Define variables for CSS and Class
$document = Factory::getDocument();
$relMediaUrl = 'media/' . $module->module . '/';
$relCssUrl = $relMediaUrl . 'css/';
$relJsUrl  = $relMediaUrl . 'js/';
$module_class = ''; // The class to be added to the module's HTML wrapper

// 1. Load the core LiteAccordion CSS file (liteaccordion.css)
// This file is assumed to contain all base styles for 'dark', 'light', 'stitch', 'basic'.
$coreCssFile = $relCssUrl . 'liteaccordion.css';
$fullCoreCssUrl = Uri::base(true) . $coreCssFile;
$fullCoreCssPath = JPATH_ROOT . '/' . $coreCssFile;

if (is_file($fullCoreCssPath)) {
    HTMLHelper::stylesheet($fullCoreCssUrl); // Use HTMLHelper for consistency and potential Joomla optimizations
} else {
    // Log a warning if the core CSS file is missing
    Factory::getApplication()->enqueueMessage(
        JText::sprintf('MOD_EJ_LITEACCORDION_WARNING_CORE_CSS_NOT_FOUND', $fullCoreCssUrl),
        'warning'
    );
}

// 2. Load jQuery if enabled
if ($fld_load_jq) {
    HTMLHelper::_('jquery.framework'); // This loads Joomla's default jQuery if available
}
// Load the liteaccordion JavaScript
$liteAccordionJsFile = $relJsUrl . 'liteaccordion.jquery.min.js'; // Use the minified version
$fullLiteAccordionJsUrl = Uri::base(true) . $liteAccordionJsFile;
$fullLiteAccordionJsPath = JPATH_ROOT . '/' . $liteAccordionJsFile;

if (is_file($fullLiteAccordionJsPath)) {
	HTMLHelper::script($fullLiteAccordionJsUrl, ['version' => 'auto', 'defer' => true]);
} else {
	Factory::getApplication()->enqueueMessage(
		JText::sprintf('MOD_EJ_LITEACCORDION_WARNING_JS_NOT_FOUND', $fullLiteAccordionJsUrl),
		'warning'
	);
}

// Also load easing.js if it's a dependency for liteaccordion (it usually is)
$easingJsFile = $relJsUrl . 'easing.js';
$fullEasingJsUrl = Uri::base(true) . $easingJsFile;
$fullEasingJsPath = JPATH_ROOT . '/' . $easingJsFile;

if (is_file($fullEasingJsPath)) {
	HTMLHelper::script($fullEasingJsUrl, ['version' => 'auto', 'defer' => true]);
} else {
	Factory::getApplication()->enqueueMessage(
		JText::sprintf('MOD_EJ_LITEACCORDION_WARNING_EASING_JS_NOT_FOUND', $fullEasingJsUrl),
		'warning'
	);
}

// 3. Determine the class for the module wrapper and attempt to load custom CSS file if applicable
if ($fldStyleScheme === 'custom') {
    // If user selected 'Custom Class Name', get the class name from the text field
    $customClassName = $fldStyleSchemeCustom;
    $module_class = htmlspecialchars($customClassName);

    // Attempt to load a CSS file with the same name (e.g., my-custom-style.css)
    if (!empty($customClassName)) {
        $potentialCustomFile = $customClassName . '.css';
        $customFileUrl = $baseCssUrl . $potentialCustomFile;
        $fullCustomFilePath = JPATH_ROOT . str_replace(Uri::root(true), '', $customFileUrl);

        if (is_file($fullCustomFilePath)) {
            HTMLHelper::stylesheet($customFileUrl);
        }
        // If the file is not found, no action is taken. The admin can define styles in the template CSS.
    }

} else {
    // For all predefined styles (dark, light, stitch, basic)
    // The class name (e.g., 'dark', 'light') will differentiate the styles defined in liteaccordion.css.
    $module_class = htmlspecialchars($fldStyleScheme);
}

// Trim whitespace from the module class string
$module_class = trim($module_class);

// 4. Cache management
if ($fldUseCache) {
  $cache = Joomla\CMS\Factory::getCache('mod_ej_liteaccordion', 'callback', [
    'defaultgroup' => 'mod_ej_liteaccordion',
    'lifetime'     => $fldCacheTime
  ]);

  $list = $cache->get(
    [ 'modEjLiteAccordionHelper', 'getList' ],
    [ $params ]
  );
} else {
  $list = modEjLiteAccordionHelper::getList($params);
}

// Include the layout file for rendering the module HTML
require ModuleHelper::getLayoutPath('mod_ej_liteaccordion', $params->get('layout', 'default'));
