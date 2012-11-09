<?php

namespace DxBuySell\Controller;

use DxBuySell\Controller\MainController;

class ItemController extends MainController
{

	public function indexAction()
	{
		return $this->getViewModel(array(
					'entity_type' => $this->getEntityType(),
					'item_slug' => $this->getItemSlug(),
						)
		);
	}
}