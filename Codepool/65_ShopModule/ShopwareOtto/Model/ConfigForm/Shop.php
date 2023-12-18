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

MLFilesystem::gi()->loadClass('Shopware_Model_ConfigForm_Shop');

class ML_ShopwareOtto_Model_ConfigForm_Shop extends ML_Shopware_Model_ConfigForm_Shop {

    public function getCustomerGroupValues($blNotLoggedIn = false) {
        $aGroupsName = array();
        $customerGroups = Shopware()->Models()->getRepository('Shopware\Models\Customer\Customer')->getCustomerGroupsQuery()->getArrayResult();
        foreach ($customerGroups as $aRow) {
            $aGroupsName[$aRow['id']] = $aRow['name'];
        }
        $oQueryBuilder = Shopware()->Models()->createQueryBuilder();
        if ($blNotLoggedIn) {
            $aRes = $oQueryBuilder
                ->select('snippet.value')
                ->from('Shopware\Models\Snippet\Snippet', 'snippet')
                ->where("snippet.name = 'RegisterLabelNoAccount' AND snippet.namespace = 'frontend/register/personal_fieldset' And snippet.localeId = " . Shopware()->Shop()->getLocale()->getId())->getQuery()->getArrayResult();
            if (!empty($aRes)) {
                $aGroupsName['-'] = $aRes[0]['value'];
            } else {
                $aGroupsName['-'] = MLI18n::gi()->Shopware_Orderimport_CustomerGroup_Notloggedin;
            }
        }
        return $aGroupsName;
    }

    public function getOrderStatusValues() {
        $oQueryBuilder = Shopware()->Models()->createQueryBuilder();
        $aRes = $oQueryBuilder
            ->select('snippet.name,snippet.value')
            ->from('Shopware\Models\Snippet\Snippet', 'snippet')
            ->where("snippet.namespace = 'backend/static/order_status' And snippet.localeId = " . Shopware()->Shop()->getLocale()->getId())->getQuery()->getArrayResult();
        $aStatusI18N = array();
        foreach ($aRes as $aRow) {
            $aStatusI18N[$aRow['name']] = $aRow['value'];
        }
        $orderStates = MLDatabase::getDbInstance()->fetchArray("select * from  `s_core_states` where `group` = 'state' order By `position`");
        $aOrderStatesName = array();
        if(isset($orderStates[0]['name'])){
            foreach ($orderStates as $aRow) {
                $sI18NIndex = $aRow['name'];
                $aOrderStatesName[$aRow['id']] = isset($aStatusI18N[$sI18NIndex]) ? $aStatusI18N[$sI18NIndex] : $aRow['description'];
            }
        } else {
            foreach ($orderStates as $aRow) {
                $sI18NIndex = strtolower(str_replace(array(' / ', ' '), '_', $aRow['description']));
                $sI18NIndex = strtolower(str_replace(
                    array(
                        'in_work',
                        'canceled',
                        'clarification_needed',
                        'partial_delivered',
                        'fully_completed',
                        'delivered_completely'
                    ), array(
                    'in_process',
                    'cancelled',
                    'clarification_required',
                    'partially_delivered',
                    'completed',
                    'completely_delivered'
                ), $sI18NIndex));

                $aOrderStatesName[$aRow['id']] = isset($aStatusI18N[$sI18NIndex]) ? $aStatusI18N[$sI18NIndex] : $aRow['description'];
            }
        }
        //unsets the order status "Cancelled" 27.10.2020
        unset($aOrderStatesName[-1]);

        return $aOrderStatesName;
    }
}
