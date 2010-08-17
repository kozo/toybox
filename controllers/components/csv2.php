<?php
/**
 * Load Security class
 */
//App::import('Core', 'Security');

/**
  Class CSV出力コンポーネント
*/
class Csv2Component extends Object {
	var $_controller;
	
	// CSVデータ
	var $_csvList = array();
	// 内部エンコーディング
	var $_encoding = "";
	
	
	/**
	 * コンポーネント初期化
	 * 
	 * @access public
	 * @author sakuragawa
	 */
	function startup(& $controller){
		$this->_controller = $controller;
		
		$this->_encoding = Configure::read('App.encoding');
	}
	
	
	/**
	 * 内部エンコーディングを設定
	 * 
	 * @access public
	 * @author sakuragawa
	 */
	function setEncoding($enc){
		$this->_encoding = $enc;
	}
	
	/**
	 * ヘッダーデータをセット
	 * 
	 * @access public
	 * @author sakuragawa
	 */
	function setHeader($headerList){
		$this->setMainData($headerList);
	}
	
	
	/**
	 * メインデータをセット
	 * 
	 * @access public
	 * @author sakuragawa
	 */
	function setMainData($dataList){
		// データ個数を取得
		//$count = count($dataList);
		
		$this->_csvList[] = $dataList;
	}
	
	function test(){
		//print_a($this->_csvList);
		
		$buf = "";
		foreach($this->_csvList as $key=>$value)
		{
			$buf .= $this->_createCsvRowData($value);
		}
		
		echo $buf;
	}
	
	/**
	 * CSVダウンロード
	 * 
	 * @access public
	 * @author sakuragawa
	 */
	function download($fileName){
		// Viewを使わないように
		$this->_controller->autoRender = false;
		Configure::write('debug', 0);
		
		header ("Content-disposition: attachment; filename=" . $fileName);
		header ("Content-type: application/octet-stream; name=" . $fileName);
		
		$csvString = "";
		foreach($this->_csvList as $key=>$value)
		{
			$csvString .= $this->_createCsvRowData($value);
		}
		
		// Shift-JISに変換
		mb_language("Japanese");
		$csvData = mb_convert_encoding($csvString,'SJIS-win', $this->_encoding);
		echo($csvData);
		exit;
	}
	
	
	/**
	 * CSVの横１本のデータを作成
	 * 
	 * @access private
	 * @author sakuragawa
	 */
	private function _createCsvRowData($data){
		$maxCount = count($data);
		$count = 0;
		$endFlag = false;
		$csvString = "";
		
		//print_a($data);
		foreach($data as $key=>$value)
		{
			$count++;
			
			if($count == $maxCount){
				// 1行の最後のデータ
				$endFlag = true;
			}
			
			$csvString .= $this->_getCsvData($value, $endFlag);
		}
		
		return $csvString;
	}
	
	/**
	 * CSV用値を作成
	 * 
	 * @access protected
	 * @author sakuragawa
	 * @param  $str 追加するデータ
	 * @param  $endFlag true：valueのあとに改行を付加する.  false：valueのあとにカンマ[,]を付加する
	 * @return 
	 */
	private function _getCsvData($str, $endFlag=false){
		// エスケープ
		//$addData = $this->_escapeData($value);
		$csvValue = str_replace('"','""', $str);
		$csvValue = str_replace(array("\r\n","\r","\n"), '', $csvValue);
		//$csvValue = str_replace(PHP_EOL, '', $csvValue);
		
		if($str != ""){
			$csvValue = sprintf('"%s"',$csvValue);
		}
		
		if($endFlag === false){
			$csvValue .= ",";
		}else{
			$csvValue .= "\r\n";
		}
		
		return $csvValue;
	}
	
	
	/**
	 * ファイルポインタから1行読み込んで配列に変換する
	 * ※ 中村用拡張
	 * 
	 * @access public
	 * @author sakuragawa
	 */
	public function fgetcsv($fp, $colList = array()){
		$buf = fgets($fp);
		if($buf === false){
			return false;
		}
		//print_a($buf);
		$buf = str_replace('""','"', $buf);
		$buf = str_replace(array("\r\n","\r","\n"), '', $buf);
		$buf = str_replace('\n', "\n", $buf);
		$bufArr = explode(",", $buf);
        $csvArr = array();
		//print_a($buf);
		//exit;
        
        // カラム名一覧を作成
        foreach($colList as $key=>$val)
        {
            $csvArr[$val] = "";
        }
        // 一覧に値を入れる
		foreach($bufArr as $key=>$val)
		{
            if(isset($colList[$key])){
                $csvArr[$colList[$key]] = trim($val, '"');
            }else{
                $csvArr[$key] = trim($val, '"');
            }
		}
		
		return $csvArr;
	}
}
?>