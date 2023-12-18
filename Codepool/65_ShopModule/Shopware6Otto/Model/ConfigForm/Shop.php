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
use Shopware\Core\Checkout\Cart\Price\Struct\CartPrice;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Redgecko\Magnalister\Controller\MagnalisterController;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Api\Context\SystemSource;
use Shopware\Core\Defaults;
use Shopware\Core\Checkout\Order\OrderStates;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionStates;
use Shopware\Core\System\Currency\CurrencyFormatter;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextService;

MLFilesystem::gi()->loadClass('Shopware6_Model_ConfigForm_Shop');

class ML_Shopware6Otto_Model_ConfigForm_Shop extends ML_Shopware6_Model_ConfigForm_Shop {

    /**
     * Done
     * @param type $blNotLoggedIn
     * @return type
     * @todo : the user.repository should be checked
     */
    public function getCustomerGroupValues($blNotLoggedIn = false) {
        $aGroupsName = array();
        $User = MagnalisterController::getShopwareMyContainer()->get('user.repository')->search(new Criteria(), Context::createDefaultContext())->first();
        $criteria = new Criteria();
        $lang = MagnalisterController::getShopwareMyContainer()->get('language.repository')->search($criteria->addFilter(new EqualsFilter('locale.id', $User->getLocaleId())), Context::createDefaultContext())->first();
        $context = new Context(
            new SystemSource(), [], Defaults::CURRENCY, [$lang->getId()], Defaults::LIVE_VERSION
        );
        $customerGroups = MagnalisterController::getShopwareMyContainer()->get('customer_group.repository')->search(new Criteria(), $context)->getEntities();
        foreach ($customerGroups as $aRow) {
            $aGroupsName[$aRow->getId()] = $aRow->getName();
        }

        return $aGroupsName;
    }

    /**
     * @return type
     * @todo Done
     */
    public function getOrderStatusValues() {
        $lang = MLShopware6Alias::getRepository('language')->search((new Criteria())->addFilter(new EqualsFilter('locale.id', MagnalisterController::getShopwareLocaleId())), Context::createDefaultContext())->first();
        $context = new Context(
            new SystemSource(), [], Defaults::CURRENCY, [$lang->getId()], Defaults::LIVE_VERSION
        );
        $orderStatus = MagnalisterController::getShopwareStateMachineRegistry()->getStateMachine(OrderStates::STATE_MACHINE, $context)->getTransitions();

        foreach ($orderStatus->getElements() as $aRow) {
            $aOrderStatesName[$aRow->getFromStateMachineState()->getTechnicalName()] = $aRow->getFromStateMachineState()->getName();
        }
                
        return $aOrderStatesName;
    }

}
