<?php
/*+**********************************************************************************
 * Andrei Maximov
 ************************************************************************************/
global $LOADER_FILE_DIR;
$LOADER_FILE_DIR = dirname(__FILE__);

class A5_Loader {

	static function includeOnce($qualifiedName, $supressWarning=false) {
		$file = self::resolveNameToPath($qualifiedName);	
		if (!file_exists($file)) {			
			return false;
		}
		$status = -1;
		if ($supressWarning) {
			$status = @include_once $file;
		} else {
			$status = include_once $file;
		}
		$success = ($status === 0)? false : true;
		return $success;
	}
  
  static function resolveNameToPath($qualifiedName, $fileExtension='php') {
		global $LOADER_FILE_DIR;
		$allowedExtensions = array('php', 'js', 'css', 'less');
		$file = '';
		if(!in_array($fileExtension, $allowedExtensions)) {
			return '';
		}
		if($fileExtension == 'js'){
			$file = str_replace('.', DIRECTORY_SEPARATOR, $qualifiedName) . '.' .$fileExtension;
	    $file = $LOADER_FILE_DIR . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .'layouts' . DIRECTORY_SEPARATOR . $file;
			return $file;
		} else {
			$file = str_replace('.', DIRECTORY_SEPARATOR, $qualifiedName) . '.' .$fileExtension;
	    $file = $LOADER_FILE_DIR . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $file;
			return $file;
		}
	}
	
	public static function getComponentClassName($componentType, $componentName, $moduleName) {
		$componentTypeDirectory = strtolower($componentType).'s';
		$moduleDir = $moduleClassPath = $moduleName;
		$moduleSpecificComponentFilePath = A5_Loader::resolveNameToPath('modules.'.$moduleDir.'.'.$componentTypeDirectory.'.'.$componentName);
		$moduleSpecificComponentClassName = $moduleClassPath.'_'.$componentName.'_'.$componentType;
		if(file_exists($moduleSpecificComponentFilePath)) {
			return $moduleSpecificComponentClassName;
		}
		throw new A5_Exception('Class not found...');
	}

	public static function autoLoad($className) {
		$parts = explode('_', $className);
		$noOfParts = count($parts);
		if($noOfParts > 2) {
			$filePath = 'modules.';
			for($i=0; $i<($noOfParts-2); ++$i) {
				$filePath .= $parts[$i]. '.';
			}
			$fileName = $parts[$noOfParts-2];
			$fileComponentName = strtolower($parts[$noOfParts-1]).'s';
			$filePath .= $fileComponentName. '.' .$fileName;		
      return A5_Loader::includeOnce($filePath);
		}
		return false;
	}
}

spl_autoload_register('A5_Loader::autoLoad');
