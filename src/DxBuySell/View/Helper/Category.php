<?php
namespace DxBuySell\View\Helper;

use DxBuySell\View\AbstractHelper;

class Category extends AbstractHelper
{
	/**
	 * The Doctrine Repo
	 * @var type 
	 */
	protected $repo = NULL;
	
	/**
	 * Selected Category
	 * @var object
	 */
	protected $category = NULL;
	
    public function __invoke()
    {
        return $this;
    }
	
	/**
	 * Set the Repo Object
	 * @param type $repo
	 * @return \DxBuySell\View\Helper\Category 
	 */
	public function setRepo($repo)
	{
		$this->repo = $repo;
		return $this;
	}
	
	/**
	 * Set the Selected CAtegory
	 * @param object $category
	 * @return \DxBuySell\View\Helper\Category 
	 */
	public function setCategory($category)
	{
		$this->category = $category;
		return $this;
	}
	
	/**
	 * Render breadcrumbs for this category 
	 */
	public function breadcrumbs()
	{
		foreach ($this->repo->getPath($this->category) as $p)
		{
			$crumb = array(
				'url' => '/forsale/' . $p['slug'],
				'title' => $p['title'],
				'anchor' => $p['title'],
			);
			$this->view->dxBreadcrumb()->add($p['slug'], $crumb);
		}
	}
	
	/**
	 * Render list of category
	 * @param object $category
	 * @return string 
	 */
	public function renderChildrenToList()
	{
		$decorator = array(
			'decorate' => true,
			'rootOpen' => '<ul class="category_list">',
			'rootClose' => '</ul>',
			'childOpen' => '<li>',
			'childClose' => '</li>',
			'nodeDecorator' => function($node)
			{
				$str = '';
		//		$str .= '<div>';
		//		$str .= '<div class="left btn-group">';
		//		$str .= '<a class="btn btn-small btn-info" href="#" title="Edit"><i class="icon-white icon-pencil"></i></a>';
		//		$str .= '<a class="btn btn-small btn-danger" href="#" title="Delete"><i class="icon-white icon-remove"></i></a>';
		//		$str .= '<a class="btn btn-small btn-success" href="#" title="Move"><i class="icon-white icon-move"></i></a>';
		//		$str .= '<a class="btn btn-small btn-warning" href="#" title="Move Down"><i class="icon-white icon-arrow-down"></i></a>';
		//		$str .= '<a class="btn btn-small btn-warning" href="#" title="Move Down"><i class="icon-white icon-arrow-down"></i></a>';
		//		$str .= '</div>';
		//		$str .= '<div class="left">&nbsp; ';
				$str .= '<a href="/forsale/' . $node['slug'] . '">' . $node['title'] . '</a>';
		//		$str .= '</div>';
		//		$str .= '<div class="clearfix"></div>';
		//		$str .= '</div>';
				return $str;
			}
		);
		return $this->repo->childrenHierarchy($this->category, TRUE, $decorator);
	}
}
