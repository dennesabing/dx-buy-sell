<?php

namespace DxBuySell\Form;

use Dx\Form;
use Zend\Form\Element;

class CreateSection extends Form
{
	/**
	 * Constructor
	 */
	public function __construct($formName = NULL, $xmlFile = NULL, $moduleOptions = array())
	{
		parent::__construct();
		$this->setName($formName);
		$this->setXmlForm($xmlFile);
		$this->setModuleOptions($moduleOptions);
	}
}