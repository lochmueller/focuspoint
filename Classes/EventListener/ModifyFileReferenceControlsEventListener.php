<?php

declare(strict_types=1);

namespace HDNET\Focuspoint\EventListener;

use HDNET\Focuspoint\Service\WizardService;
use TYPO3\CMS\Backend\Form\Event\ModifyFileReferenceControlsEvent;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

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
        $wizardService = GeneralUtility::makeInstance(WizardService::class);
        $controls = $event->getControls();

        if ($this->isValidRecord($event->getForeignTable(), $uid)) {
            $arguments = $GLOBALS['TYPO3_REQUEST']->getQueryParams();
            // The arguments array is different in case this is called by an AJAX request
            // via an IRRE inside an IRRE...
            if (!isset($arguments['edit'])) {
                $url = parse_url(GeneralUtility::getIndpEnv('HTTP_REFERER'));
                parse_str($url['query'], $arguments);
            }
            $returnUrl = [
                'edit' => $arguments['edit'],
                'returnUrl' => $arguments['returnUrl'] ?? '',
            ];

            $wizardArguments = [
                'P' => [
                    'referenceUid' => $uid,
                    'returnUrl' => (string) $uriBuilder->buildUriFromRoute('record_edit', $returnUrl),
                ],
            ];
            $wizardUri = (string) $uriBuilder->buildUriFromRoute('focuspoint', $wizardArguments);
            $controls['focuspoint'] = $wizardService->getWizardButton($wizardUri, true);
        } else {
            // No inline JavaScript (javascript:alert) here, because it is blocked by the backend CSP
            $controls['focuspoint'] = $wizardService->getSaveFirstButton();
        }

        $event->setControls($controls);
    }

    /**
     * Check if the record is valid.
     */
    protected function isValidRecord(string $table, mixed $uid): bool
    {
        // Skip if the UID can't be interpreted as integer (e.g. 'NEW64ae7…')
        if (!MathUtility::canBeInterpretedAsInteger($uid)) {
            return false;
        }

        return null !== BackendUtility::getRecord($table, (int) $uid);
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
