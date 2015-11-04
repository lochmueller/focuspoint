<?php
/**
 * Hook into group items
 *
 * NOTE: In 7.0 the interface is changed, becuse the $parentObject do not use a type hint anymore
 * That is the reason of the DIRTY abstract class in front of the concrete implementation
 *
 * @author  Tim Lochmüller
 */

namespace HDNET\Focuspoint\Hooks;

use HDNET\Focuspoint\Service\WizardService;
use TYPO3\CMS\Backend\Form\DatabaseFileIconsHookInterface;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

// Work arround for changing Interface
if (GeneralUtility::compat_version('7.0')) {
    abstract class AbstractGroupItem implements DatabaseFileIconsHookInterface
    {

        /**
         * Modifies the parameters for selector box form-field for the db/file/select elements (multiple)
         *
         * @param array $params An array of additional parameters, eg: "size", "info", "headers" (array with "selector" and "items"), "noBrowser", "thumbnails
         * @param string $selector Alternative selector box.
         * @param string $thumbnails Thumbnail view of images. Only filled if there are images only. This images will be shown under the selectorbox.
         * @param array $icons Defined icons next to the selector box.
         * @param string $rightbox Thumbnail view of images. Only filled if there are other types as images. This images will be shown right next to the selectorbox.
         * @param string $fName Form element name
         * @param array $uidList The array of item-uids. Have a look at \TYPO3\CMS\Backend\Form\FormEngine::dbFileIcons parameter "$itemArray
         * @param array $additionalParams Array with additional parameters which are be available at method call. Includes $mode, $allowed, $itemArray, $onFocus, $table, $field, $uid.
         * @param object $parentObject Parent object
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
            $this->dbFileIcons_postProcessCompatibility($params,
                $selector,
                $thumbnails,
                $icons,
                $rightbox,
                $fName,
                $uidList,
                $additionalParams,
                $parentObject);
        }

        protected function dbFileIcons_postProcessCompatibility(
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

        }
    }
} else {
    abstract class AbstractGroupItem implements DatabaseFileIconsHookInterface
    {

        /**
         * Modifies the parameters for selector box form-field for the db/file/select elements (multiple)
         *
         * @param array $params An array of additional parameters, eg: "size", "info", "headers" (array with "selector" and "items"), "noBrowser", "thumbnails
         * @param string $selector Alternative selector box.
         * @param string $thumbnails Thumbnail view of images. Only filled if there are images only. This images will be shown under the selectorbox.
         * @param array $icons Defined icons next to the selector box.
         * @param string $rightbox Thumbnail view of images. Only filled if there are other types as images. This images will be shown right next to the selectorbox.
         * @param string $fName Form element name
         * @param array $uidList The array of item-uids. Have a look at \TYPO3\CMS\Backend\Form\FormEngine::dbFileIcons parameter "$itemArray
         * @param array $additionalParams Array with additional parameters which are be available at method call. Includes $mode, $allowed, $itemArray, $onFocus, $table, $field, $uid.
         * @param \TYPO3\CMS\Backend\Form\FormEngine $parentObject Parent object
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
            \TYPO3\CMS\Backend\Form\FormEngine $parentObject
        ) {
            $this->dbFileIcons_postProcessCompatibility($params,
                $selector,
                $thumbnails,
                $icons,
                $rightbox,
                $fName,
                $uidList,
                $additionalParams,
                $parentObject);
        }

        protected function dbFileIcons_postProcessCompatibility(
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

        }
    }
}


/**
 * Hook into group items
 *
 * @hook TYPO3_CONF_VARS|SC_OPTIONS|t3lib/class.t3lib_tceforms.php|dbFileIcons
 */
class GroupItem extends AbstractGroupItem
{

    /**
     * Modifies the parameters for selector box form-field for the db/file/select elements (multiple)
     *
     * @param array $params An array of additional parameters, eg: "size", "info", "headers" (array with "selector" and "items"), "noBrowser", "thumbnails
     * @param string $selector Alternative selector box.
     * @param string $thumbnails Thumbnail view of images. Only filled if there are images only. This images will be shown under the selectorbox.
     * @param array $icons Defined icons next to the selector box.
     * @param string $rightbox Thumbnail view of images. Only filled if there are other types as images. This images will be shown right next to the selectorbox.
     * @param string $fName Form element name
     * @param array $uidList The array of item-uids. Have a look at \TYPO3\CMS\Backend\Form\FormEngine::dbFileIcons parameter "$itemArray
     * @param array $additionalParams Array with additional parameters which are be available at method call. Includes $mode, $allowed, $itemArray, $onFocus, $table, $field, $uid.
     * @param object $parentObject Parent object
     *
     * @return void
     */
    public function dbFileIcons_postProcessCompatibility(
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
        if (!isset($additionalParams['mode']) || $additionalParams['mode'] !== 'file') {
            return;
        }
        $this->loadJavaScript();

        $matches = [];
        if (!preg_match('/\[([a-zA-Z0-9_]+)\]\[([a-zA-Z0-9_]+)\]\[([a-zA-Z0-9_]+)\]/', $fName, $matches)) {
            return;
        }

        $uri = $matches['1'] . ';' . $matches['3'] . ';' . $parentObject->returnUrl;
        /** @var WizardService $wizardService */
        $wizardService = GeneralUtility::makeInstance('HDNET\\Focuspoint\\Service\\WizardService');
        $icons['R'][] = $wizardService->getWizardButton($uri, 'group-focuspoint');
    }

    /**
     * Load the JS
     */
    protected function loadJavaScript()
    {
        static $alreadyAdded = false;
        if (!$alreadyAdded) {
            $alreadyAdded = true;
            /** @var PageRenderer $pageRenderer */
            $pageRenderer = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Page\\PageRenderer');
            $pageRenderer->loadRequireJsModule('TYPO3/CMS/Focuspoint/GroupSelect');
        }
    }
}