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
include_once(M_DIR_LIBRARY.'request/shopware/ShopwareCustomField.php');

use library\request\shopware\ShopwareCustomField;

class ML_ShopwareCloud_Update_AddOrderCustomFields extends ML_Core_Update_Abstract {

    const OTTO_ORDER_RETURN_CARRIER_FIELD = 'returnCarrier';
    const OTTO_ORDER_RETURN_TRACKING_KEY_FIELD = 'returnTrackingNumber';
    const AMAZON_ORDER_SHIPPING_CARRIER_FIELD = 'carrierCode';
    const AMAZON_ORDER_SHIPPING_METHOD_FIELD = 'shipMethod';

    protected $sShopId;

    /** @var library\request\shopware\ShopwareCustomField $oCustomFieldRequest */
    protected  $oCustomFieldRequest;

    protected $oApiHelper;

    protected $customFieldPosition;
    protected $fieldSetId = null;

    // we store here translations because it is difficult to get translation for specific language using MLI18n class
    // we need translations for all languages because we need to add all languages when we create custom fields
    protected $customFieldsTranslation = array(
        self::OTTO_ORDER_RETURN_CARRIER_FIELD => array(
            'en-GB' => array(
                'label' => 'Otto Return Carrier',
                'placeholder' => 'Add Return Carrier'
            ),
            'de-DE' => array(
                'label' => 'Otto Return Carrier',
                'placeholder' => 'Add Return Carrier'
            ),
            'fr-FR' => array(
                'label' => 'Otto Return Carrier',
                'placeholder' => 'Add Return Carrier'
            ),
        ),
        self::OTTO_ORDER_RETURN_TRACKING_KEY_FIELD => array(
            'en-GB' => array(
                'label' => 'Otto Return Tracking Key',
                'placeholder' => 'Add Return Tracking Key'
            ),
            'de-DE' => array(
                'label' => 'Otto Return Tracking Key',
                'placeholder' => 'Add Return Tracking Key'
            ),
            'fr-FR' => array(
                'label' => 'Otto Return Tracking Key',
                'placeholder' => 'Add Return Tracking Key'
            ),
        ),
        self::AMAZON_ORDER_SHIPPING_CARRIER_FIELD => array(
            'en-GB' => array(
                'label' => 'Amazon Shipping Carrier',
                'placeholder' => 'Add Carrier Code'
            ),
            'de-DE' => array(
                'label' => 'Amazon Transportunternehmen',
                'placeholder' => 'Add Carrier Code'
            ),
            'fr-FR' => array(
                'label' => 'Amazon Transporteur',
                'placeholder' => 'Add Carrier Code'
            ),
        ),
        self::AMAZON_ORDER_SHIPPING_METHOD_FIELD => array(
            'en-GB' => array(
                'label' => 'Amazon Shipping Method',
                'placeholder' => 'Add Shipping Method'
            ),
            'de-DE' => array(
                'label' => 'Amazon Lieferservice (Versandart / Versandmethode)',
                'placeholder' => 'Add Shipping Method'
            ),
            'fr-FR' => array(
                'label' => 'Amazon Mode d\'expédition',
                'placeholder' => 'Add Shipping Method'
            ),
        )
    );

    public function execute() {
        $this->sShopId = MLShopwareCloudAlias::getShopHelper()->getShopId();
        $this->oApiHelper = new APIHelper();
        $this->oCustomFieldRequest = new ShopwareCustomField($this->sShopId);
        $this->ceateMagnalisterOrderDetailsField();
        $aShopInfo = MLShop::gi()->getShopInfo();
        if(isset($aShopInfo['DATA']['Marketplaces'])) {
            foreach ($aShopInfo['DATA']['Marketplaces'] as $marketplace) {
                if (isset($marketplace['Marketplace']) && ($marketplace['Marketplace'] === 'otto' || $marketplace['Marketplace'] === 'amazon')) {
                    $this->createOrderCustomFileds($marketplace['Marketplace']);
                }
            }
        }
        return parent::execute();
    }

    /**
     * Create custom fields for order details in the
     *
     * @param $shopwareShopId
     * @return void
     * @throws Exception
     */
    public function ceateMagnalisterOrderDetailsField() {
        $apiHelper = new APIHelper();
        $fieldSetName = $apiHelper::ORDER_DETAILS_FIELD_SET;
        $fieldName = $apiHelper::ORDER_DETAILS_FIELD;
        $customFieldExists = $apiHelper->checkIfCustomFieldExists($this->sShopId);
        if (!$customFieldExists) {
            $fieldSetId = $apiHelper::randomHex();
            $fieldId = $apiHelper::randomHex();
            $this->customFieldPosition = '1';
            $customFiled = array(
                'id' => $fieldId,
                'name' => $fieldName,
                'type' => 'html',
                'config' => array(
                    'label' => array('en-GB' => null),
                    'placeholder' => array('en-GB' => null),
                    'componentName' => 'sw-text-editor',
                    'customFieldType' => 'textEditor',
                    'customFieldPosition' => $this->customFieldPosition
                ),
                'customFieldSet' => array(
                    'id' => $fieldSetId,
                    'name' => $fieldSetName,
                    'config' => array(
                        'label' => array(
                            'en-GB' => I18N::gi('EN')->getTrans('messages', 'orderDetails.customFieldSet'),
                            'de-DE' => I18N::gi('DE')->getTrans('messages', 'orderDetails.customFieldSet'),
                            'fr-FR' => I18N::gi('FR')->getTrans('messages', 'orderDetails.customFieldSet'),
                        )
                    )
                )

            );
            $shopwareCustomFieldsRequest = new ShopwareCustomField($this->sShopId);
            $shopwareCustomFieldsRequest->createShopwareCustomField($customFiled);
            $customFiledSetRelation = array(
                'customFieldSetId' => $fieldSetId,
                'entityName' => 'order'
            );
            $shopwareCustomFieldsRequest->createShopwareCustomFieldSetRelation($customFiledSetRelation);
        }
    }

    public function createOrderCustomFileds($marketplace) {
        $aCustomFields = array();
        // setting the custom field set id we try to get the maganlister details field
        // if we found it we will set custom field set id
        if (!isset($this->fieldSetId)) {
            $this->checkIfCustomFieldExists($this->oApiHelper::ORDER_DETAILS_FIELD);
        }

        if ($marketplace === 'otto') {
            $aCustomFields = $this->getMarketplaceConstants($marketplace);
        }

        if ($marketplace === 'amazon') {
            $aCustomFields = $this->getMarketplaceConstants($marketplace);
        }

        foreach ($aCustomFields as $customField) {
            $customFieldExists = $this->checkIfCustomFieldExists($customField);
            if (!$customFieldExists) {
                if (!isset($this->fieldSetId)) {
                    throw new Exception('Shopware Cloud Custom FieldSetId is missing for marketplace: '. $marketplace, 1712235636);
                }

                $fieldId = $this->oApiHelper::randomHex();
                $customFiled = array(
                    'id' => $fieldId,
                    'name' => $customField,
                    'type' => 'text',
                    'customFieldSetId' => $this->fieldSetId,
                    'config' => array(
                        'label' => array(
                            'en-GB' => $this->customFieldsTranslation[$customField]['en-GB']['label'],
                            'de-DE' => $this->customFieldsTranslation[$customField]['de-DE']['label'],
                            'fr-FR' => $this->customFieldsTranslation[$customField]['fr-FR']['label'],
                        ),
                        'placeholder' => array(
                            'en-GB' => $this->customFieldsTranslation[$customField]['en-GB']['placeholder'],
                            'de-DE' => $this->customFieldsTranslation[$customField]['de-DE']['placeholder'],
                            'fr-FR' => $this->customFieldsTranslation[$customField]['fr-FR']['placeholder'],
                        ),
                        'customFieldPosition' => $this->customFieldPosition
                    ),

                );
                $this->oCustomFieldRequest->createShopwareCustomField($customFiled);
                $this->customFieldPosition ++;
            }
        }
        if (empty($aCustomFields)) {
            MLMessage::gi()->addDebug('Line: '.__LINE__.' Class:'.__CLASS__, 'Custom Fields array missing something have is wrong. Check this update script.');
        }
    }

    public function getMarketplaceConstants($marketplace) {
        $reflectionClass = new ReflectionClass(__CLASS__);
        $classConstants = $reflectionClass->getConstants();
        $marketplaceConstants = [];

        foreach ($classConstants as $name => $value) {
            if (strpos($name, strtoupper($marketplace).'_') === 0) {
                $marketplaceConstants[] = $value;
            }
        }

        return $marketplaceConstants;
    }

    public function checkIfCustomFieldExists($technicalName) {
        $filters['name'] = [
            'type' => 'equals',
            'values' => $technicalName
        ];
        $preparedFilters = $this->oApiHelper->prepareFilters($filters, 'POST');
        $oMagnaCustomFields = $this->oCustomFieldRequest->getShopwareCustomFields('/api/search/custom-field', 'POST', $preparedFilters);
        if (is_array($oMagnaCustomFields->getData()) && $oMagnaCustomFields->getData()[0]->getId() !== null) {
            // setting the custom field set id in case we find maganlister details
            if ($technicalName === $this->oApiHelper::ORDER_DETAILS_FIELD) {
                $this->fieldSetId = $oMagnaCustomFields->getData()[0]->getAttributes()->getCustomFieldSetId();
                $this->customFieldPosition = $oMagnaCustomFields->getData()[0]->getAttributes()->getConfig()->getCustomFieldPosition() + 1;
            }
            $result = true;

        } else {
            $result = false;
        }

        return $result;
    }
}



