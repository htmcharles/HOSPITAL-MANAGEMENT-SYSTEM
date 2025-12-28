<?php
 function limit_word($str, $len) {
    if (strlen($str) < $len)
        return $str;
 
    $str = substr($str,0,$len);
    if ($spc_pos = strrpos($str," "))
            $str = substr($str,0,$spc_pos);
 
    return $str . ".....";
} 
?>