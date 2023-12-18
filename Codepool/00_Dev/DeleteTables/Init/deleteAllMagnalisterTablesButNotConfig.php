<?php 
MLSession::gi()->flush();
$aDb=MLShop::gi()->getDbConnection();
$rDb=  mysql_connect($aDb['host'], $aDb['user'], $aDb['password']);
mysql_select_db($aDb['database'], $rDb);
$rQuery=mysql_query("show tables like 'magnalister_%'",$rDb);
while($aRow= mysql_fetch_array($rQuery,MYSQL_NUM)){
    if(!in_array($aRow[0],array(
        'magnalister_config'
        ,'magnalister_apirequests'
        ,'magnalister_selection'
        ,'magnalister_amazon_prepare'
        ,'magnalister_apirequests'
        ,'magnalister_products'
        ,'magnalister_orders'
        ,'magnalister_preparedefaults'
        ))){
        MLMessage::gi()->addInfo($aRow[0]);
        mysql_query("drop table ".$aRow[0]);
    }
}
mysql_close($rDb);
?>