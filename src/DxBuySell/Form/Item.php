<?php

namespace DxBuySell\Form;

use Dx\Form;
use Zend\Form\Element;

class Item extends Form
{
	/**
	 * Constructor
	 * @param object $category The Category
	 */
	public function __construct($xmlFile = NULL)
	{
		parent::__construct();
		$this->setName('itemform');
		$this->setAttribute('method', 'post');
		$this->formFromXml($xmlFile);

		//Hidden
		$this->add(array(
			'name' => 'hiddenField',
			'type' => 'Zend\Form\Element\Hidden',
			'attributes' => array(
				'value' => 'myHiddenValue',
			),
		));

		//Fieldset One
		$this->add(array(
			'name' => 'fsOne',
			'type' => 'Zend\Form\Fieldset',
			'options' => array(
				'legend' => 'Legend for Fieldset 1',
			),
			'elements' => array(
				//Text
				array(
					'spec' => array(
						'name' => 'text',
						'type' => 'Zend\Form\Element\Text',
						'attributes' => array(
							'placeholder' => 'Placeholder',
						),
						'options' => array(
							'label' => 'Text',
							'hint' => 'Hint',
							'description' => 'Description.',
						),
					),
				),
				//Password
				array(
					'spec' => array(
						'name' => 'password',
						'type' => 'Zend\Form\Element\Password',
						'attributes' => array(
							'placeholder' => 'Placeholder',
						),
						'options' => array(
							'label' => 'Password',
							'hint' => 'Hint',
							'description' => 'Description.',
						//'prependText'   => 'Prepend text',
						//'appendText'    => 'Append text',
						),
					),
				),
				//Textarea
				array(
					'spec' => array(
						'name' => 'textarea',
						'type' => 'Zend\Form\Element\Textarea',
						'attributes' => array(
							'placeholder' => 'Placeholder',
						),
						'options' => array(
							'label' => 'Textarea',
							'hint' => 'Hint',
							'description' => 'Description.',
						),
					),
				),
			),
		));

		//Fieldset Two
		$this->add(array(
			'name' => 'fsTwo',
			'type' => 'Zend\Form\Fieldset',
			'options' => array(
				'legend' => 'Legend for Fieldset 2',
			),
			'elements' => array(
				//Checkbox
				array(
					'spec' => array(
						'name' => 'checkbox',
						'type' => 'Zend\Form\Element\Checkbox',
						'options' => array(
							'label' => 'Checkbox',
							'hint' => 'Hint',
							'description' => 'Description.',
						),
					),
				),
				//Radio
				array(
					'spec' => array(
						'name' => 'radio',
						'type' => 'Zend\Form\Element\Radio',
						'options' => array(
							'label' => 'Radio',
							'description' => 'Description.',
							'value_options' => array(
								'fm' => 'FM',
								'am' => 'AM',
								'dig' => 'Digital',
							),
						),
					),
				),
				//Radio inline
				array(
					'spec' => array(
						'name' => 'radioInline',
						'type' => 'Zend\Form\Element\Radio',
						'options' => array(
							'label' => 'Radio Inline',
							'description' => 'Description.',
							'value_options' => array(
								'a' => 'A',
								'b' => 'B',
								'c' => 'C',
								'd' => 'D',
								'e' => 'E',
								'f' => 'F',
							),
						),
					),
				),
				//Multicheckbox
				array(
					'spec' => array(
						'name' => 'multiCheckbox',
						'type' => 'Zend\Form\Element\MultiCheckbox',
						'options' => array(
							'label' => 'Multi Checkbox',
							'description' => 'Description.',
							'value_options' => array(
								'mon' => 'Monday',
								'tue' => 'Tuesday',
								'wed' => 'Wednesday',
								'thu' => 'Thursday',
								'fri' => 'Friday',
								'sat' => 'Saturday',
								'sun' => 'Sunday',
							),
						),
					),
				),
				//Multicheckbox inline
				array(
					'spec' => array(
						'name' => 'multiCheckboxInline',
						'type' => 'Zend\Form\Element\MultiCheckbox',
						'options' => array(
							'label' => 'Multi Checkbox Inline',
							'description' => 'Description.',
							'value_options' => array(
								'spring' => 'Spring',
								'summer' => 'Summer',
								'autumn' => 'Autumn',
								'winter' => 'Winter',
							),
						),
					),
				),
				)));

		//Select
//		$this->add(array(
//			'name' => 'select',
//			'type' => 'Zend\Form\Element\Select',
//			'options' => array(
//				'label' => 'Select',
//				'hint' => 'Hint',
//				'description' => 'Description.',
//				'value_options' => array(
//					'alpha' => 'Alpha',
//					'beta' => 'Beta',
//					'gamma' => 'Gamma',
//					'delta' => 'Delta',
//				),
//			),
//		));

		//Multiselect
		$this->add(array(
			'name' => 'multiSelect',
			'type' => 'Zend\Form\Element\Select',
			'attributes' => array(
				'multiple' => true,
			),
			'options' => array(
				'label' => 'Multi Select',
				'hint' => 'Hint',
				'description' => 'Description.',
				'value_options' => array(
					'white' => 'White',
					'red' => 'Red',
					'black' => 'Black',
					'blue' => 'Blue',
					'green' => 'Green',
					'yellow' => 'Yellow',
				),
			),
		));

		//File
		$this->add(array(
			'name' => 'file',
			'type' => 'Zend\Form\Element\File',
			'options' => array(
				'label' => 'File',
				'hint' => 'Hint',
				'description' => 'Description. (Note: File upload not allowed in this demo!)',
			),
		));

		//Csrf
		$this->add(new Element\Csrf('csrf'));

		//Submit button
		$this->add(array(
			'name' => 'submitBtn',
			'type' => 'Zend\Form\Element\Submit',
			'attributes' => array(
				'value' => 'Submit',
			),
			'options' => array(
				'primary' => true,
			),
		));
	}

}