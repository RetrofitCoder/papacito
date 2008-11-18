<?
chdir(dirname(__FILE__));
require_once('../papacito.php');

class PapacitoTest extends PHPUnit_Framework_TestCase {
	private $obj = null;
	private $names = array();
	
	const FUNC_RAW_NAME = 'get_raw_name';
	const FUNC_PATERNAL = 'get_paternal';
	const FUNC_MATERNAL = 'get_maternal';
	const FUNC_MARRIED = 'get_married';
	const FUNC_WIDOW = 'is_widow';
	const FUNC_EXCESS = 'get_excess';
	
	private function set_names($raw, $paternal, $maternal, $married, $widow, $excess) {
		$this->names[] = array(
			self::FUNC_RAW_NAME => trim($raw),
			self::FUNC_PATERNAL => $paternal,
			self::FUNC_MATERNAL => $maternal,
			self::FUNC_MARRIED => $married,
			self::FUNC_WIDOW => $widow,
			self::FUNC_EXCESS => $excess
		);
	}
	
	function setup() {
		$this->set_names('Sanchez Rivera', 'Sanchez', 'Rivera', '', false, '');
		$this->set_names('Ortega y Gasset', 'Ortega', 'Gasset', '', false, '');
		$this->set_names('Lopez de la Cruz', 'Lopez', 'de la Cruz', '', false, '');
		$this->set_names('Lerma i Blasco', 'Lerma', 'Blasco', '', false, '');
		$this->set_names('Lopez sra de Portillo', 'Lopez', '', 'Portillo', false, '');
		$this->set_names('Lopez sra. de Portillo', 'Lopez', '', 'Portillo', false, '');
		$this->set_names('Lopez Saenz vda. de Portillo', 'Lopez', 'Saenz', 'Portillo', true, '');
		$this->set_names(' Espinoza  de Rocha', 'Espinoza', 'de Rocha', '', false, '');
		$this->set_names('Rivera del Rosario', 'Rivera', 'del Rosario', '', false, '');
	}
	
	function teardown() {
		$this->obj = null;
		$this->names = null;
	}
	
	function test_construct_no_name() {
		try {
			$this->obj = new Papacito(null);
		} catch (Exception $e) {
			return;
		}
		$this->fail('An expected exception was not thrown');
	}
	
	function test_construct_non_string() {
		try {
			$this->obj = new Papacito(array('one', 'two', 'three'));
		} catch (Exception $e) {
			return;
		}
		$this->fail('An expected exception was not thrown.');
	}
	
	function test_construct_zero_length_string() {
		try {
			$this->obj = new Papacito('');
		} catch (Exception $e) {
			return;
		}
	}
	
	function test_get_raw_name() {
		$this->check_name(self::FUNC_RAW_NAME, 'The ' . self::FUNC_RAW_NAME . '() function did not return the same value passed to the constructor.');
	}

	function test_get_maternal_name() {
                $this->check_name(self::FUNC_MATERNAL, 'The ' . self::FUNC_MATERNAL . '() function did not return the expected value.');
	}

	function test_get_paternal_name() {
		$this->check_name(self::FUNC_PATERNAL, 'The ' . self::FUNC_PATERNAL . '() function did not return the expected value.');
	}

	function test_get_married_name() {
		$this->check_name(self::FUNC_MARRIED, 'The ' . self::FUNC_MARRIED . '() function did not return the expected value.');
	}

	function test_is_widow() {
                $this->check_name(self::FUNC_WIDOW, 'The ' . self::FUNC_WIDOW . '() function did not return the expected value.');
	}
	
	function test_get_excess() {
		$this->check_name(self::FUNC_EXCESS, 'The ' . self::FUNC_EXCESS . '() function did not return the expected value.');
	}
	
	private function check_name($function, $err_msg) {
		foreach ( $this->names as $name ) {
			$this->obj = new Papacito($name[self::FUNC_RAW_NAME]);
			$this->assertEquals($name[$function], $this->obj->$function(), $err_msg);
		}
			
	}
}
?>
