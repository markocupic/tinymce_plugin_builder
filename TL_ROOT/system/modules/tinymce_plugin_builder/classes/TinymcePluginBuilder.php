<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace TinymcePluginBuilder;


/**
 * Provide methods to add plugins to tinymce rte
 *
 *
 * @author Marko Cupic <https://github.com/markocupic>
 */
class TinymcePluginBuilder
{


    /**
     * @param $strBuffer
     * @param $strTemplate
     * @return mixed
     */
    public static function outputBackendTemplate($strBuffer, $strTemplate)
    {

        if (strpos($strBuffer, 'tinymce.init') === false)
        {
            return $strBuffer;
        }


        // Add plugins
        $tinyMcePluginPattern = '/window.tinymce(.*)tinymce.init(.*)plugins:(.*)[\'"](.*)[\'"]/sU';
        if (preg_match($tinyMcePluginPattern, $strBuffer, $matches))
        {
            if (isset($GLOBALS['TINYMCE']['SETTINGS']['PLUGINS']))
            {

                if (is_array($GLOBALS['TINYMCE']['SETTINGS']['PLUGINS']))
                {
                    // $matches[4]: string between single/double quotes
                    if (isset($matches[4]))
                    {
                        $aPlugins = explode(" ", $matches[4]);
                        foreach ($GLOBALS['TINYMCE']['SETTINGS']['PLUGINS'] as $plugin)
                        {
                            $aPlugins[] = $plugin;
                        }
                        $aPlugins = array_unique($aPlugins);
                        $strPlugins = implode(' ', $aPlugins);
                        $strBuffer = str_replace($matches[4], $strPlugins, $strBuffer);
                    }
                }
            }
        }


        // Add buttons to the toolbar
        $tinyMceToolbarPattern = '/window.tinymce(.*)tinymce.init(.*)toolbar:(.*)[\'"](.*)[\'"]/sU';
        if (preg_match($tinyMceToolbarPattern, $strBuffer, $matches))
        {
            if (isset($GLOBALS['TINYMCE']['SETTINGS']['TOOLBAR']))
            {

                if (is_array($GLOBALS['TINYMCE']['SETTINGS']['TOOLBAR']))
                {
                    // $matches[4]: string between single/double quotes
                    if (isset($matches[4]))
                    {
                        $aButtons = explode("|", $matches[4]);
                        $aButtons = array_map(function ($item)
                        {
                            // Remove whitespaces
                            return trim($item);
                        }, $aButtons);

                        foreach ($GLOBALS['TINYMCE']['SETTINGS']['TOOLBAR'] as $button)
                        {
                            $aButtons[] = $button;
                        }

                        $aButtons = array_unique($aButtons);
                        $strButtons = implode(' | ', $aButtons);
                        $strBuffer = str_replace($matches[4], $strButtons, $strBuffer);
                    }
                }
            }
        }

        // Add new config rows
        $strRows = "";
        $tinyMceRowPattern = '/window.tinymce(.*)tinymce.init(.*)\({(.*)<\/script>/sU';
        if (preg_match($tinyMceRowPattern, $strBuffer, $matches))
        {
            if (isset($GLOBALS['TINYMCE']['SETTINGS']['CONFIG_ROW']))
            {

                if (is_array($GLOBALS['TINYMCE']['SETTINGS']['CONFIG_ROW']))
                {
                    if (isset($matches[3]))
                    {

                        foreach ($GLOBALS['TINYMCE']['SETTINGS']['CONFIG_ROW'] as $key => $row)
                        {
                            $strRows .= "\n\t" . $key . ": " . $row . ",";
                        }

                        $strBuffer = str_replace($matches[3], $strRows . $matches[3], $strBuffer);
                    }
                }
            }
        }


        return $strBuffer;
    }
}

