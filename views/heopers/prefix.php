<?php
class PrefixHelper extends AppHelper {
    var $helpers = array('Html');
    
    
	/**
	 * prefix対応版リンク 
	 * 
	 * @access public
	 * @author sakuragawa
	 */
    public function link($title, $url = null, $options = array(), $confirmMessage = false) {
        // prefixの一括設定をとりあえず実装(制限機能版)
        if(isset($url['prefix']) && is_array($url)){
            $buf = Configure::read('Routing.prefixes');
            $prefixList = array();
            foreach($buf as $key=>$val){
                $prefixList[$val] = false;
            }
            if($url['prefix'] == false){
                // prefix全消し
                $url = Set::merge($url, $prefixList);
            }else if(is_string($url['prefix'])){
                if(isset($prefixList[$url['prefix']])){
                    $prefixList[$url['prefix']] = true;
                    $url = Set::merge($url, $prefixList);
                }
            }
            unset($url['prefix']);
        }
        
        return $this->Html->link($title, $url, $options, $confirmMessage);
    }
}
?>
