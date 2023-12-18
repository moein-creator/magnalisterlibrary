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
 * (c) 2010 - 2021 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

class ML_Modul_Model_Service_UploadInvoices_Abstract extends ML_Modul_Model_Service_Abstract {

    /**
     * @var int
     */
    protected $invoicesPerRequest = 10;

    /*
     * string
     */
    protected $sInvoiceOptionConfig;

    /*
     * string
     */
    protected $sInvoiceNumberOptionConfig;

    /**
     * @var array
     */
    protected $aERPInvoiceDataCache = array();

    /**
     * @var string
     */
    protected $sOrderIdIndexName = 'MarketplaceOrderId';


    /**
     * @var string
     */
    protected $sDocumentTypeIndexName = 'InvoiceType';

    public function __construct() {
        MagnaConnector::gi()->setTimeOutInSeconds(600);
        @set_time_limit(60 * 10); // 10 minutes per module
        parent::__construct();
        if (in_array($this->getInvoiceOptionConfig(), array('erp'), true)) {
            $this->setErpSetting();
        }

    }

    protected function setErpSetting() {
        try {
            MLHelper::getReceiptUpload()->setConfig(array(
                'Invoice'              => MLModule::gi()->getConfig('invoice.erpinvoicesource'),
                'Invoice_DESTINATION'  => MLModule::gi()->getConfig('invoice.erpinvoicedestination'),
                'Reversal'             => MLModule::gi()->getConfig('invoice.erpreversalinvoicesource'),
                'Reversal_DESTINATION' => MLModule::gi()->getConfig('invoice.erpreversalinvoicedestination'),
            ));
        } catch (MagnaException $e) {
            if (ML_Form_Helper_ReceiptUpload::$ReceiptUploadError == $e->getCode()) {
                MLHelper::gi('stream')->stream($e->getMessage());
                MLErrorLog::gi()->addError(0, ' ', $e->getMessage(), array());
            } else {
                throw $e;
            }
        }
    }

    public function execute() {
        if (in_array($this->getInvoiceOptionConfig(), array('erp', 'webshop', 'magna'), true)) {
            $offset = 0;

            $runOnce = false;
            if (MLRequest::gi()->data('runOnce') == 'true') {
                $runOnce = true;
            }

            do {
                try {
                    $aResults = MagnaConnector::gi()->submitRequest(array(
                        'ACTION'         => 'GetOrdersToUploadInvoices',
                        'OFFSET'         => array(
                            'COUNT' => $this->invoicesPerRequest,
                            'START' => $offset
                        ),
                        'RequestVersion' => 2,
                    ), true);
                } catch (MagnaException $e) {
                    MLHelper::gi('stream')->deeper('A problem occurred by getting orders from API.');
                    MLHelper::gi('stream')->higher('');
                    return;
                }

                $aOrders = $aResults['DATA'];
                $this->displayListOfProcessingOrder($aOrders);

                MLHelper::gi('stream')->deeper('Confirmation result');
                $aDataToSubmit = array();
                foreach ($aOrders as $aOrder) {

                    $oOrder = MLOrder::factory()->getByMagnaOrderId($aOrder[$this->sOrderIdIndexName]);
                    if (!$oOrder->exists()) {
                        MLHelper::gi('stream')->stream($oOrder->get('special').' doesn\'t exist in shop-system (magnalister_orders table)');
                    } else {
                        try {
                            // Shopware 5+6, WooCommerce, Shopify
                            if (method_exists($oOrder, 'getShopOrderObject')) {
                                $oOrder->getShopOrderObject();
                                // Magento, Prestashop
                            } elseif (method_exists($oOrder, 'getShopOrder')) {
                                $oOrder->getShopOrder();
                            }
                        } catch (Exception $oEx) {
                            MLHelper::gi('stream')->stream($oOrder->get('special').' doesn\'t exist in shop-system');
                            continue;
                        }

                        // if Invoice Config is use ERP technique pull File and Invoice number from configured directory
                        $sOrderInvoiceFile = $this->getInvoiceFile($oOrder, $aOrder[$this->sDocumentTypeIndexName]);
                        $sOrderInvoiceNumber = $this->getInvoiceNumber($oOrder, $aOrder[$this->sDocumentTypeIndexName]);
                        if (empty($sOrderInvoiceNumber) && $this->getInvoiceOptionConfig() === 'magna' && $this->getInvoiceNumberOptionConfig() === 'matching') {
                            $sMessage = MLI18n::gi()->get('UploadInvoice_Error_Empty_InvoiceNumber', array(
                                        'order-id' => $oOrder->get('special')
                                    )
                                ).'('.$oOrder->getShopOrderId().')';
                            MLHelper::gi('stream')->stream($sMessage);
                            MLErrorLog::gi()->addError(0, ' ', $sMessage, array('MOrderID' => $oOrder->get('special')));
                        } else if (!empty($sOrderInvoiceFile) || $this->getInvoiceOptionConfig() === 'magna') {
                            $aDataToSubmit[] = $this->manipulateSubmittedInvoiceData(array(
                                'TotalAmount'            => $oOrder->getShopOrderTotalAmount(),
                                'TotalVAT'               => $oOrder->getShopOrderTotalTax(),
                                'File'                   => $sOrderInvoiceFile,
                                'InvoiceNumber'          => $sOrderInvoiceNumber,
                                $this->sOrderIdIndexName => $aOrder[$this->sOrderIdIndexName],
                            ), $aOrder);
                        } else if (empty($sOrderInvoiceFile) && in_array($this->getInvoiceOptionConfig(), array('webshop', 'erp'), true)) {
                            MLHelper::gi('stream')->stream('No pdf is available for order number: '.$oOrder->get('special').', Shop order id:'.$oOrder->getShopOrderId());
                        }
                    }
                }
                //echo print_m(json_indent(json_encode($aDataToSubmit)));
                //return;
                try {
                    $aResponse = MagnaConnector::gi()->submitRequest($this->manipulateUploadRequest(array(
                        'ACTION'   => 'UploadInvoices',
                        'Invoices' => $aDataToSubmit,
                    )));

                    foreach ($aResponse['CONFIRMATIONS'] as $sMarketplaceOrderId) {
                        MLHelper::gi('stream')->stream($sMarketplaceOrderId.' is uploaded and confirmed properly.');
                        $oOrder = MLOrder::factory()->getByMagnaOrderId($sMarketplaceOrderId);
                        if ($oOrder->exists()) {
                            $aOrderData = $oOrder->get('data');
                            $aOrderData['Invoice'] = 'sent';
                            $oOrder->set('data', $aOrderData)->save();
                            MLHelper::getReceiptUpload()->markOrderAsProcessed($oOrder->getShopOrderId());
                        }
                    }
                } catch (\Exception $ex) {
                    MLLog::gi()->add('UploadInvoices_'.MLModule::gi()->getMarketPlaceId().'_Exception', array(
                        'RequestData' => MagnaConnector::gi()->getLastRequest(),
                        'Exception'   => array(
                            'Message'   => $ex->getMessage(),
                            'Code'      => $ex->getCode(),
                            'Backtrace' => $ex->getTrace(),
                        )
                    ));
                }
                $offset += $this->invoicesPerRequest - count($aResponse['CONFIRMATIONS']);
                MLHelper::gi('stream')->higher('');
            } while (!$runOnce && count($aOrders) == $this->invoicesPerRequest); // while the response includes the same amount of requested orders

        }
    }

    protected function getInvoiceOptionConfig() {
        if ($this->sInvoiceOptionConfig === null) {
            $this->sInvoiceOptionConfig = MLModule::gi()->getConfig('invoice.option');
        }
        return $this->sInvoiceOptionConfig;
    }

    protected function getInvoiceNumberOptionConfig() {
        if ($this->sInvoiceNumberOptionConfig === null) {
            $this->sInvoiceNumberOptionConfig = MLModule::gi()->getConfig('invoice.invoicenumberoption');
        }
        return $this->sInvoiceNumberOptionConfig;
    }

    /**
     * @param ML_Shop_Model_Order_Abstract $oOrder
     * @param $invoiceType
     * @return string|null
     * @throws MagnaException
     */
    protected function getInvoiceFile($oOrder, $invoiceType) {
        if ($this->getInvoiceOptionConfig() === 'erp') {
            $aData = $this->getERPInvoiceData($oOrder->getShopOrderId(), $invoiceType, $oOrder->get('special'));
            $sOrderInvoiceFile = $aData['OrderInvoiceFile'];
        } else {
            $sOrderInvoiceFile = $oOrder->getShopOrderInvoice($invoiceType);
        }
        return $sOrderInvoiceFile;
    }

    /**
     * @param ML_Shop_Model_Order_Abstract $oOrder
     * @param $invoiceType
     * @return string|null
     */
    public function getInvoiceNumber($oOrder, $invoiceType) {
        if ($this->getInvoiceOptionConfig() === 'erp') {
            $aData = $this->getERPInvoiceData($oOrder->getShopOrderId(), $invoiceType, $oOrder->get('special'));
            $sOrderInvoiceNumber = $aData['OrderInvoiceNumber'];
        } else {
            $sOrderInvoiceNumber = $oOrder->getInvoiceNumber($invoiceType);
        }
        return $sOrderInvoiceNumber;
    }

    protected function getERPInvoiceData($sShopOrderID, $sInvoiceType, $sMarketplaceOrderId) {
        if (!isset($this->aERPInvoiceDataCache[$sShopOrderID][$sInvoiceType])) {
            try {
                $receipt = MLHelper::getReceiptUpload()->processReceipt($sShopOrderID, $sInvoiceType);
                $sOrderInvoiceNumber = $receipt['receiptNr'];
                $sOrderInvoiceFile = $receipt['file'];
            } catch (MagnaException $e) {
                MLHelper::gi('stream')->stream($e->getMessage());
                MLErrorLog::gi()->addError(0, ' ', $e->getMessage(), array('MOrderID' => $sMarketplaceOrderId));
                $sOrderInvoiceNumber = null;
                $sOrderInvoiceFile = null;
            }
            $this->aERPInvoiceDataCache[$sShopOrderID][$sInvoiceType] = array(
                'OrderInvoiceNumber' => $sOrderInvoiceNumber,
                'OrderInvoiceFile'   => $sOrderInvoiceFile,
            );
        }
        return $this->aERPInvoiceDataCache[$sShopOrderID][$sInvoiceType];
    }

    /**
     * @param mixed $aOrders
     * @return void
     */
    protected function displayListOfProcessingOrder($aOrders) {
        MLHelper::gi('stream')->deeper('Processing orders to upload invoices');
        foreach ($aOrders as $aOrder) {
            MLHelper::gi('stream')->stream($aOrder[$this->sOrderIdIndexName].' : '.$aOrder[$this->sDocumentTypeIndexName]);
        }
        MLHelper::gi('stream')->higher('');
    }

    protected function manipulateSubmittedInvoiceData($aData, $aOrder) {
        $aData[$this->sDocumentTypeIndexName] = $aOrder[$this->sDocumentTypeIndexName];
        return $aData;
    }


    protected function manipulateUploadRequest($aData) {
        return $aData;
    }

}
