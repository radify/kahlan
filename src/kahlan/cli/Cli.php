<?php
/**
 * Kahlan: PHP Testing Framework
 *
 * @copyright     Copyright 2013, CrysaLEAD
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace kahlan\cli;

class Cli {

	/**
	 * ANSI/VT100 color/format sequences
	 *
	 * @var array
	 */
	protected static $_vt100 = [
		'colors' => [
			'black' => 30,
			'red' => 31,
			'green' => 32,
			'yellow' => 33,
			'blue' => 34,
			'magenta' => 35,
			'cyan' => 36,
			'white' => 37,
			'default' => 39
		],
		'formats' => [
			'n' => 0,   //normal
			'b' => 1,   //bold
			'd' => 2,   //dim
			'u' => 4,   //underline
			'r' => 7,   //reverse
			'h' => 7,   //hidden
			's' => 9    //strike
		]
	];

	/**
	 * The default color
	 *
	 * @var string
	 */
	protected static $_vtcolor = 'default';

	/**
	 * The default background color
	 *
	 * @var string
	 */
	protected static $_vtbackground = 'default';

	/**
	 * The default style
	 *
	 * @var string
	 */
	protected static $_vtstyle = 'default';

	/**
	 * Return the ANSI/VT100 number from a color name.
	 *
	 * @param  mixed $name A color name string or a ANSI/VT100 number
	 * @return integer a ANSI/VT100 number
	 */
	protected static function _vtcolor($name) {
		return static::_vt100($name);
	}

	/**
	 * Return the ANSI/VT100 number from a backgound color name.
	 *
	 * @param  mixed $name A backgound color name string or a ANSI/VT100 number
	 * @return integer a ANSI/VT100 number
	 */
	protected static function _vtbackground($name) {
		if (is_numeric($name)) {
			return $name + 10;
		}
		return static::_vtcolor($name) + 10;
	}

	/**
	 * Return a ANSI/VT100 number from a style name.
	 *
	 * @param  mixed $name A style name string or a ANSI/VT100 number
	 * @return integer a ANSI/VT100 number
	 */
	protected static function _vtstyle($name) {
		return isset(static::$_vt100['formats'][$name]) ? static::$_vt100['formats'][$name] : 0;
	}

	/**
	 * Return a ANSI/VT100 number from a color name.
	 *
	 * @param  mixed $name A color name string or a ANSI/VT100 number
	 * @return integer a ANSI/VT100 number
	 */
	protected static function _vt100($name) {
		if (is_numeric($name)) {
			return $name;
		}
		$value = 0;
		$items = explode(' ', $name);

		if (($name = array_shift($items)) === 'light') {
			$value += 100;
			$name = array_shift($items);
		}

		if (isset(static::$_vt100['colors'][$name])) {
			$value += static::$_vt100['colors'][$name];
		} else {
			$value = 39;
		}
		return $value;
	}

	public static function bell($count = 1) {
		echo str_repeat("\007", $count);
	}

	/**
	 * Return a VT100 colored string.
	 *
	 * @param mixed        $string  The string to color.
	 * @param string|array $options The possible values for an array are:
	 *                     - `'style`: a style code.
	 *                     - `'color'`: a color code.
	 *                     - `'background'`: a background color code.
	 *                     The string must respect one of the following format:
	 *                     - `'style;color;background'`
	 *                     - `'style;color'`
	 *                     - `'color'`
	 *
	 */
	public static function color($string, $options = null) {
		if ($options === null) {
			return $string;
		}

		if (is_string($options)) {
			$options = explode(';', $options);
			if (strlen($options[0]) === 1) {
				$options = array_pad($options, 3, 'default');
				$options = array_combine(['style', 'color', 'background'], $options);
			} else {
				$options = ['color' => reset($options)];
			}
		}

		$options += [
			'style' => 'default',
			'color' => 'default',
			'background' => 'default'
		];

		$format = "\e[";
		$format .= static::_vtstyle($options['style']) . ';';
		$format .= static::_vtcolor($options['color']) . ';';
		$format .= static::_vtbackground($options['background']) . 'm';

		return $format . $string . "\e[0m";
	}
}

?>