<?php
/**
 * Load Security class
 */
//App::import('Core', 'Security');

/**
  Class CSV出力コンポーネント
*/
class JsonComponent extends Object {
	var $_controller;
	
	
	/**
	 * コンポーネント初期化
	 * 
	 * @access public
	 * @author sakuragawa
	 */
	function startup(& $controller){
		$this->_controller = $controller;
	}
	
	/**
	 * cakeライブラリ仕様 オブジェクトをJSONに変換するメソッド
	 *
	 * @params $data オブジェクト
	 * @return String JSONストリング
	 */
	function toJson($data){
		// Helperの呼び出し
		App::import("Helper", "Javascript");
		
		// インスタンス化
		$javascript = new JavascriptHelper();
		
		// オブジェクトをJSON形式に変換して、文字列で返す
		return $javascript->object($data);
	}
	
	/**
     * AJAX用データをechoする
     * ※array(key=>value)の配列をarray(key, value)の配列に変換してjsonをreturnする
     * 
     * @access public
     * @author sakuragawa
     */
    protected function getSelectBoxUtilJsonData($data){
        $list = array();
        foreach($data as $key=>$val)
        {
            $list[] = array($val, $key);
        }
        $json = $this->cakeJsonEncode($list);

        return $json;
    }
    
	/**
	 * JSONを配列へ変換
	 * 
	 * @access public
	 * @author sakuragawa
	 */
    function toArray($json){
        return json_decode($json);
    }
}
?>