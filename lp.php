<?php
class NewLangParser  {
  private $_handler;
  private $_path;
  
  public function stream_open($path, $mode, $options, &$opened_path) {           

    stream_wrapper_restore('file');
    $this->_handler = fopen($path, $mode);
    $this->_path = $path;
    self::registerLang();
    return true;
  }             
  
  public function stream_read($count) {

    $content = fread($this->_handler, $count);
    $content = $this->_parseCode($content);
    
    if ($content)
      return "<?php\n".$content;
    return "";
  }             
  
  private function _parseCode($content) {
    $content = str_replace('COOL', 'class ', $content);
	$content = str_replace('BOSS', 'public function ', $content);
    $content = str_replace('ROBO', 'return ', $content);
    $content = str_replace('DOT', 'echo', $content);
	 $content = str_replace('NERUNGUDA', 'else', $content);
	 $content = str_replace('#', '$', $content); 
	$content = preg_replace('/(\w+).new\((.*)\)/', 'new \\1(\\2)', $content);
    $content = str_replace('.', '->', $content);       
    
   // $content = preg_replace('/NERUNGUDA\s*\((.*)\)/', 'else)', $content);
	//$content = preg_replace('/NERUNGUDA\s*\((.*)\)/', 'if(!(\\1))', $content);
	$content = preg_replace('/NERUPUDA\((.*)\)/', 'if(\\1)', $content);

	//$content = preg_replace('/till\((.*)\)/', 'while(\\1)', $content);
	$content = preg_replace('/MAZILCHI/', 'while', $content);
	//$content = preg_replace('/still/', 'for', $content);
	//$content = preg_replace('/still\((.*)\)/', 'for(\\3)', $content);
	//$content = preg_replace('/for.(\w+)\((.*)\)/', 'new \\1(\\2)', $content);
	
	
	/*
	$patterns = array();
$patterns[0] = '/still/';
$patterns[1] = '/#/';
$patterns[2] = '/;/';
$replacements = array();
$replacements[2] = 'for';
$replacements[1] = '$';
$replacements[0] = ';';
//echo preg_replace($patterns, $replacements, $string);
	
	$content = preg_replace($patterns, $replacements, $content);
	*/
    return $content;
  }
  
  public function stream_eof() { 

    return true;
  }             
  
  public function stream_stat() {      

    return fstat($this->_handler);
  }                           
  
  public function url_stat() {  

    return stat($this->_path);
  }    
  
  public static function registerLang() {
    stream_wrapper_unregister('file');
    stream_wrapper_register('file', 'NewLangParser');
  }
}

NewLangParser::registerLang();
?>