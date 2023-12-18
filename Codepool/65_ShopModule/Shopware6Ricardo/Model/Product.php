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

MLFilesystem::gi()->loadClass('Shopware6_Model_Product');

class ML_Shopware6Ricardo_Model_Product extends ML_Shopware6_Model_Product
{

    /**
     * @param $blGros
     * @param $blFormated
     * @param string $sPriceKind
     * @param float $fPriceFactor
     * @param null $iPriceSignal
     * @param null $fTax
     * @return mixed
     * @throws Exception
     * @see \Shopware\Core\Content\Test\Product\SalesChannel\ProductPriceDefinitionBuilderTest
     */
    protected function getPrice($blGros, $blFormated, $sPriceKind = '', $fPriceFactor = 0.0, $iPriceSignal = null, $fTax = null)
    {
        $oProductEntity = $this->getCorrespondingProductEntity();
        $price = $oProductEntity->getPrice();
        if ($price === null) {
            $oProductEntity = $this->oProduct;
        }

        if ($oProductEntity->getTaxId() === null && $this->oProduct->getTaxId() !== null) {
            $oProductEntity->setTaxId($this->oProduct->getTaxId());
        }
        $context = MagnalisterController::getSalesChannelContextFactory()
            ->create($oProductEntity->getId(), Defaults::SALES_CHANNEL, [SalesChannelContextService::CURRENCY_ID => $this->getShopwareContext()->getCurrencyId()]);


        $CurrencyObject = MLShopware6Alias::getRepository('currency')
            ->search((new Criteria())->addFilter(new EqualsFilter('isoCode', MLModule::gi()->getConfig('currency'))), Context::createDefaultContext())->first();

        if (version_compare(MLSHOPWAREVERSION, '6.4.0.0', '>=')) {
            foreach ($oProductEntity->getPrice() as $value) {
                $fBrutPrice = $value->getGross();
            }
            $fBrutPrice = $fBrutPrice * $CurrencyObject->getFactor();

        } else {
            $fBrutPrice = MagnalisterController::getProductPriceDefinitionBuilder()->build($oProductEntity, $context)->getPrice()->getPrice();
        }

        $context->setTaxState(CartPrice::TAX_STATE_NET);

        if (version_compare(MLSHOPWAREVERSION, '6.4.0.0', '>=')) {

            foreach ($oProductEntity->getPrice() as $value) {
                $fNetPrice = $value->getNet();
            }
            $fNetPrice = $fNetPrice * $CurrencyObject->getFactor();

        } else {
            $fNetPrice = MagnalisterController::getProductPriceDefinitionBuilder()->build($oProductEntity, $context)->getPrice()->getPrice();
        }
//        Kint::dump(__FUNCTION__,$this->getShopwareContext()->getCurrencyId(), $fBrutPrice,$fNetPrice);
        $oPrice = MLPrice::factory();
        if ($fTax !== null) {
            $fBrutPrice = $oPrice->calcPercentages(null, $fNetPrice, $fTax);
        }

        if ($sPriceKind === 'percent') {
            $fBrutPrice = $oPrice->calcPercentages(null, $fBrutPrice, $fPriceFactor);
        } elseif ($sPriceKind === 'addition') {
            $fBrutPrice = $fBrutPrice + $fPriceFactor;
        }


        if ($iPriceSignal !== null) {
            //If price signal is single digit then just add price signal as last digit
            if (strlen((string)$iPriceSignal) == 1) {
                $fBrutPrice = (0.1 * (int)($fBrutPrice * 10)) + ((int)$iPriceSignal / 100);
            } else {
                $fBrutPrice = ((int)$fBrutPrice) + ((int)$iPriceSignal / 100);
            }
        }
        $fUsePrice = round($blGros ? $fBrutPrice : $fNetPrice, 2);
        //Check if last digit (second decimal) is 0 or 5. If not set 5 as default last digit
        $fUsePrice = (((int)(string)($fUsePrice * 100)) % 5) == 0 // cast to string because it seems php have float precision in background
            ? $fUsePrice : (((int)(string)($fUsePrice * 10)) / 10) + 0.05;
        // round again, to be sure that precision is 2
        $fUsePrice = round($fUsePrice, 2);
        return $fUsePrice;
    }

    protected function getProperties()
    {
        try {
            $sPropertiesHtml = ' ';
            if ($this->getCorrespondingProductEntity()->getPropertyIds() !== null) {
                $sRowClass = 'odd';
                $sPropertiesHtml .= '<ul class="magna_properties_list">';
                foreach ($this->getCorrespondingProductEntity()->getPropertyIds() as $value) {
                    $OptionPropertiesEntitesCereteria = new Criteria();
                    $OptionPropertiesEntites = MagnalisterController::getShopwareMyContainer()->get('property_group_option.repository')->search($OptionPropertiesEntitesCereteria->addFilter(new EqualsFilter('id', $value)), $this->getShopwareContext())->first();
                    $groupentity = MagnalisterController::getShopwareMyContainer()->get('property_group.repository');
                    $group = $groupentity->search(new Criteria(['id' => $OptionPropertiesEntites->getGroupId()]), $this->getShopwareContext())->first();
                    if ($OptionPropertiesEntites->getName() !== NULL) {
                        $sPropertiesHtml .= '<li class="magna_property_item ' . $sRowClass . '">'
                            . '<span class="magna_property_name">' . $group->getName()
                            . '</span>: '
                            . '<span  class="magna_property_value">' . $OptionPropertiesEntites->getName()
                            . '</span>'
                            . '</li>';
                        $sRowClass = $sRowClass === 'odd' ? 'even' : 'odd';
                    } else {
                        $DefaultLangCriteria2 = new Criteria();
                        $DefaultLangOptionPropertiesEntites = MagnalisterController::getShopwareMyContainer()->get('property_group_option.repository')->search($DefaultLangCriteria2->addFilter(new EqualsFilter('id', $value)), Context::createDefaultContext())->first();

                        $DefaultGroupentity = MagnalisterController::getShopwareMyContainer()->get('property_group.repository');
                        if ($group->getName() == null) {
                            $DefaultGroup = $DefaultGroupentity->search(new Criteria(['id' => $DefaultLangOptionPropertiesEntites->getGroupId()]), Context::createDefaultContext())->first();
                        } else {
                            $DefaultGroup = $DefaultGroupentity->search(new Criteria(['id' => $DefaultLangOptionPropertiesEntites->getGroupId()]), $this->getShopwareContext())->first();
                        }

                        $sPropertiesHtml .= '<li class="magna_property_item ' . $sRowClass . '">'
                            . '<span class="magna_property_name">' . $DefaultGroup->getName()
                            . '</span>: '
                            . '<span  class="magna_property_value">' . $DefaultLangOptionPropertiesEntites->getName()
                            . '</span>'
                            . '</li>';
                        $sRowClass = $sRowClass === 'odd' ? 'even' : 'odd';
                    }
                }
                $sPropertiesHtml .= '</ul>';
            }

            return $sPropertiesHtml;
        } catch (Exception $oEx) {
            return '';
        }
    }

}
