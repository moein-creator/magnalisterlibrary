<?php 
if (!class_exists('ML', false))
    throw new Exception();
?>
<?php
/** @var $this ML_DeployI18n_Controller_Main_Tools_I18n */
    if(
            $this->getRequest('download') !==null
    ){
        $this->downloadPackage();
    }

    ?>    
        <form action="<?php echo $this->getCurrentUrl() ?>" method="post">
            <?php foreach(MLHttp::gi()->getNeededFormFields() as $sName=>$sValue ){ ?>
                <input type="hidden" name="<?php echo $sName ?>" value="<?php echo $sValue?>" />
            <?php } ?>
                <div>download from : <?php echo MLSetting::gi()->get('sUpdateUrl') . 'magnalister/'; ?></div>
                <div>save in :  <?php echo MLFilesystem::getCachePath().'I18n/'; ?></div>
            <input class="mlbtn" type="submit" value="Download Language Files" name="<?php echo MLHttp::gi()->parseFormFieldName('download')?>" />
        </form>
   <?php 
   
//deprecated , 
   if(
            $this->getRequest('lang')===null
            || $this->getRequest('lang')==''
            || !is_string($this->getRequest('lang'))
    ){
    ?>
       <form action="<?php echo $this->getCurrentUrl() ?>" method="post">
            <?php foreach(MLHttp::gi()->getNeededFormFields() as $sName=>$sValue ){ ?>
                <input type="hidden" name="<?php echo $sName ?>" value="<?php echo $sValue ?>"/>
            <?php } ?>
           <input type="text" placeholder="Sprachkürzel" name="<?php echo MLHttp::gi()->parseFormFieldName('lang') ?>"/>
           <input class="button" type="submit"/>
       </form>
        <?php
    }else{
        $sUrl=$this->createLangFiles();
        ?>
       <a href="<?php echo $sUrl; ?>">CSV-Datei</a>
            <?php
    }
