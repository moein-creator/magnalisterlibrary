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
 * (c) 2010 - 2020 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */
MLFilesystem::gi()->loadClass('Core_Update_Abstract');
/**
 * to set default value in global configuration
 */
include_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Helper' . DIRECTORY_SEPARATOR . 'MLShopwareCloudAlias.php';
include_once(M_DIR_LIBRARY . 'request/shopware/ShopwareNumberRange.php');

use library\request\shopware\ShopwareNumberRange;


class ML_ShopwareCloud_Update_SetOrderNumberRangeStates extends ML_Core_Update_Abstract
{

    protected $sShopId;

    /** @var library\request\shopware\ShopwareNumberRange $oEntityNumberRequest */
    protected $oEntityNumberRequest;

    public function execute()
    {
        $this->sShopId = MLShopwareCloudAlias::getShopHelper()->getShopId();
        $this->oEntityNumberRequest = new ShopwareNumberRange($this->sShopId);
        $this->AddingOrderNumberRangeStates();
        return parent::execute();
    }

    public function AddingOrderNumberRangeStates()
    {
        $type = 'order';
        $numberRangesUlr = '';
        $customerNumberRangeStateUlr = '';
        $oNumberRangeTypes = $this->oEntityNumberRequest->getShopwareNumberRangeTypes();
        foreach ($oNumberRangeTypes->getData() as $oNumberRangeType) {
            if ($oNumberRangeType->getAttributes()->getTechnicalName() == $type) {
                $numberRangesUlr = $oNumberRangeType->getRelationships()->getNumberRanges()->getLinks()->getRelated();
                break;
            }
        }
        if ($numberRangesUlr !== '') {
            $oCustomerNumberRanges = $this->oEntityNumberRequest->getShopwareNumberRanges($numberRangesUlr);
            $sOrderNumber = $oCustomerNumberRanges->getData()[0]->getAttributes()->getStart();
            if (is_array($oCustomerNumberRanges->getData())) {
                $customerNumberRangeStateUlr = $oCustomerNumberRanges->getData()[0]->getRelationships()->getState()->getLinks()->getRelated();
            }
            // when we have first order in the shop the order number is missing
            if ($customerNumberRangeStateUlr !== '') {
                $oCustomerNumberRangeTypes = $this->oEntityNumberRequest->getShopwareNumberRangeStates($customerNumberRangeStateUlr);
                if (!is_array($oCustomerNumberRangeTypes->getData())) {
                    $this->oEntityNumberRequest->createShopwareNumberRangeStates(array('numberRangeId' => $oCustomerNumberRanges->getData()[0]->getId(), 'lastValue' => (int)$sOrderNumber));
                }
            }
        }
    }
}

