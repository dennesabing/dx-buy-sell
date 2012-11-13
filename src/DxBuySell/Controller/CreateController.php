<?php

namespace DxBuySell\Controller;

use DxBuySell\Controller\MainController;

class CreateController extends MainController
{

	/**
	 * List of steps
	 * @var array 
	 */
	protected $steps = array('section', 'category', 'details', 'credits', 'success');

	public function indexAction()
	{
		$this->layout('layout/2column-rightbar');
		$this->setStep('section');
		if ($this->getStep() == NULL || $this->getStep() == 'section')
		{
			$viewData = array();
			$entityType = $this->getEntityType();
			if (!empty($entityType))
			{
				$viewData['entityType'] = $entityType;
			}
			$validData = NULL;
			$form = $this->getServiceLocator()->get('dxbuysell_form_create_section');
			$formXmlFile = __DIR__ . $this->getOptions()->getPathToXmlForms() . 'sections.xml';
			$formXmlDisplayOptions = __DIR__ . $this->getOptions()->getPathToXmlForms() . 'sectionsDisplayOptions.xml';
			$formXml = \Dx\Reader\Xml::toArray($formXmlFile);
			$form->setXmlForm($formXml)->formFromXml();
			if (isset($formXml['form']['filters']))
			{
				$inputFilter = $this->getServiceLocator()->get('dxbuysell_form_create_section_filter');
				$inputFilter->setXml($formXml['form']['filters'])->filterFromXml();
				$form->setInputFilter($inputFilter);
			}
			$formDisplayOptions = \Dx\Reader\Xml::toArray($formXmlDisplayOptions);
			if ($this->request->isPost())
			{
				$form->setData($this->request->getPost());
				if ($form->isValid())
				{
					$formData = $form->getData();
					$this->setSection($formData['fsSection']['adSection']);
					$this->setStep('category');
				}
			}
			$viewData['form'] = $form;
			$viewData['formType'] = \DluTwBootstrap\Form\FormUtil::FORM_TYPE_VERTICAL;
			$viewData['validData'] = $validData;
			$viewData['formDisplayOptions'] = $formDisplayOptions['display'];
			return $this->getViewModel($viewData);
		}
	}

	public function categoryAction()
	{
		if ($this->getStep() == 'category' && $this->getSection())
		{
			$viewData = array();
			$section = $this->getSection();


			$this->setSection($section);
			$this->setStep(3);
			return $this->getViewModel($viewData);
		}
	}

	public function detailsAction()
	{
		if ($this->getStep() == 'details' && $this->getSection() && $this->getCategories())
		{
			$viewData = array();
			$section = $this->getSection();
			$categories = $this->getCategories();


			$this->setCategories($categories);
			$this->setSection($section);
			$this->setStep(3);
			return $this->getViewModel($viewData);
		}


		$viewData = array();
		$this->layout('layout/2column-rightbar');
		$categorySlug = $this->getCategorySlug();
		$entityType = $this->getEntityType();
		if (!empty($entityType))
		{
			$viewData['entityType'] = $entityType;
		}
		if (!empty($categorySlug))
		{
			$viewData['categorySlug'] = $categorySlug;
			$em = $this->getEntityManager();
			$categoryRepo = $em->getRepository('DxBuySell\Entity\Category');
			$category = $categoryRepo->findOneBySlug($categorySlug);
			if ($category)
			{
				$viewData['category'] = $category;
				$viewData['categoryRepo'] = $categoryRepo;
			}
		}
		else
		{
			
		}

		$pathToXmlForms = __DIR__ . '/../../../data/categoryxmlforms/';
		$formMainXmlFile = $pathToXmlForms . 'form.xml';
		$formXmlFile = \Dx\Reader\Xml::toArray($formMainXmlFile);
		if (isset($category))
		{
			$formXmlPrefix = $category->getSlug();
			if (\Dx\File::check($pathToXmlForms . $formXmlPrefix . '_form.xml'))
			{
				$formCategoryXmlFile = \Dx\Reader\Xml::toArray($pathToXmlForms . $formXmlPrefix . '_form.xml');
				$formXmlFile = \Dx\ArrayManager::merge($formCategoryXmlFile, $formXmlFile);
			}
			$viewData['formXmlDisplayOptions'] = $pathToXmlForms . $formXmlPrefix . '_formDisplayOptions.xml';
		}
		$form = $this->getServiceLocator()->get('dxbuysell_form_create_details');
		$form->setXmlForm($formXmlFile)->formFromXml();
		$inputFilter = new \DxBuySell\Form\ItemInputFilter();
		$form->setInputFilter($inputFilter);
		$validData = null;
		if ($this->request->isPost())
		{
			$form->setData($this->request->getPost());
			if ($form->isValid())
			{
				$formData = $form->getData();
				$validData = \Zend\Debug\Debug::dump($formData, 'Valid form data', false);
			}
		}
		$viewData['form'] = $form;
		$viewData['formType'] = \DluTwBootstrap\Form\FormUtil::FORM_TYPE_VERTICAL;
		$viewData['validData'] = $validData;
		return $this->getViewModel($viewData);
	}

	public function creditsAction()
	{
		
	}

	public function successAction()
	{
		
	}

	/**
	 * Set the current step;
	 * @param integer $step
	 * @return \DxBuySell\Controller\CreateController
	 */
	protected function setStep($step)
	{
		if (in_array($step, $this->steps))
		{
			$this->getSession()->offsetSet('step', $step);
		}
		return $this;
	}

	/**
	 * Return the current Step;
	 * @return type
	 */
	protected function getStep()
	{
		if ($this->getSession()->offsetExists('step'))
		{
			return $this->getSession()->offsetGet('step');
		}
	}

	/**
	 * The Section where to post the Ad
	 *
	 * return string
	 */
	protected function getSection()
	{
		if ($this->getSession()->offsetExists('section'))
		{
			$section = $this->getSession()->offsetGet('section');
			if ($this->getOptions()->checkSection($section))
			{
				return $section;
			}
		}
		return FALSE;
	}

	/**
	 * Set the section to where to post the ad
	 * @param type $section
	 */
	protected function setSection($section)
	{
		if ($this->getOptions()->checkSection($section))
		{
			$this->getSession()->offsetSet('section', $section);
		}
		return FALSE;
	}

	/**
	 * Set the selected categories
	 * @param type $categories
	 */
	protected function setCategories($categories)
	{
		if (!empty($categories))
		{
			$this->getSession()->offsetSet('categories', $categories);
		}
		return $this;
	}

	/**
	 * Get the selected Categories
	 * @return array
	 */
	protected function getCategories()
	{
		if ($this->getSession()->offsetExists('categories'))
		{
			return $this->getSession()->offsetGet('categories');
		}
		return FALSE;
	}

}