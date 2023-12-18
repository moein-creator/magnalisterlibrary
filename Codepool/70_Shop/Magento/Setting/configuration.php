<?php

MLSetting::gi()->get('configuration');//throws exception if not exists
MLSetting::gi()->add('configuration', array(
    'productfields' => array(
        'fields' => array(
             array(
                'name' => 'general.weightunit',
                'type' => 'select'
            ),
        ),
    ),
), true);