<?php

declare(strict_types=1);

namespace HDNET\Focuspoint\EventListener;

use HDNET\Focuspoint\Service\WizardService;
use TYPO3\CMS\Backend\Form\Event\ModifyFileReferenceControlsEvent;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ModifyFileReferenceControlsEventListener
{
    public function __invoke(ModifyFileReferenceControlsEvent $event): void
    {
        if ('sys_file_reference' !== $event->getForeignTable()) {
            return;
        }

        $record = $event->getRecord();
        $uid = $record['uid'] ?? 0;

        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);

        if ($this->isValidRecord($event->getForeignTable(), $uid)) {
            $arguments = GeneralUtility::_GET();
            // The arguments array is different in case this is called by an AJAX request
            // via an IRRE inside an IRRE...
            if (!isset($arguments['edit'])) {
                $url = parse_url(GeneralUtility::getIndpEnv('HTTP_REFERER'));
                parse_str($url['query'], $arguments);
            }
            $returnUrl = [
                'edit' => $arguments['edit'],
                'returnUrl' => $arguments['returnUrl'],
            ];

            $wizardArguments = [
                'P' => [
                    'referenceUid' => $uid,
                    'returnUrl' => (string) $uriBuilder->buildUriFromRoute('record_edit', $returnUrl),
                ],
            ];
            $wizardUri = (string) $uriBuilder->buildUriFromRoute('focuspoint', $wizardArguments);
        } else {
            $wizardUri = 'javascript:alert(\'Please save the base record first, because open this wizard will not save the changes in the current form!\');';
        }

        $wizardService = GeneralUtility::makeInstance(WizardService::class);

        $controls = $event->getControls();
        $controls['focuspoint'] = $wizardService->getWizardButton((string) $wizardUri, true);
        $event->setControls($controls);
    }

    /**
     * Check if the record is valid.
     */
    protected function isValidRecord(string $table, int $uid): bool
    {
        return null !== BackendUtility::getRecord($table, $uid);
    }

    /**
     * Add a element with the given key in front of the array.
     */
    protected function arrayUnshiftAssoc(array &$arr, string $key, string $val): void
    {
        $arr = array_reverse($arr, true);
        $arr[$key] = $val;
        $arr = array_reverse($arr, true);
    }
}
