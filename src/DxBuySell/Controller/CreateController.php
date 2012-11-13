<?php

namespace DxBuySell\Controller;

use DxBuySell\Controller\MainController;

class CreateController extends MainController
{

	public function indexAction()
	{
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
		$form = $this->getServiceLocator()->get('dxbuysell_form_item');
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
}