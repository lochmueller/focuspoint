<?php
/**
 * Hook into the inline icons
 *
 * @package Focuspoint\Hooks
 * @author  Tim Lochmüller
 */

namespace HDNET\Focuspoint\Hooks;

use HDNET\Focuspoint\Service\WizardService;
use TYPO3\CMS\Backend\Form\Element\InlineElementHookInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hook into the inline icons
 *
 * @author Tim Lochmüller
 * @hook   TYPO3_CONF_VARS|SC_OPTIONS|t3lib/class.t3lib_tceforms_inline.php|tceformsInlineHook
 */
class InlineRecord implements InlineElementHookInterface
{

    /**
     * Initializes this hook object.
     *
     * @param object $parentObject
     *
     * @return void
     */
    public function init(&$parentObject)
    {
    }

    /**
     * Pre-processing to define which control items are enabled or disabled.
     *
     * @param string $parentUid The uid of the parent (embedding) record (uid or NEW...)
     * @param string $foreignTable The table (foreign_table) we create control-icons for
     * @param array $childRecord The current record of that foreign_table
     * @param array $childConfig TCA configuration of the current field of the child record
     * @param boolean $isVirtual Defines whether the current records is only virtually shown and not physically part of the parent record
     * @param array $enabledControls (reference) Associative array with the enabled control items
     *
     * @return void
     */
    public function renderForeignRecordHeaderControl_preProcess(
        $parentUid,
        $foreignTable,
        array $childRecord,
        array $childConfig,
        $isVirtual,
        array &$enabledControls
    ) {
    }

    /**
     * Post-processing to define which control items to show. Possibly own icons can be added here.
     *
     * @param string $parentUid The uid of the parent (embedding) record (uid or NEW...)
     * @param string $foreignTable The table (foreign_table) we create control-icons for
     * @param array $childRecord The current record of that foreign_table
     * @param array $childConfig TCA configuration of the current field of the child record
     * @param boolean $isVirtual Defines whether the current records is only virtually shown and not physically part of the parent record
     * @param array $controlItems (reference) Associative array with the currently available control items
     *
     * @return void
     */
    public function renderForeignRecordHeaderControl_postProcess(
        $parentUid,
        $foreignTable,
        array $childRecord,
        array $childConfig,
        $isVirtual,
        array &$controlItems
    ) {
        if ($foreignTable != 'sys_file_reference') {
            return;
        }

        if (!GeneralUtility::isFirstPartOfStr($childRecord['uid_local'], 'sys_file_')) {
            return;
        }

        $parts = BackendUtility::splitTable_Uid($childRecord['uid_local']);
        if (!isset($parts[1])) {
            return;
        }

        $table = $childRecord['tablenames'];
        $uid = $parentUid;

        $arguments = GeneralUtility::_GET();
        if ($this->isValidRecord($table, $uid) && isset($arguments['edit'])) {
            $returnUrl = [
                'edit' => $arguments['edit'],
                'returnUrl' => $arguments['returnUrl'],
            ];

            if (GeneralUtility::compat_version('7.0')) {
                $returnUrlGenerated = BackendUtility::getModuleUrl('record_edit', $returnUrl);
            } else {
                $returnUrlGenerated = 'alt_doc.php?' . ltrim(
                    GeneralUtility::implodeArrayForUrl(
                        '',
                        $returnUrl,
                        '',
                        true,
                        true
                    ),
                    '&'
                ) . BackendUtility::getUrlToken('editRecord');
            }

            $wizardArguments = [
                'P' => [
                    'referenceUid' => $childRecord['uid'],
                    'returnUrl' => $returnUrlGenerated
                ],
            ];
            $wizardUri = BackendUtility::getModuleUrl('focuspoint', $wizardArguments);
        } else {
            $wizardUri = 'javascript:alert(\'Please save the base record first, because open this wizard will not save the changes in the current form!\');';
        }
        /** @var WizardService $wizardService */
        $wizardService = GeneralUtility::makeInstance(WizardService::class);
        $this->arrayUnshiftAssoc($controlItems, 'focuspoint', $wizardService->getWizardButton($wizardUri));
    }

    /**
     * Check if the record is valid
     *
     * @param string $table
     * @param int $uid
     * @return bool
     */
    protected function isValidRecord($table, $uid)
    {
        return BackendUtility::getRecord($table, $uid) !== null;
    }

    /**
     * Add a element with the given key in front of the array
     *
     * @param $arr
     * @param string $key
     * @param string $val
     */
    protected function arrayUnshiftAssoc(&$arr, $key, $val)
    {
        $arr = array_reverse($arr, true);
        $arr[$key] = $val;
        $arr = array_reverse($arr, true);
    }
}
