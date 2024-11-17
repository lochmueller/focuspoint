<?php

declare(strict_types=1);

namespace HDNET\Focuspoint\Service\WizardHandler;

use HDNET\Focuspoint\Domain\Repository\FileStandaloneRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Group extends AbstractWizardHandler
{
    /**
     * Check if the handler can handle the current request.
     */
    public function canHandle(): bool
    {
        return null !== $this->getRelativeFilePath();
    }

    /**
     * Return the current point.
     */
    public function getCurrentPoint(): array
    {
        $fileStandaloneRepository = GeneralUtility::makeInstance(FileStandaloneRepository::class);
        $row = $fileStandaloneRepository->findOneByRelativeFilePath($this->getRelativeFilePath());
        if (!isset($row['focus_point_x'])) {
            return [0, 0];
        }

        return $this->cleanupPosition([
            $row['focus_point_x'],
            $row['focus_point_y'],
        ]);
    }

    /**
     * Set the point (between -100 and 100).
     */
    public function setCurrentPoint(int $x, int $y): void
    {
        /** @var FileStandaloneRepository $fileStandaloneRepository */
        $fileStandaloneRepository = GeneralUtility::makeInstance(FileStandaloneRepository::class);
        $row = $fileStandaloneRepository->findOneByRelativeFilePath($this->getRelativeFilePath());

        $values = [
            'focus_point_x' => $x,
            'focus_point_y' => $y,
            'relative_file_path' => $this->getRelativeFilePath(),
        ];

        if (isset($row['uid'])) {
            $fileStandaloneRepository->update(
                (int) $row['uid'],
                $values
            );
        } else {
            $fileStandaloneRepository->insert(
                $values
            );
        }
    }

    /**
     * Get the public URL for the current handler.
     */
    public function getPublicUrl(): string
    {
        return $this->displayableImageUrl($this->getRelativeFilePath());
    }

    /**
     * get the arguments for same request call.
     */
    public function getArguments(): array
    {
        $parameter = $GLOBALS['TYPO3_REQUEST']->getQueryParams();
        $p = $parameter['P'];

        return [
            'P' => [
                'table' => $p['table'],
                'field' => $p['field'],
                'file' => $p['file'],
            ],
        ];
    }

    /**
     * get the file name.
     */
    protected function getRelativeFilePath(): ?string
    {
        $parameter = $GLOBALS['TYPO3_REQUEST']->getQueryParams();
        if (!isset($parameter['P'])) {
            return null;
        }
        $p = $parameter['P'];
        if (!isset($p['table']) || !isset($p['field']) || !isset($p['file'])) {
            return null;
        }
        if (!isset($GLOBALS['TCA'][$p['table']])) {
            return null;
        }
        $tableTca = $GLOBALS['TCA'][$p['table']];
        if (!isset($tableTca['columns'][$p['field']])) {
            return null;
        }
        $fieldTca = $tableTca['columns'][$p['field']];

        $uploadFolder = $fieldTca['config']['uploadfolder'] ?? '';
        $baseFolder = '';
        if ('' !== trim($uploadFolder, '/')) {
            $baseFolder = rtrim($uploadFolder, '/') . '/';
        }

        $filePath = $baseFolder . $p['file'];
        if (!is_file(GeneralUtility::getFileAbsFileName($filePath))) {
            return null;
        }

        return $filePath;
    }
}
