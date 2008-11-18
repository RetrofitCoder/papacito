<?
/**
 * Class to parse Spanish surnames according to the information found on wikipedia (http://en.wikipedia.org/wiki/Spanish_naming_customs).
 * 
 * @version 0.8
 */
class papacito {
	// Internal variables
	private $raw_name = '';
	private $maternal = '';
	private $paternal = '';
	private $married = '';
	private $is_widow = false;
	private $excess = '';
	
	// Array of what characters will be removed from the last name prior to tokenizing.
	private $replace_chars = array('.', ',');
	
	// Class constant of the characters that delimit the tokens in the last name.
	const TOKEN_CHARS = " \n\t";
	
	/**
	 * Constructor.  The full last name is passed as a string argument.  The internal parser is 
	 * called on instantiation, so the object is configured with the name fields set after construction.
	 * 
	 * @params string raw_last_name The full last name that will be parsed.
	 * @throws Exception if the parameter is not a string, is only whitespace, or is a zero length string.
	 */
	function __construct($raw_last_name) {
		// Check that the right data type was passed.
		if ( false == is_string($raw_last_name) ) {
			throw new Exception(__METHOD__ . ' called with non-string argument.');
		}
		
		// Trim up the value of the last name
		$raw_last_name = trim($raw_last_name);
		if ( 0 == strlen($raw_last_name ) ) {
			throw new Exception(__METHOD__ . ' called with zero length value for the last name');
		}
		
		// Assign the string to the internal variable for the raw name
		$this->raw_name = $raw_last_name;
		
		// Call the internal parser
		$this->_parse();
	}
	
	/**
	 * Internal function for parsing the last name.
	 */
	private function _parse() {
		// Set up an array of the properties that tokens of the full last name could be assigned to.
		$fields = array('paternal', 'maternal', 'married');
		
		// Internal pointer of where we are in the $fields array.
		$pointer = 0;
		
		// Variable for the max number used as an index for the $fields array
		$max_index = count($fields) - 1;
		
		// Clean up the raw last name with any characters that are unwanted.
		$search_string = str_replace($this->replace_chars, '', $this->raw_name);
		
		// Tokenize the last name
		$token = strtok($search_string, self::TOKEN_CHARS);
		while ( false !== $token ) {
			// Check that we have not run past the end of the fields array.
			if ( $pointer > $max_index ) { 
				$pointer = $max_index;
			}
			
			// Set which property will be written to based on the internal pointer
			$property = $fields[$pointer];
			
			// Switch on the token for what we want to do.
			switch ( strtoupper($token) ) {
				case 'Y':
				case 'I':
					// The 'y' conjunction is a separator between the paternal and maternal names.
					// In Catalan-Valencian names, the 'i' conjuction serves the same purpose.
					// Set the pointer to the maternal name
					$pointer = array_search('maternal', $fields);
					break;
				case 'DE':
					// 'de' can take a number of forms. It could be for a regional placename, 
					// or for indication of the married name.  When used as a particle at the 
					// beginning of the married name, it can be ignored.
					if ( array_search('married', $fields) != $pointer || '' != $this->$property ) {
						$this->$property .= $token . ' ';
					}
					break;
				case 'LA':
				case 'DEL':
					// Add these particles to the current property field with a space,
					// but do not increment the pointer to the next property.
					$this->$property .= $token . ' ';
					break;
				case 'V':
				case 'VDA':
				case 'VIUDA':
					// This is the abbreviation for widow.  Set the widow property
					$this->is_widow = true;
					
					// The next chunk of the search string is the married name.
					// Fall through to the SRA block for handing that.
				case 'SRA':
					// This is the abbreviation for Senora, which is an indicator that the married name is next.
					$pointer = array_search('married', $fields);
					
					// Do nothing else and get the next token
					break;
				default:
					$this->$property .= $token;
					$pointer++;
					break;
			}
			
			// Get the next token
			$token = strtok(self::TOKEN_CHARS);
		}
	}
	
	// Public functions for getting values from the private variables.
	/**
	 * Function to return the raw last name passed to the constructor at instantiation.
	 *
	 * @return string raw_last_name
	 */
	public function get_raw_name() {
		return $this->raw_name;
	}
	
	/**
	 * Function to return the maternal name.
	 *
	 * @return string maternal_name
	 */
	public function get_maternal() {
		return $this->maternal;
	}
	
	/**
	 * Function to return the paternal name.
	 *
	 * @return string paternal_name
	 */	
	public function get_paternal() {
		return $this->paternal;
	}
	
	/**
	 * Function to return the married name.
	 *
	 * @return string married_name
	 */	
	public function get_married() {
		return $this->married;
	}
	
	/**
	 * Function to return a flag if the name indicates this is a widow.
	 *
	 * @return bool is_widow
	 */
	public function is_widow() {
		return $this->is_widow;
	}
	
	/**
	 * Function to return the excess name tokens remaining at the end of parsing.
	 *
	 * @return string excess_name
	 */	
	public function get_excess() {
		return $this->excess;
	}
}
?>

