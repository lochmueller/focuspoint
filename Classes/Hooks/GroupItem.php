<?php

/**
 * Hook into group items.
 *
 * NOTE: In 7.0 the interface is changed, becuse the $parentObject do not use a type hint anymore
 * That is the reason of the DIRTY abstract class in front of the concrete implementation
 */

namespace HDNET\Focuspoint\Hooks;

use HDNET\Focuspoint\Service\WizardService;
use TYPO3\CMS\Backend\Form\DatabaseFileIconsHookInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hook into group items.
 *
 * @hook TYPO3_CONF_VARS|SC_OPTIONS|t3lib/class.t3lib_tceforms.php|dbFileIcons
 */
class GroupItem implements DatabaseFileIconsHookInterface
{
    /**
     * Modifies the parameters for selector box form-field for the db/file/select elements (multiple).
     *
     * @param array  $params           An array of additional parameters, eg: "size", "info", "headers" (array with "selector" and "items"), "noBrowser", "thumbnails
     * @param string $selector         alternative selector box
     * @param string $thumbnails       Thumbnail view of images. Only filled if there are images only. This images will be shown under the selectorbox.
     * @param array  $icons            defined icons next to the selector box
     * @param string $rightbox         Thumbnail view of images. Only filled if there are other types as images. This images will be shown right next to the selectorbox.
     * @param string $fName            Form element name
     * @param array  $uidList          The array of item-uids. Have a look at \TYPO3\CMS\Backend\Form\FormEngine::dbFileIcons parameter "$itemArray
     * @param array  $additionalParams Array with additional parameters which are be available at method call. Includes $mode, $allowed, $itemArray, $onFocus, $table, $field, $uid.
     * @param object $parentObject     Parent object
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
        if (!isset($additionalParams['mode']) || 'file' !== $additionalParams['mode']) {
            return;
        }
        $this->loadJavaScript();

        $matches = [];
        if (!\preg_match('/\[([a-zA-Z0-9_]+)\]\[([a-zA-Z0-9_]+)\]\[([a-zA-Z0-9_]+)\]/', $fName, $matches)) {
            return;
        }

        $wizardArguments = [
            'P' => [
                'table' => $matches['1'],
                'field' => $matches['3'],
                'returnUrl' => isset($parentObject->returnUrl) ? $parentObject->returnUrl : GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'),
            ],
        ];
        $wizardUri = BackendUtility::getModuleUrl('focuspoint', $wizardArguments);

        /** @var WizardService $wizardService */
        $wizardService = GeneralUtility::makeInstance(WizardService::class);
        $icons['R'][] = $wizardService->getWizardButton($wizardUri, 'group-focuspoint');
    }

    /**
     * Load the JS.
     */
    protected function loadJavaScript()
    {
        static $alreadyAdded = false;
        if (!$alreadyAdded) {
            $alreadyAdded = true;
            /** @var PageRenderer $pageRenderer */
            $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
            $pageRenderer->loadRequireJsModule('TYPO3/CMS/Focuspoint/GroupSelect');
        }
    }
}
