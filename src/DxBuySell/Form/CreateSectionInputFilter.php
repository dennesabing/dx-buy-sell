<?php

namespace DxBuySell\Form;

use Dx\Form\InputFilter;

class CreateSectionInputFilter extends InputFilter
{

	public function __construct($xmlFile, $moduleOptions = NULL)
	{
		$this->setXml($xmlFile);
		$this->setModuleOptions($moduleOptions);
	}

}

