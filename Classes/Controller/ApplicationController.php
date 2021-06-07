<?php

namespace Datamints\DatamintsLocallangBuilder\Controller;

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use Datamints\DatamintsLocallangBuilder\Mvc\View\ExtensionJsonView;
use Datamints\DatamintsLocallangBuilder\Mvc\View\JsonView;

/**
 * This file is part of the "locallang-xlf" Extension for TYPO3 CMS.
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 * (c) 2021 Mark Weisgerber <mark.weisgerber@outlook.de>
 * ExtensionController
 */
class ApplicationController extends AbstractController
{
    use \Datamints\DatamintsLocallangBuilder\Domain\Repository\Traits\ExtensionRepositoryTrait;
    use \Datamints\DatamintsLocallangBuilder\Domain\Repository\Traits\LocallangRepositoryTrait;
    use \Datamints\DatamintsLocallangBuilder\Domain\Repository\Traits\TranslationRepositoryTrait;
    use \Datamints\DatamintsLocallangBuilder\Domain\Repository\Traits\TranslationValueRepositoryTrait;
    use \Datamints\DatamintsLocallangBuilder\Services\Traits\CachesServiceTrait;
    use \Datamints\DatamintsLocallangBuilder\Services\Traits\ProviderServiceTrait;

    /**
     * main Action
     *
     * Entry-Point for some basic configuration to transfer to vue-app
     *
     */
    public function mainAction()
    {
        $this->view->assignMultiple([
            'config' => \json_encode( // Add everything config related stuff to give vue access to it
                [
                    'provider' => $this->providerService->getConfiguredProvider(),
                ]
            ),
        ]);
    }

    public function initializeClearAction()
    {
        $this->defaultViewObjectName = JsonView::class;
    }

    /**
     * clear Action
     *
     * clears all related extension related db-tables when requested by e.g. reimport-action
     *
     */
    public function clearAction()
    {
        // Cleaning up...
        $this->cachesService->clearOwnCache();
        $this->extensionRepository->removeAllQuick();
        $this->locallangRepository->removeAllQuick();
        $this->translationRepository->removeAllQuick();
        $this->translationValueRepository->removeAllQuick();

        \Datamints\DatamintsLocallangBuilder\Utility\DatabaseUtility::persistAll();

        $this->view->assign('message', 'All database-tables have been reset to zero.');
    }
}