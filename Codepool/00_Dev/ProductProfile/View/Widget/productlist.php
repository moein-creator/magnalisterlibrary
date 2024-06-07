<?php if (!class_exists('ML', false))
    throw new Exception(); ?>
<?php if ($this instanceof ML_Productlist_Controller_Widget_ProductList_Abstract) { ?>
    <?php
    if (MLSetting::gi()->get('blProductProfileTruncateTable')) {
        MLDatabase::getDbInstance()->query('truncate table magnalister_products');
    }
    /* @var $oList ML_Productlist_Model_ProductList_Abstract */
    $oList = $this->getProductList();
    $aStatistic = $oList->getStatistic();
    //        new dBug($aStatistic);
    //        new dBug($oList->getHead());
//        new dBug(array('product'=>$oList->getList()->current(),'data'=>$oList->getList()->current()->mixedData()));
        
        
        
        $iTime=microtime(true);
        $iMem=  memory_get_usage(true);
        ob_start();
    ?>
    <div class="ml-plist">
        <div>
            <?php
                $this
                    ->includeView('widget_productlist_action_selection',    get_defined_vars())
                    ->includeView('widget_productlist_action_top',          get_defined_vars())
                ;
            ?>
        </div>
        <div class="clear"></div>
        <?php
            $this->includeView('widget_productlist_filter',              get_defined_vars());
        ?>
		<div class="pagination_bar">
		<?php
			$this->includeView('widget_productlist_pagination',          get_defined_vars());
		?>
		</div>
		<?php
            $this
                ->includeView('widget_productlist_list',                get_defined_vars());
		?>
		<div class="pagination_bar">
			<?php
			$this->includeView('widget_productlist_pagination',          get_defined_vars());
			?>
		</div>
		<?php
			$this
                ->includeView('widget_productlist_action_eachRow',      get_defined_vars())
                ->includeView('widget_productlist_action_bottom',       get_defined_vars())
            ;
            MLSettingRegistry::gi()->addJs('magnalister.productlist.js');
        MLSetting::gi()->add('aCss', array('magnalister.productlist.css?%s'), true);
        ?>
    </div>
    <?php
            $sInfo=substr((MLSetting::gi()->get('blProductProfileUseOriginal')?'org':'dev').' | '.MLSetting::gi()->get('sProductProfileInfo'),0,20);
            $sHtml= ob_get_contents();
            ob_end_clean();
            echo $sHtml;
            $sTime=((string)  microtime(true)-$iTime).'s';
            $base = log(memory_get_usage(true)-$iMem) / log(1024);
            $suffixes = array('', 'k', 'M', 'G', 'T');
            $sMem= round(pow(1024, $base - floor($base)), 3) . $suffixes[(string)floor($base)].'B';
    
            $oProductList=MLProduct::factory()->getList();
            $oProductList->getQueryObject()->orderBy(array('productsid'));
            $aDb=MLProduct::factory()->getList()->data();
            foreach($aDb as &$aRow){
                unset($aRow['lastused']);
            }
            echo '<textarea style="width:40%;float:left">'.print_r($aDb,1).'</textarea>';
            echo '<textarea style="width:40%;float:right">'.$sHtml.'</textarea>';
        if(MLSetting::gi()->get('blProductProfileWriteLog')){
            MLLog::gi()->add('productprofile-list', 
                $sInfo.str_repeat(' ', 20-strlen($sInfo)).' '.
                'ident('.$this->getIdent().')'.  str_repeat(' ',30-strlen($this->getIdent())).
                'time('.$sTime.')'.  str_repeat(' ',17-strlen($sTime)).
                'memory('.$sMem.')'.  str_repeat(' ',9-strlen($sMem)).
                'db('.md5(MLHelper::getEncoderInstance()->encode($aDb)).') '.
                'html('.md5($sHtml).')'
            );
        }else{
            MLMessage::gi()->addDebug( 
                $sInfo.'<br />'.
                'ident('.$this->getIdent().')<br />'.
                'time('.$sTime.')<br />'.
                'memory('.$sMem.')<br />'.
                'db('.md5(MLHelper::getEncoderInstance()->encode($aDb)).')<br />'.
                'html('.md5($sHtml).')'
           );
        }
    ?>
<?php }?>