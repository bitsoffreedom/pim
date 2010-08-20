<?php

class IntegerForm
{
	private $integers;

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

	public function getIntegers()
	{
		return $this->integers;
	}
}

?>
