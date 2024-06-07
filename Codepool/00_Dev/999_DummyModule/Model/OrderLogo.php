<?php

class ML_DummyModule_Model_OrderLogo {
    public function getLogo(ML_Shop_Model_Order_Abstract $oModel) {
        return 'DummyModule.png';
    }
}
