<?php
/**
 * Helper: Strings
 *
 * Add functionality for manipulating, validating and processing strings
 */
class JMSA_Helper_Strings
{

	/**
	 * Escape a string to be used as an ID attribute.
	 *
	 * If the first character is an incorrect ID attribute character, the string will be prefixed by an underscore
	 * Every occurence of an invalid ID attribute character will be replaced by the $replace character, which
	 * should of course be a valid ID attribute character (this is, however, not validated!).
	 *
	 * @param string $string String to escape
	 * @param string $replace String to replace any invalid characters
	 * @return string Escaped string
	 */
	public static function esc_attr_id($string, $replace = '_')
	{
		if (!preg_match('/[a-z]/i', substr($string, 0, 1))) {
			$string = '_' . $string;
		}
		
		$string = preg_replace('/[^a-z0-9_\-]/i', $replace, $string);
		
		return $string;
	}

}
?>