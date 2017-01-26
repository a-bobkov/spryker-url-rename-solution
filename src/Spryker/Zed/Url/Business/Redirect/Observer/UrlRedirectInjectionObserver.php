<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Redirect\Observer;

use Generated\Shared\Transfer\UrlRedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Url\Business\Exception\RedirectLoopException;
use Spryker\Zed\Url\Business\Redirect\UrlRedirectActivatorInterface;
use Spryker\Zed\Url\Business\Url\AbstractUrlCreatorObserver;
use Spryker\Zed\Url\Persistence\UrlQueryContainerInterface;

class UrlRedirectInjectionObserver extends AbstractUrlCreatorObserver
{

    /**
     * @var \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface
     */
    protected $urlQueryContainer;

    /**
     * @var \Spryker\Zed\Url\Business\Redirect\UrlRedirectActivatorInterface
     */
    protected $urlRedirectActivator;

    /**
     * @param \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface $urlQueryContainer
     * @param \Spryker\Zed\Url\Business\Redirect\UrlRedirectActivatorInterface $urlRedirectActivator
     */
    public function __construct(UrlQueryContainerInterface $urlQueryContainer, UrlRedirectActivatorInterface $urlRedirectActivator)
    {
        $this->urlQueryContainer = $urlQueryContainer;
        $this->urlRedirectActivator = $urlRedirectActivator;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function update(UrlTransfer $urlTransfer)
    {
        $this->urlQueryContainer->getConnection()->beginTransaction();

        $this->handleRedirectInjection($urlTransfer);

        $this->urlQueryContainer->getConnection()->commit();
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @throws \Spryker\Zed\Url\Business\Exception\RedirectLoopException
     *
     * @return void
     */
    protected function handleRedirectInjection(UrlTransfer $urlTransfer)
    {
        $newUrlRedirectEntity = $this->urlQueryContainer
            ->queryUrlRedirectByIdUrl($urlTransfer->getIdUrl())
            ->findOne();

        if (!$newUrlRedirectEntity) {
            return;
        }

        $finalTargetUrlRedirectEntity = $this->findUrlRedirectEntityBySourceUrl($newUrlRedirectEntity->getToUrl());

        if (!$finalTargetUrlRedirectEntity) {
            return;
        }

        if ($finalTargetUrlRedirectEntity->getToUrl() === $urlTransfer->getUrl()) {
            throw new RedirectLoopException(sprintf(
                'Redirecting "%s" to "%s" resolved in a URL redirect loop.',
                $urlTransfer->getUrl(),
                $newUrlRedirectEntity->getToUrl()
            ));
        }

        $newUrlRedirectEntity
            ->setToUrl($finalTargetUrlRedirectEntity->getToUrl())
            ->save();

        $this->activateUrlRedirect($newUrlRedirectEntity->getIdUrlRedirect());
    }

    /**
     * @param string $sourceUrl
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirect
     */
    protected function findUrlRedirectEntityBySourceUrl($sourceUrl)
    {
        return $this->urlQueryContainer
            ->queryUrlRedirectBySourceUrl($sourceUrl)
            ->findOne();
    }

    /**
     * @param int $idUrlRedirect
     *
     * @return void
     */
    protected function activateUrlRedirect($idUrlRedirect)
    {
        $urlRedirectTransfer = new UrlRedirectTransfer();
        $urlRedirectTransfer->setIdUrlRedirect($idUrlRedirect);

        $this->urlRedirectActivator->activateUrlRedirect($urlRedirectTransfer);
    }

}