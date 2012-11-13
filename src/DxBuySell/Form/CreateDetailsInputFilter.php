<?php

namespace DxBuySell\Form;

use Zend\InputFilter\InputFilter;

class ItemInputFilter extends InputFilter
{

	public function __construct()
	{
		//Hidden
		$this->add(array(
			'name' => 'hiddenField',
			'required' => true,
		));
		//Fieldset 1 Input filter
		$fs1InputFilter = new InputFilter();
		//Text
		$fs1InputFilter->add(array(
			'name' => 'text',
			'required' => true,
			'validators' => array(
				array(
					'name' => 'string_length',
					'options' => array(
						'min' => 5,
					),
				),
			),
		));
		//Password
		$fs1InputFilter->add(array(
			'name' => 'password',
			'required' => true,
			'validators' => array(
				array(
					'name' => 'string_length',
					'options' => array(
						'min' => 5,
						'max' => 8,
					),
				),
			),
		));
		//Textarea
		$fs1InputFilter->add(array(
			'name' => 'textarea',
			'required' => false,
			'validators' => array(
				array(
					'name' => 'string_length',
					'options' => array(
						'min' => 5,
					),
				),
			),
		));
		$this->add($fs1InputFilter, 'fsOne');

		//Fieldset 2 Input filter
		$fs2InputFilter = new InputFilter();
		//Multicheckbox
		$fs2InputFilter->add(array(
			'name' => 'multiCheckbox',
			'required' => false,
		));
		//Multicheckbox inline
		$fs2InputFilter->add(array(
			'name' => 'multiCheckboxInline',
			'required' => false,
		));
		$this->add($fs2InputFilter, 'fsTwo');

		//Elements outside of any fieldsets (i.e. directly on the form)
		//Select
		$this->add(array(
			'name' => 'select',
			'required' => true,
		));
		//Multi Select
		$this->add(array(
			'name' => 'multiSelect',
			'required' => true,
		));
		//File
		$this->add(array(
			'name' => 'file',
			'required' => false,
			'validators' => array(
				array(
					'name' => 'string_length',
					'options' => array(
						'max' => 0,
					),
				),
			),
		));
		//Text append / prepend
		$this->add(array(
			'name' => 'textAp',
			'required' => false,
		));
		//Icon append / prepend
		$this->add(array(
			'name' => 'iconAp',
			'required' => false,
		));
	}

}

