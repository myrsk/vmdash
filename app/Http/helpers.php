<?php 

if (! function_exists('formatSize')) {
    function formatSize($bytes,$decimals=2){
        $size=array('B','KB','MB','GB','TB','PB','EB','ZB','YB');
        $factor=floor((strlen($bytes)-1)/3);
        return round(sprintf("%.{$decimals}f",$bytes/pow(1024,$factor))).@$size[$factor];
    }
}