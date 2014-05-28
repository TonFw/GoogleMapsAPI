<?php
    
    require_once 'ActiveRecord/ActiveRecord.php';
    require_once 'TonFace/tonlib_fb/TonLibFB.php';

    function count_dimension($Array, $count = 0) {
        if(is_array($Array)) return count_dimension(current($Array), ++$count);
        else return $count;
    }

?>
