<?php

namespace DxBuySell\Form;

use Dx\Form;
use Zend\Form\Element;

class CreateDetails extends Form
{
	/**
	 * Constructor
	 * @param string|array $xmlFile array or filename of the xmlFile
	 */
	public function __construct($formName = NULL, $xmlFile = NULL, $moduleOptions = array())
	{
		parent::__construct();
		$this->setName($formName);
		$this->setXmlForm($xmlFile);
		$this->setModuleOptions($moduleOptions);
	}
}