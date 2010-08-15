<?php
namespace PIM;

abstract class View_Widget
{
	private $buffer = NULL;
	private $viewfile;

	abstract protected function render();

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

?>
