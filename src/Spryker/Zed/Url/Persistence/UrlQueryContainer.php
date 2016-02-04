<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Url\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Url\Persistence\Map\SpyUrlRedirectTableMap;
use Orm\Zed\Url\Persistence\SpyUrlRedirectQuery;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Propel\Runtime\ActiveQuery\Criteria;

/**
 * @method UrlPersistenceFactory getFactory()
 */
class UrlQueryContainer extends AbstractQueryContainer implements UrlQueryContainerInterface
{

    const TO_URL = 'toUrl';
    const STATUS = 'status';

    /**
     * @param string $url
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrl
     */
    public function queryUrl($url)
    {
        $query = $this->getFactory()->createUrlQuery();
        $query->filterByUrl($url);

        return $query;
    }

    public function queryTranslationById($id)
    {
        $query = $this->getFactory()->createUrlQuery();
        $query->filterByIdUrl($id);

        return $query;
    }

    /**
     * @param int $id
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlById($id)
    {
        $query = $this->getFactory()->createUrlQuery();
        $query->filterByIdUrl($id);

        return $query;
    }

    /**
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrls()
    {
        $query = $this->getFactory()->createUrlQuery();

        return $query;
    }

    /**
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlsWithRedirect()
    {
        $query = $this->getFactory()->createUrlQuery();
        $query->innerJoinSpyUrlRedirect()
            ->withColumn(SpyUrlRedirectTableMap::COL_TO_URL, self::TO_URL)
            ->withColumn(SpyUrlRedirectTableMap::COL_STATUS, self::STATUS);

        return $query;
    }

    /**
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirectQuery
     */
    public function queryRedirects()
    {
        $query = $this->getFactory()->createUrlRedirectQuery();

        return $query;
    }

    /**
     * @param int $idUrlRedirect
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirectQuery
     */
    public function queryRedirectById($idUrlRedirect)
    {
        $query = $this->getFactory()->createUrlRedirectQuery();
        $query->filterByIdUrlRedirect($idUrlRedirect);

        return $query;
    }

    /**
     * @return self|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinLocales()
    {
        return $this->queryUrls()
            ->leftJoinSpyLocale(null, Criteria::LEFT_JOIN)
            ->withColumn('locale_name');
    }

    /**
     * @param int $id
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlByIdWithRedirect($id)
    {
        $query = $this->getFactory()->createUrlQuery();
        $query->leftJoinSpyUrlRedirect()
            ->withColumn(SpyUrlRedirectTableMap::COL_TO_URL, self::TO_URL)
            ->withColumn(SpyUrlRedirectTableMap::COL_STATUS, self::STATUS)
            ->filterByIdUrl($id);

        return $query;
    }

    /**
     * @param int $idCategoryNode
     * @param int $idLocale
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryResourceUrlByCategoryNodeAndLocaleId($idCategoryNode, $idLocale)
    {
        $query = $this->getFactory()->createUrlQuery();
        $query->filterByFkResourceCategorynode($idCategoryNode);
        $query->filterByFkLocale($idLocale);

        return $query;
    }

}
