<?php

declare(strict_types=1);

/**
 * Extends the file list.
 */

namespace HDNET\Focuspoint\Hooks;

use HDNET\Autoloader\Annotation\Hook;
use HDNET\Focuspoint\Service\WizardService;
use HDNET\Focuspoint\Utility\FileUtility;
use HDNET\Focuspoint\Utility\ImageUtility;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Filelist\FileListEditIconHookInterface;

/**
 * Extends the file list.
 *
 * @Hook("TYPO3_CONF_VARS|SC_OPTIONS|fileList|editIconsHook")
 */
class FileList implements FileListEditIconHookInterface
{
    /**
     * Modifies edit icon array.
     *
     * @param array                        $cells        Array of edit icons
     * @param \TYPO3\CMS\Filelist\FileList $parentObject Parent object
     */
    public function manipulateEditIcons(&$cells, &$parentObject): void
    {
        /** @var WizardService $wizardService */
        $wizardService = GeneralUtility::makeInstance(WizardService::class);

        try {
            $metaUid = $this->getFileMetaUidByCells($cells);
            $file = FileUtility::getFileByMetaData($metaUid);
        } catch (\Exception $ex) {
            $cells['focuspoint'] = $wizardService->getWizardButton();

            return;
        }

        if (!ImageUtility::isValidFileExtension($file->getExtension())) {
            $cells['focuspoint'] = $wizardService->getWizardButton();

            return;
        }

        $wizardArguments = [
            'P' => [
                'metaUid' => $metaUid,
                'returnUrl' => GeneralUtility::getIndpEnv('REQUEST_URI'),
            ],
        ];
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $wizardUri = (string) $uriBuilder->buildUriFromRoute('focuspoint', $wizardArguments);
        $cells['focuspoint'] = $wizardService->getWizardButton($wizardUri);
    }

    /**
     * Get the file object of the given cell information.
     *
     * @param array $cells
     *
     * @throws \Exception
     */
    protected function getFileMetaUidByCells($cells): int
    {
        if ($cells['__fileOrFolderObject'] instanceof File) {
            if (\is_callable([$cells['__fileOrFolderObject'], 'getMetaData'])) {
                return $cells['__fileOrFolderObject']->getMetaData()->get()['uid'];
            }
        }
        if ($cells['__fileOrFolderObject'] instanceof FileInterface) {
            $metaData = $cells['__fileOrFolderObject']->_getMetaData();
        }
        if (!isset($metaData['uid'])) {
            throw new \Exception('No meta data found', 1475144024);
        }

        return (int) $metaData['uid'];
    }
}
