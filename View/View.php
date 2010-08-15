<?php

abstract class View_View
{
	private $buffer = NULL;

	protected function setBuffer($b)
	{
		$this->buffer = $b;
	}

	public function __toString()
	{
		return $this->buffer;
	}
}

?>
