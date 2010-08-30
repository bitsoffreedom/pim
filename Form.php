<?php

class IntegerForm
{
	private $integers;

	// @param string name
	public function __construct($name)
	{
		$this->integers = Array();

		if (!is_array($_POST) || !array_key_exists($name, $_POST)
				|| !is_array($_POST[$name]))
			return;

		foreach ($_POST[$name] as $value) {
			if (!ctype_digit($value)) {
				$this->integers = FALSE;
				throw new Exception('Wrong POST data received');
			}
			$this->integers[] = intval($value);
		}
	}

	// @return array w/ integers
	public function getIntegers()
	{
		return $this->integers;
	}
}

class StringForm
{
	private $string;

	// @param string name
	public function __construct($name)
	{
		if (!is_array($_POST) || !array_key_exists($name, $_POST))
			return;

		if ($_POST[$name] != "" && !ctype_alnum($_POST[$name]))
				throw new Exception('Wrong POST data received');

		$this->string = $_POST[$name];
	}

	// @return string
	public function getString()
	{
		return $this->string;
	}
}

class KeyExists
{
	private $key;

	// @param "array w/ strings" buttons
	public function __construct($keys)
	{
		if (!is_array($_POST))
			return;

		foreach ($keys as $k) {
			if (array_key_exists($k, $_POST)) {
				$this->key = $k;
				return;
			}
		}
	}

	// @return string
	public function getKey()
	{
		return $this->key;
	}
}

?>
