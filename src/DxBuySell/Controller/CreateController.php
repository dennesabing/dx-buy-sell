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

		$form = new \DxBuySell\Form\Item();
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

	protected function formGeneral($form, $inputFilter, $formFile, $inputFilterFile, $viewScriptFile, $formTemplate, $formType, $pageHeading)
	{
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
		//Source code
		$moduleDir = realpath(__DIR__ . '/../../../');
		$formSource = file_get_contents($moduleDir . $formFile);
		$inputFilterSource = file_get_contents($moduleDir . $inputFilterFile);
		$viewScriptSource = file_get_contents($moduleDir . $viewScriptFile);
		$viewModelForm = new ViewModel();
		$viewModelForm->setVariables(array(
			'form' => $form,
			'formType' => $formType,
			'validData' => $validData,
		));
		$viewModelForm->setTemplate($formTemplate);
		$viewModel = $this->getViewModel();
		$viewModel->setVariables(array(
			'pageHeading' => $pageHeading,
			'formFile' => $formFile,
			'formSource' => $formSource,
			'inputFilterFile' => $inputFilterFile,
			'inputFilterSource' => $inputFilterSource,
			'viewScriptFile' => $viewScriptFile,
			'viewScriptSource' => $viewScriptSource,
		));
		$viewModel->addChild($viewModelForm, 'formOutput');
		return $viewModel;
	}

}