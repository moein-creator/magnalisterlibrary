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
 * (c) 2010 - 2023 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

class ML_ShopwareCloud_Helper_Model_Shop
{

    /**
     * Returns shop id from calculated query string.
     * Shop id is a string, and presents customer's
     * domain name.
     *
     * @return string e.g. my-store.shopwarecloud.com
     */
    public function getCustomerName() {
        return CustomerHelper::gi()->getCustomer($this->getShopId())['CustomerName'];
    }

    public  function getShopId() {
        return isset($_GET['shop-id']) ? $_GET['shop-id'] : null;
    }

    /**
     * Returns order statuses for getOrderStatusValues and getPaymentStatusValues
     * methods. Filters out statuses from state-machine-state api call by stateMachineId
     *
     * @param $request 
     * @param $apiHelper
     * @param $stateType
     * @return $oOrderStatuses
     */
    public function getOrderStatuses($request, $apiHelper, $stateType, $technicalName = '', $associations = array()) {
        $filters = [
            'stateMachineId' => [
                'type' => 'equals',
                'values' => $this->getStateMachineId($request, $apiHelper, $stateType)
            ],
        ];
        if (!empty($technicalName)) {
            $filters['technicalName'] = [
                'type' => 'equals',
                'values' => $technicalName
            ];
        }
        /** @var APIHelper $apiHelper */
        $requestBody = $apiHelper->prepareFilters($filters, 'POST');
        foreach ($associations as $association => $associationData) {
            $requestBody['associations'][$association] = $associationData;
        }
        $oOrderStatuses = $request->getShopwareOrderStatuses('/api/search/state-machine-state', 'POST', $requestBody);
        return $oOrderStatuses;
    }

    /**
     * Returns StateMachineID to filter order statuses 
     *
     * @param $request 
     * @param $apiHelper
     * @param $stateType
     * @return object
     */
    private function getStateMachineId($request, $apiHelper, $stateType) {
        $filters = [
            'technicalName' => [
                'type' => 'equals',
                'values' => $stateType
            ],
        ];
        $requestBody = $apiHelper->prepareFilters($filters, 'POST');
        $stateMachines = $request->getShopwareStateMachine('/api/search/state-machine', 'POST', $requestBody);
        return $stateMachines->getData()[0]->getId();
    }
}
