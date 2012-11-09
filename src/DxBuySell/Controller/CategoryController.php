<?php

namespace DxBuySell\Controller;

use DxBuySell\Controller\MainController;
use DxBuySell\Entity\Categoryb;

class CategoryController extends MainController
{

	public function indexAction()
	{
		$this->layout('layout/3column');
		$em = $this->getEntityManager();
		$categoryRepo = $em->getRepository('DxBuySell\Entity\Category');
		$category = $categoryRepo->findOneById(1);
		$items = NULL;
		return $this->getViewModel(array(
					'entityType' => $this->getEntityType(),
					'categoryRepo' => $categoryRepo,
					'category' => $category,
					'items' => $items,
						)
		);
	}

	public function categoryAction()
	{
		$this->layout('layout/3column');
		$em = $this->getEntityManager();
		$categoryRepo = $em->getRepository('DxBuySell\Entity\Category');
		$category = $categoryRepo->findOneBySlug($this->getCategorySlug());
		$items = NULL;
		return $this->getViewModel(array(
					'entityType' => $this->getEntityType(),
					'categoryRepo' => $categoryRepo,
					'category' => $category,
					'items' => $items
						)
		);
	}
}