<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Url\Communication\Grid;

use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class UrlGrid extends AbstractTable
{

    const ID_URL = 'id_url';
    const FK_LOCALE = 'fk_locale';
    const URL = 'url';
    const FK_RESOURCE_REDIRECT = 'fk_resource_redirect';
    const FK_RESOURCE_PRODUCT = 'fk_resource_product';
    const FK_RESOURCE_CATEGORYNODE = 'fk_resource_categoryname';
    const FK_RESOURCE_PAGE = 'fk_resource_page';

    /**
     * @return void
     */
    protected function configure(TableConfiguration $config)
    {
        // @todo: Implement configure() method.
    }

    /**
     * @return void
     */
    protected function prepareData(TableConfiguration $config)
    {
        // @todo: Implement prepareData() method.
    }

    public function definePlugins()
    {
        return [
            $this->createDefaultRowRenderer(),
            $this->createPagination(),

            $this->createDefaultColumn()
                ->setName(self::ID_URL)
                ->filterable()
                ->sortable(),

            $this->createDefaultColumn()
                ->setName(self::FK_LOCALE)
                ->filterable()
                ->sortable(),

            $this->createDefaultColumn()
                ->setName(self::URL)
                ->filterable()
                ->sortable(),

            $this->createDefaultColumn()
                ->setName(self::FK_RESOURCE_REDIRECT)
                ->filterable()
                ->sortable(),

            $this->createDefaultColumn()
                ->setName(self::FK_RESOURCE_PRODUCT)
                ->filterable()
                ->sortable(),

            $this->createDefaultColumn()
                ->setName(self::FK_RESOURCE_CATEGORYNODE)
                ->filterable()
                ->sortable(),

            $this->createDefaultColumn()
                ->setName(self::FK_RESOURCE_PAGE)
                ->filterable()
                ->sortable(),

        ];
    }

}