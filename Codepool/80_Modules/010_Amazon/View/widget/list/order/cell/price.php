<?php

/* @var $this  ML_Amazon_Controller_Amazon_ShippingLabel_Orderlist */
/* @var $oList ML_Amazon_Model_List_Amazon_Order */
 if (!class_exists('ML', false))
     throw new Exception();
?>
<?php

echo MLPrice::factory()->format($fAmount, $sCurrencyCode) ;
