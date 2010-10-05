<?php
class ImagelinkHelper extends AppHelper {
    var $helpers = array('Html');
    
    
    /**
     * 画像リンク
     * 
     * @access public
     * @author sakuragawa
     */
    function link($imagePath, $url, $imageParam = array(), $linkParam = array()){
        $image = $this->Html->image($imagePath, $imageParam);
        $linkParam['escape'] = false;
        return $this->Html->link($image, $url, $linkParam, false);
    }
}
?>
