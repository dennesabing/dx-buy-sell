<?php

namespace DxBuySell\Controller;

use Dx\Mvc\Controller\FrontendController;

class MainController extends FrontendController
{
	
	protected $modulePrefix = 'dxbuysell';
	
	/**
	 * The URL Query name of the selecte item
	 * @var string
	 */
	public $paramNameItem = 'item_slug';
	
	/**
	 * The URL Query name of the selected category
	 * @var string
	 */
	public $paramNameCategory = 'category_slug';
	
	/**
	 * The URL Query name of the current page
	 * @var int
	 */
	public $paramNamePage = 'page';
	
	/**
	 * The URL Query name of the sorting extra options
	 * @var string
	 */
	public $paramNameSorting = 'sorting';
	
	/**
	 * The URL Query name of the current entity
	 * @var string
	 */
	public $paramNameEntityType = 'entity_type';
	
	/**
	 * Get the Entity Type of the current request. e.g buy or sell
	 * @return string|bool
	 */
	protected function getEntityType()
	{
		return $this->getEvent()->getRouteMatch()->getParam($this->paramNameEntityType);
	}
	
	/**
	 * Get the Category Slug or Category Id of the request
	 * @return string|bool
	 */
	protected function getCategorySlug()
	{
		return $this->getEvent()->getRouteMatch()->getParam($this->paramNameCategory);
	}
	
	/**
	 * Get the Current Page
	 * @return int
	 */
	protected function getCurrentPage()
	{
		$page = (int) $this->getEvent()->getRouteMatch()->getParam($this->paramNamePage);
		if(!$page)
		{
			return 0;
		}
	}
	
	/**
	 * Get the Current Page sorting
	 * @return string
	 */
	protected function getCurrentSorting()
	{
		$sorting = $this->getEvent()->getRouteMatch()->getParam($this->paramNameSorting);
		if(!$sorting)
		{
			return $this->getDefaultSorting();
		}
	}
	
	/**
	 * Convert sorting data to array
	 * @return array 
	 */
	protected function getSortingToArray()
	{
		$sortArr = array();
		$sorting = $this->getCurrentSorting();
		if($sorting)
		{
			$sorting = explode('-', $sorting);
			'layout-maxrows-date-desc';
			$sortArr = array(
				'layout' => $sorting[0],
				'maxrows' => $sorting[1],
				'orderby' => $sorting[2],
				'direction' => $sorting[3],
			);
		}
		return $sortArr;
	}
	
	/**
	 * Get the current maxrows
	 * @return type 
	 */
	protected function getCurrentMaxRows()
	{
		$maxrows = $this->getEvent()->getRouteMatch()->getParam('maxrows');
		if(!$maxrows)
		{
			return $this->getCategoryItemsDefaultMaxRows();
		}
	}
	
	/**
	 * Get the Current Item Layout
	 * @return type 
	 */
	protected function getCurrentLayout()
	{
		$layout = $this->getEvent()->getRouteMatch()->getParam('layout');
		if(!$layout)
		{
			return $this->getCategoryItemsDefaultLayout();
		}
	}
	
	
	/**
	 * Get the default sorting layout-maxrows-field-direction
	 * @return string 
	 */
	protected function getDefaultSorting()
	{
		return 'layout-maxrows-date-desc';
	}
	
	/**
	 * Get the Default number of items to return
	 * @return int 
	 */
	protected function getCategoryItemsDefaultMaxRows()
	{
		return 15;
	}
	
	/**
	 * Get the default item layout. grid or rows
	 * @return string
	 */
	protected function getCategoryItemsDefaultLayout()
	{
		return 'rows';
	}
}
