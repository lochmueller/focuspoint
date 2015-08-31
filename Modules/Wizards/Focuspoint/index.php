<?php
/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Wizard to add new records to a group/select TCEform formfield
 */

/** @var \HDNET\Focuspoint\Controller\Wizard\FocuspointController $editController */
$editController = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('HDNET\\Focuspoint\\Controller\\Wizard\\FocuspointController');
echo $editController->main();
