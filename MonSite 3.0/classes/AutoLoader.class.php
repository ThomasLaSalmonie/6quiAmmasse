<?php
// Load my root class
error_reporting(E_ALL);
require_once(__ROOT_DIR . '/classes/MyObject.class.php');
class AutoLoader extends MyObject {
public function __construct() {
spl_autoload_register(array($this, 'load')); //des qu'il comprend pas une chaine il envoie loead à this
}
// This method will be automatically executed by PHP whenever it encounters
// an unknown class name in the source code
private function load($className) {
	$repertoire = array('classes', 'model', 'controller', 'view');
	$i =0;
	$fichier = null;
	
	do{
		$fichier = __ROOT_DIR.'/'.$repertoire[$i].'/'.ucfirst ($className).'.class.php';
		$i++;
	}while(!is_readable($fichier) && $i<count($repertoire));

	if(!is_readable($fichier))
		throw new Exception('unknown class'.$className);
	
	require_once($fichier);
	
	}
}
$__LOADER = new AutoLoader(); //variable globale, instencie la classe