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
 * $Id$
 *
 * (c) 2010 - 2022 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

// OrderTransactionStates::STATE_MACHINE
MLSetting::gi()->set('ORDER_TRANSACTION_STATES_STATE_MACHINE',  'order_transaction.state');

// OrderTransactionStates::STATE_OPEN
MLSetting::gi()->set('ORDER_TRANSACTION_STATES_STATE_OPEN',  'open');

// OrderStates::STATE_MACHINE
MLSetting::gi()->set('ORDER_STATES_STATE_MACHINE',  'order.state');

// OrderStates::STATE_OPEN
MLSetting::gi()->set('ORDER_STATES_STATE_OPEN',  'open');

// CartPrice::TAX_STATE_GROSS
MLSetting::gi()->set('CART_PRICE_TAX_STATE_GROSS',  'gross');

// OrderDeliveryStates::STATE_OPEN
MLSetting::gi()->set('ORDER_DELIVERY_STATES_STATE_OPEN',  'open');

// OrderDeliveryStates::STATE_MACHINE
MLSetting::gi()->set('ORDER_DELIVERY_STATES_STATE_MACHINE',  'order_delivery.state');

// Defaults::STORAGE_DATE_TIME_FORMAT
MLSetting::gi()->set('DEFAULTS_STORAGE_DATE_TIME_FORMAT',  'Y-m-d H:i:s.v');