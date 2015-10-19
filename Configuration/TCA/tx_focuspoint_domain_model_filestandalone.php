<?php

/**
 * Base TCA generation for the model HDNET\\Focuspoint\\Domain\\Model\\FileStandalone
 */

$base = \HDNET\Autoloader\Utility\ModelUtility::getTcaInformation('HDNET\\Focuspoint\\Domain\\Model\\FileStandalone');

$custom = array();

return \HDNET\Autoloader\Utility\ArrayUtility::mergeRecursiveDistinct($base, $custom);