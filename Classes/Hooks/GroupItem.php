<?php
/**
 * Hook into group items
 *
 * @author  Tim Lochmüller
 */

namespace HDNET\Focuspoint\Hooks;

use HDNET\Focuspoint\Service\WizardService;
use TYPO3\CMS\Backend\Form\DatabaseFileIconsHookInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hook into group items
 *
 * @hook TYPO3_CONF_VARS|SC_OPTIONS|t3lib/class.t3lib_tceforms.php|dbFileIcons
 */
class GroupItem implements DatabaseFileIconsHookInterface
{

    /**
     * Modifies the parameters for selector box form-field for the db/file/select elements (multiple)
     *
     * @param array  $params           An array of additional parameters, eg: "size", "info", "headers" (array with "selector" and "items"), "noBrowser", "thumbnails
     * @param string $selector         Alternative selector box.
     * @param string $thumbnails       Thumbnail view of images. Only filled if there are images only. This images will be shown under the selectorbox.
     * @param array  $icons            Defined icons next to the selector box.
     * @param string $rightbox         Thumbnail view of images. Only filled if there are other types as images. This images will be shown right next to the selectorbox.
     * @param string $fName            Form element name
     * @param array  $uidList          The array of item-uids. Have a look at \TYPO3\CMS\Backend\Form\FormEngine::dbFileIcons parameter "$itemArray
     * @param array  $additionalParams Array with additional parameters which are be available at method call. Includes $mode, $allowed, $itemArray, $onFocus, $table, $field, $uid.
     * @param object $parentObject     Parent object
     *
     * @return void
     */
    public function dbFileIcons_postProcess(
        array &$params,
        &$selector,
        &$thumbnails,
        array &$icons,
        &$rightbox,
        &$fName,
        array &$uidList,
        array $additionalParams,
        $parentObject
    ) {
        /** @var WizardService $wizardService */
        $wizardService = GeneralUtility::makeInstance('HDNET\\Focuspoint\\Service\\WizardService');
        $icons['R'][] = $wizardService->getWizardButton();

        // JS
        static $alreadyAdded = false;
        if (!$alreadyAdded) {
            $alreadyAdded = true;
            $icons['R'][] = '<script type="text/javascript" src="/typo3conf/ext/focuspoint/Resources/Public/JavaScript/GroupSelect.js"></script>';
        }
    }
}