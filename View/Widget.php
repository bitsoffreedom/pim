<?php

abstract class Widget
{
	// $var string
	private $buffer = NULL;
	// $var string
	private $viewfile;

	abstract protected function render();

	// @param string $file
	protected function setViewFile($file)
	{
		$this->viewfile = $file;
	}

	/**
         * Renders a view file.
         * This method includes the view file as a PHP script
         * and captures the display result if required.
         * @param array data to be extracted and made available to the view file
         */
        protected function renderInternal($_data_=null)
        {
                // we use special variable names here to avoid conflict when extracting data
                if (is_array($_data_))
                        extract($_data_,EXTR_PREFIX_SAME,'data');
                else
                        $data=$_data_;

		ob_start();
		ob_implicit_flush(false);
		/* XXX: perhaps replace this with include and throw an exception? */
		require($this->viewfile);
		$this->buffer = ob_get_clean();
        }

	public function __toString()
	{
		return $this->buffer;
	}
}

class TopWidget extends Widget
{
	// @var string
	private $body;

	public function __construct()
	{
		$this->setViewFile("top.php");
	}

	// @param string
	public function setBody($body)
	{
		$this->body = $body;
	}

	public function render()
	{
		$this->renderInternal(Array("body" => $this->body));
	}
}

class SelectSectorWidget extends Widget
{
	// @var array
	private $category_list;
	// @var string
	private $errormsg;

	public function __construct()
	{
		$this->setViewFile("sector.php");
	}

	// @param array
	public function setCategoryList($c)
	{
		$this->category_list = $c;
	}

	// @param string
	public function setErrorMsg($m)
	{
		$this->errormsg = $m;
	}

	public function render()
	{
		$this->renderInternal(
			Array(
			"categorylist" => $this->category_list,
			"errormsg" => $this->errormsg
			));
	}
}

class CompanyWidget extends Widget
{
	public function __construct()
	{
		$this->setViewFile("company.php");
	}

	public function render()
	{
		$this->renderInternal();
	}
}

?>
