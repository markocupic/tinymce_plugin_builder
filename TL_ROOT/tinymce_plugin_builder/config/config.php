<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */

if ($GLOBALS['TL_CONFIG']['useRTE'])
{
    new TinymcePluginBuilder();
    $GLOBALS['TL_HOOKS']['outputBackendTemplate'][] = array('TinymcePluginBuilder', 'outputBackendTemplate');

}
