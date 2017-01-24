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
     * @var
     */
    protected $strBuffer;

    /**
     * @param $strBuffer
     * @param $strTemplate
     * @return mixed
     */
    public function outputTemplate($strBuffer, $strTemplate)
    {

        // Add strBuffer to $this->strBuffer
        $this->strBuffer = $strBuffer;


        if (strpos($this->strBuffer, 'tinymce.init') === false)
        {
            return $this->strBuffer;
        }


        // Add plugins
        $tinyMcePluginPattern = '/window.tinymce(.*)tinymce.init(.*)plugins(.*):(.*)(["\']{1})(.*)\5/sU';
        if (isset($GLOBALS['TINYMCE']['SETTINGS']['PLUGINS']))
        {
            if (is_array($GLOBALS['TINYMCE']['SETTINGS']['PLUGINS']))
            {
                // Add key with empty value if it does not exist
                if (!preg_match($tinyMcePluginPattern, $this->strBuffer, $matches))
                {
                    // Add empty key
                    $this->addRowToConfig('plugins', "''");
                    // Retest
                    preg_match($tinyMcePluginPattern, $this->strBuffer, $matches);
                }
                if (count($matches) > 0)
                {
                    // $matches[6]: string between single/double quotes
                    if (isset($matches[6]))
                    {
                        // Plugins are separated with whitespaces
                        $aPlugins = preg_split("/[\s]+/", $matches[6]);
                        foreach ($GLOBALS['TINYMCE']['SETTINGS']['PLUGINS'] as $plugin)
                        {
                            $aPlugins[] = $plugin;
                        }

                        $aPlugins = array_unique($aPlugins);
                        $strPlugins = trim(implode(' ', $aPlugins));
                        $this->strBuffer = preg_replace($tinyMcePluginPattern, 'window.tinymce\1tinymce.init\2plugins\3:\4\5' . $strPlugins . '\5', $this->strBuffer);
                    }
                }
            }
        }


        // Add buttons to the toolbar
        $tinyMceToolbarPattern = '/window.tinymce(.*)tinymce.init(.*)toolbar(.*):(.*)(["\']{1})(.*)\5/sU';
        if (isset($GLOBALS['TINYMCE']['SETTINGS']['TOOLBAR']))
        {
            if (is_array($GLOBALS['TINYMCE']['SETTINGS']['TOOLBAR']))
            {
                // Add key with empty value if it does not exist
                if (!preg_match($tinyMceToolbarPattern, $this->strBuffer, $matches))
                {
                    // Add empty key
                    $this->addRowToConfig('toolbar', "''");
                    // Retest
                    preg_match($tinyMcePluginPattern, $this->strBuffer, $matches);
                }

                if (count($matches) > 0)
                {
                    // $matches[6]: string between single/double quotes
                    if (isset($matches[6]))
                    {
                        $aButtons = explode("|", $matches[6]);
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
                        $strButtons = trim(implode(' | ', $aButtons));
                        $this->strBuffer = preg_replace($tinyMceToolbarPattern, 'window.tinymce\1tinymce.init\2toolbar\3:\4\5' . $strButtons . '\5', $this->strBuffer);
                    }
                }
            }
        }

        // Add new config rows
        if (isset($GLOBALS['TINYMCE']['SETTINGS']['CONFIG_ROW']))
        {
            if (is_array($GLOBALS['TINYMCE']['SETTINGS']['CONFIG_ROW']))
            {
                foreach ($GLOBALS['TINYMCE']['SETTINGS']['CONFIG_ROW'] as $key => $row)
                {
                    $this->addRowToConfig($key, $row);
                }
            }
        }

        return $this->strBuffer;
    }


    /**
     * Add a new config row to tinymce.init({})
     * @param $key
     * @param $value
     */
    private function addRowToConfig($key, $value)
    {
        $tinyMceRowPattern = '/window.tinymce(.*)tinymce.init(.*)\({(.*)<\/script>/sU';
        if (preg_match($tinyMceRowPattern, $this->strBuffer, $matches))
        {

            if (isset($matches[3]))
            {
                $strRow = "\n\t" . $key . ": " . $value . ",";
                $this->strBuffer = str_replace($matches[3], $strRow . $matches[3], $this->strBuffer);
            }
        }
    }
}

