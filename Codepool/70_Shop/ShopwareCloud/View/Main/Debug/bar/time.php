<?php
class_exists('ML', false) or die();
global $_updaterTime, $_executionTime;
$_executionTime = microtime(true) - $_executionTime;
$memory = memory_usage();
?>
    Entire page served in <b><?php echo microtime2human($_executionTime); ?></b><br/>
    <hr/>
    Updater Time: <?php echo microtime2human($_updaterTime); ?>. <br/>
    API-Request Time: <?php echo microtime2human(MagnaConnector::gi()->getRequestTime()) ?>. <br/>
    Processing Time: <?php echo microtime2human($_executionTime - $_updaterTime - MagnaConnector::gi()->getRequestTime() - ML_ShopwareCloud_Helper_ShopwareCloudInterfaceRequestHelper::gi()->getTotalRequestTime()); ?>.
    <br/>
    Shopware Api Request Time: <?php echo microtime2human(ML_ShopwareCloud_Helper_ShopwareCloudInterfaceRequestHelper::gi()->getTotalRequestTime()); ?>. <br/>
    <hr/>
<?php echo(($memory !== false) ? 'Max. Memory used: <b>'.$memory.'</b>.<br/><hr/>' : ''); ?>
    DB-Stats magnalister:
    <ul>
        <li>Queries used: <b><?php echo MLDatabase::getDbInstance()->getQueryCount(); ?></b></li>
        <li>Query time: <?php echo microtime2human(MLDatabase::getDbInstance()->getRealQueryTime()) ?></li>
    </ul>
    <br/>
    DB-Stats Shopware App SQL:
    <ul>
        <li>Queries used: <b><?php echo MagnaDB::gi()->getQueryCount(); ?></b></li>
        <li>Query time: <?php echo microtime2human(MagnaDB::gi()->getRealQueryTime()) ?></li>
    </ul>
    <br/>
    DB-Cluster-Node: <?php echo MagnaDB::gi()->fetchRow("show variables like 'wsrep_node_name'")['Value']; ?><br/>
<?php
