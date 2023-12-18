<?php
/**
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
MLFilesystem::gi()->loadClass('Form_Controller_Widget_Form_Abstract');

class ML_TestOrders_Controller_Main_Tools_TestOrders extends ML_Form_Controller_Widget_Form_Abstract {
    protected $aParameters = array('controller');
    protected $blJsonError = false;

    protected function construct() {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '1024M');
        $this->getNormalizedFormArray();//for getting values
    }

    protected function optionalIsActive($aField) {
        if (isset($aField['optional']['active'])) {
            // 1. already setted
            $blActive = $aField['optional']['active'];
        } else {
            if (is_string($aField)) {
                $sField = $aField;
            } else {
                if (isset($aField['optional']['name'])) {
                    $sField = $aField['optional']['name'];
                } else {
                    $sField = isset($aField['realname']) ? $aField['realname'] : $aField['name'];
                }
            }
            $sField = strtolower($sField);
            // 2. get from request
            $sActive = $this->getRequestField($sField, true);
            if ($sActive == 'false' || $sActive === false || ($sActive === null && $sField == 'completejsondata')) {
                $blActive = false;
            } else {
                $blActive = true;
            }
        }
        return $blActive;
    }

    protected function getFieldMethods($aField) {
        $aParent = parent::getFieldMethods($aField);
        $aParent[] = 'triggerAfterField';
        return $aParent;
    }

    protected function triggerAfterField(&$aField) {
        if (
            isset($aField['type']) &&
            (
                $aField['type'] == 'text'
                ||
                isset($aField[$aField['type']]['field']['type']) && $aField[$aField['type']]['field']['type'] == 'text'
            )
        ) {
            if ($aField['type'] == 'duplicate') {
                foreach ($aField['value'] as $iValue => $aValue) {
                    if (!is_array($aValue) && json_decode($aValue) !== false) {
                        $aValue = json_decode($aValue, true);
                    }
                    if (is_array($aValue)) {
                        $aField['value'][$iValue] = json_indent(json_encode($aValue));
                        $aField['fieldinfo'][$iValue] = array('attributes' => array('rows' => count($aValue) + 2));
                    } elseif (json_decode($aValue)) {
                        $aValue = json_decode($aValue, true);
                        $aField['value'][$iValue] = json_indent($aValue);
                        $aField['fieldinfo'][$iValue] = array('attributes' => array('rows' => count($aValue) + 2));
                    } else {
                        $aField['fieldinfo'][$iValue] = array('attributes' => array('rows' => substr_count($aValue, "\n"), 'style' => 'background:orange'));
                        $this->blJsonError = true;
                    }

                }
            } else {
                if (!is_array($aField['value']) && json_decode($aField['value']) !== false) {
                    $aField['value'] = json_decode($aField['value'], true);
                }
                if (is_array($aField['value'])) {
                    $aField['attributes'] = array(
                        'rows' => count($aField['value']) + 2
                    );
                    $aField['value'] = json_indent(json_encode($aField['value']));
                } else {
                    $aField['attributes'] = array(
                        'rows'  => substr_count($aField['value'], "\n"),
                        'style' => 'background:orange'
                    );
                    $this->blJsonError = true;
                }
            }
            if (isset($aField['optional'])) {
                $aField['optional']['field']['type'] = $aField['type'];
                $aField['type'] = 'optional';
            }
        }
    }

    protected function mergeOrderData() {
        $aRequest = $this->getRequestField();
        $aOrder = array();
        foreach ($this->getField('addresssets', 'addresssets') as $sKey) {
            $aAddress = $this->optionalIsActive(array('name' => $sKey.'address')) ? ($this->getField($sKey.'address', 'value')) : '[]';
            if (json_decode($aAddress) !== false) {
                $aOrder['AddressSets'][$sKey] = json_decode($aAddress, true);
            }
        }
        $fTotalAmount = 0;
        foreach (array('Totals', 'Products') as $sKey) {
            if ($this->optionalIsActive(array('name' => $sKey))) {
                foreach ($this->getField($sKey, 'value') as $sTotal) {
                    if (json_decode($sTotal) !== false) {
                        $aItem = json_decode($sTotal, true);
                        if (isset($aItem['Value'])) {
                            $fTotalAmount += $aItem['Value'];
                        } elseif (isset($aItem['Price'])) {
                            $fTotalAmount += $aItem['Price'];
                        }
                        $aOrder[$sKey][] = $aItem;
                    }
                }
            } else {
                $aOrder[$sKey] = array();
            }
        }
        $aOrder['Order'] = (
            isset($aRequest['order']) && json_decode($aRequest['order']) !== false
        ) ? json_decode($aRequest['order'], true) :
            array();
        $aOrder['Order']['TotalPrice'] = $fTotalAmount;

        $aOrder['MPSpecific'] = (
            isset($aRequest['mpspecific']) && json_decode($aRequest['mpspecific']) !== false
        ) ? json_decode($aRequest['mpspecific'], true) : array();
        if (empty($aOrder['MPSpecific'])) {
            return null;
        } else {
            return $aOrder;
        }
    }

    protected function getOrderData() {
        $aRequest = $this->getRequestField();
        $aOptional = $this->getRequest('optional');
        if ($this->optionalIsActive('completejsondata')) {
            $aOrders = array();
            foreach ($aRequest['completejsondata'] as $sOrder) {
                $aComplete = json_decode($sOrder, TRUE);
                if (((bool)count(array_filter(array_keys($aComplete), 'is_string')))) {
                    $aOrders[] = $aComplete;
                } else {
                    foreach ($aComplete as $aOrder) {
                        $aOrders[] = $aOrder;
                    }
                }
            }
            return $aOrders;
        } else {
            $aOrder = $this->mergeOrderData();
            
            return array($aOrder);
        }
    }

    public function orderAction($blExecute = true) {
        if ($blExecute) {
            try {
                $aRequest = $this->getRequestField();
                $aOrders = $this->getOrderData();
                if ($this->blJsonError === true) {
                    throw new Exception('JSON-data not correct.');
                }
                ML::gi()->init(array('mp' => $aRequest['marketplace']));
                $aInfo = array();
                $aSuccessMsgs = array();
                $aErrorMsgs = array();
                $aUpdateOrder = array();
                $aNewOrder = array();
                foreach ($aOrders as $sKey => $aOrder) {
                    if (isset($aOrder['Products']) && !empty($aOrder['Products'])) {
                        $aNewOrder[] = $aOrder;
                    } else {
                        $aUpdateOrder[] = $aOrder;
                    }
                }
                MLService::getImportOrdersInstance()->setLocalTestOrders($aNewOrder)->execute();
                try {
                    MLService::getUpdateOrdersInstance()->setLocalTestOrders($aUpdateOrder)->execute();
                } catch (Exception $oEx) {
                    //no update instance
                }
                // rescue messages
                $oMessage = MLMessage::gi();
                $oReflection = new ReflectionClass($oMessage);
                $oRefProp = $oReflection->getProperty('aData');
                $oRefProp->setAccessible(true);
                $aMessages = $oRefProp->getValue($oMessage);
                $oRefProp->setValue($oMessage, array(
                    1 => array(),
                    2 => array(),
                    3 => array(),
                    4 => array(),
                    5 => array(),
                    6 => array(),
                    7 => array(),
                    8 => array()
                ));
                ML::gi()->init();
                $oMessage = MLMessage::gi();
                $oReflection = new ReflectionClass($oMessage);
                $oRefProp = $oReflection->getProperty('aData');
                $oRefProp->setAccessible(true);
                $oRefProp->setValue($oMessage, $aMessages);
            } catch (Exception $oEx) {
                MLMessage::gi()->addError($oEx);
            }
            return $this;
        } else {
            return array(
                'aI18n' => array('label' => 'Create or extend order'),
                'aForm' => array(
                    'type'     => 'submit',
                    'position' => 'right',
                )
            );
        }
    }

    protected function getFirstValue($aField, $mValue = null) {
        $mRequest = $this->getRequestField($aField['name']);
        if ($mRequest === null) {
            if ($mValue === null && isset($aField['value'])) {
                return $aField['value'];
            } else {
                return $mValue;
            }
        } else {
            if (is_string($mRequest) && json_decode($mRequest)) {
                return json_decode($mRequest, true);
            } else {
                return $mRequest;
            }
        }
    }

    protected function marketplaceField(&$aField) {
        $aTabIdents = MLDatabase::factory('config')->set('mpid', 0)->set('mkey', 'general.tabident')->get('value');
        require_once MLFilesystem::getOldLibPath('php/callback/callbackFunctions.php');
        if (!MLCache::gi()->exists(strtoupper(__CLASS__).'_'.MLShop::gi()->getShopSystemName().'__marketplaces')) {
            $aMarketplaces = array();
            foreach (MLShop::gi()->getMarketplaces() as $iMarketPlace => $sMarketplace) {
                try {
//                    ML::gi()->init(array('mp' => $iMarketPlace));
//                    if (!MLModul::gi()->isConfigured()) {
//                        throw new Exception('not configured');
//                    }
                    $aMarketplaces[$iMarketPlace] = $sMarketplace . ' ' . (isset($aTabIdents[$iMarketPlace]) && $aTabIdents[$iMarketPlace] != '' ? ': ' . $aTabIdents[$iMarketPlace] . ' ' : '') . '(' . $iMarketPlace . ')';
                } catch (Exception $oEx) {//modul dont exists or not configured - do nothing
                }
            }
            MLCache::gi()->set(strtoupper(__CLASS__).'__marketplaces', $aMarketplaces, 60 * 60 * 4);
            ML::gi()->init();
        }
        $aField['values'] = MLCache::gi()->get(strtoupper(__CLASS__).'__marketplaces');
        $aField['value'] = $this->getFirstValue($aField, key($aField['values']));
    }

    protected function mpSpecificInfoField(&$aField) {
        $aField['ajax'] = array(
            'selector' => '#'.$this->getField('marketplace', 'id'),
            'trigger'  => 'change',
            'field'    => array(
                'type' => 'information',
            ),
        );
        $sMarketPlace = magnaGetMarketplaceByID($this->getField('marketplace', 'value'));
        $aField['value'] = (
        isset($aField['values'][$sMarketPlace])
            ? $aField['values'][$sMarketPlace]
            : $aField['values']['default']
        );
    }

    protected function mpSpecificField(&$aField) {
        $aField['type'] = 'ajax';
        $aField['ajax'] = array(
            'selector' => '#'.$this->getField('marketplace', 'id'),
            'trigger'  => 'change',
            'field'    => array(
                'type' => 'text',
            )
        );
        $sMarketPlace = magnaGetMarketplaceByID($this->getField('marketplace', 'value'));
        $aField['value'] =
            $this->getFirstValue(
                $aField,
                $aField['values']['default']
            );
        $aAccepted = array_keys(isset($aField['values'][$sMarketPlace]) ? $aField['values'][$sMarketPlace] : array());
        foreach ($aField['values'] as $sMp => $aValues) {
            foreach ($aValues as $sKey => $mValue) {
                if ($sMp === $sMarketPlace || $sMp === 'default') {
                    $aField['value'][$sKey] = isset($aField['value'][$sKey]) ? $aField['value'][$sKey] : $mValue;
                } elseif (!in_array($sKey, $aAccepted, true)) {
                    unset($aField['value'][$sKey]);
                }
            }

        }
    }

    protected function addressSetsField(&$aField) {
        $aField['addresssets'] = array('Main', 'Billing', 'Shipping');
    }

    protected function mainAddressField(&$aField) {
        $this->_addressField($aField, 'Main');
    }

    protected function billingAddressField(&$aField) {
        $this->_addressField($aField, 'Billing');
    }

    protected function shippingAddressField(&$aField) {
        $this->_addressField($aField, 'Shipping');
    }

    protected function _addressField(&$aField, $sPrefix = '') {
        if (!MLHttp::gi()->isAjax()) {
            $aField['optional'] = array();
        }
        $aDefault = $this->getField('addresssets', 'value');
        $aDefault['StreetAddress'] = $sPrefix.' '.$aDefault['StreetAddress'];
        $aDefault['Street'] = $sPrefix.' '.$aDefault['Street'];
        $aField['value'] = $this->getFirstValue(
            $aField, $aDefault
        );
        $aField['type'] = 'text';
    }

    protected function orderField(&$aField) {
        if (!MLHttp::gi()->isAjax()) {
            $aField['optional'] = array();
        }
        $aField['duplicate']['field'] = array(
            'type' => 'text',
        );
        $aField['value'] = $this->getFirstValue(
            $aField
        );
    }

    protected function totalsField(&$aField) {
        if (!MLHttp::gi()->isAjax()) {
            $aField['optional'] = array();
        }
        $aField['value'] = $this->getFirstValue($aField);
        $aField['type'] = 'duplicate';
        $aField['duplicate']['field'] = array(
            'type' => 'text',
        );
    }

    protected function productsField(&$aField) {
        $aField['optional'] = array();
        $aField['value'] = $this->getFirstValue($aField);
        $aField['type'] = 'duplicate';
        $aField['duplicate']['field'] = array(
            'type' => 'text',
        );
    }

    protected function completejsondataField(&$aField) {
        $aOrderData = $this->getOrderData();
        $aField['value'] = (empty($aOrderData) || empty($aOrderData[0])) ? array(array()) : $aOrderData;
        $aField['type'] = 'duplicate';
        $aField['duplicate']['field']['type'] = 'text';
    }

}