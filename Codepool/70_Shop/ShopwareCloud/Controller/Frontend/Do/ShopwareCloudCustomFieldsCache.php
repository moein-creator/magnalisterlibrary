<?php
/*
 * 888888ba                 dP  .88888.                    dP
 * 88    `8b                88 d8'   `88                   88
 * 88aaaa8P' .d8888b. .d888b88 88        .d8888b. .d8888b. 88  .dP  .d8888b.
 * 88   `8b. 88ooood8 88'  `88 88   YP88 88ooood8 88'  `"" 88888"   88'  `88
 * 88     88 88.  ... 88.  .88 Y8.   .88 88.  ... 88.  ... 88  `8b. 88.  .88
 * dP     dP `88888P' `88888P8  `88888'  `88888P' `88888P' dP   `YP `88888P'
 *
 *                          m a g n a l i s t e r
 *                                      boost your Online-Shop
 *
 * -----------------------------------------------------------------------------
 * (c) 2010 - 2022 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */
include_once(M_DIR_LIBRARY.'CronLogger.php');
include_once(M_DIR_LIBRARY.'MailService.php');
include_once(M_DIR_LIBRARY.'request/shopware/ShopwareCustomField.php');

use library\request\shopware\ShopwareCustomField;
use library\CronLogger;

MLFilesystem::gi()->loadClass('ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache');

class ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudCustomFieldsCache extends ML_ShopwareCloud_Controller_Frontend_Do_ShopwareCloudAbstractCache {

    protected $sType = 'CustomFields';

    public function __construct() {
        parent::__construct();
        $this->shopwareEntityRequest = new ShopwareCustomField($this->sShopId);
    }

    protected function getTotalCountOfEntities() {
        $preparedFilters = $this->getUpdatedAtTimeFilter();
        $filters = [
            'page' => 1,
            'limit' =>  1,
            'total-count-mode' => 1,
        ];
        $preparedFilters = array_merge($filters, $preparedFilters);
        $entities = $this->shopwareEntityRequest->getShopwareCustomFields('/api/search/custom-field', 'POST', $preparedFilters);
        return $entities->getMeta()->getTotal();
    }

    protected function updateEntity($data) {
        $aCustomFieldsRelations = [];
        $sUrl = '/api/custom-field-set/' . $data->getAttributes()->getCustomFieldSetId() . '/relations';
        $oRelations = $this->shopwareEntityRequest->getShopwareCustomSetRelations($sUrl, 'GET');

        foreach ($oRelations->getData() as $oRelation) {
            $aCustomFieldsRelations[] = $oRelation->getAttributes()->getEntityName();
        }

        MLDatabase::factory('ShopwareCloudCustomFields')
            ->set('ShopwareCustomFieldID', $data->getId())
            ->set('ShopwareCustomFieldSetID', $data->getAttributes()->getCustomFieldSetId())
            ->set('ShopwareCustomFieldName', $data->getAttributes()->getName())
            ->set('ShopwarePosition', $data->getAttributes()->getConfig()->getCustomFieldPosition())
            ->set('ShopwareCustomFieldType', $data->getAttributes()->getType())
            ->set('ShopwareCustomFieldLabel', $data->getAttributes()->getConfig()->getLabel())
            ->set('ShopwareCustomFieldRelation', json_encode($aCustomFieldsRelations))
            ->set('ShopwareCustomFieldConfigOptions', json_encode($data->getAttributes()->getConfig()->getOptions()))
            ->save();
    }

    protected function getEntities($preparedFilters) {
        return $this->shopwareEntityRequest->getShopwareCustomFields('/api/search/custom-field', 'POST', $preparedFilters);
    }
}
