<?php
return false;
return (
    strpos(MLRequest::gi()->data('controller'), 'main_tools_image') !== false
    &&
    MLRequest::gi()->data('oldimage') == 'old'
) ? true : false;