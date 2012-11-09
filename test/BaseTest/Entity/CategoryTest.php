<?php

namespace Dx\PHPUnit;

use DxBuySell\Entity\Category;
use Dx\PHPUnit\BaseTestCase;

class CategoryTest extends BaseTestCase
{

	/**
	 * The Category Tree
	 */
	protected $catTree = array();

	public function setup()
	{
		$this->entities = array(
			'DxBuySell\Entity\Category',
			'DxBuySell\Entity\Item'
		);
		parent::setup();
		$this->repo = $this->em->getRepository('DxBuySell\Entity\Category');
		$this->catTree = array(
			'items' => array(
				'title' => 'Items',
				'children' => array(
					'art' => array(
						'title' => 'Art'
					),
					'computers' => array(
						'title' => 'Computers'
					),
					'collectibles' => array(
						'title' => 'Collectibles',
						'children' => array(
							'antiques' => array(
								'title' => 'Antiques'
							),
							'cartoonCharacters' => array(
								'title' => 'Cartoon Characters'
							),
							'dolls' => array(
								'title' => 'Dolls',
							),
							'coins' => array(
								'title' => 'Coins',
								'children' => array(
									'asia' => array(
										'title' => 'Asia'
									),
									'australia' => array(
										'title' => 'Australia'
									),
									'britain' => array(
										'title' => 'Britain',
										'children' => array(
											'britainOne' => array(
												'title' => 'Britain One',
												'children' => array(
													'britainTwo' => array(
														'title' => 'Britain Two',
														'children' => array(
															'britainThree' => array(
																'title' => 'Britain Three'
															)
														)
													)
												)
											)
										)
									)
								)
							),
						),
					),
				)
			)
		);
	}

	/**
	 * Create the Tree Structure
	 * @param type $arr
	 * @param Category $cat 
	 */
	public function createTree($arr = NULL, $cat = NULL)
	{
		if ($cat === NULL)
		{
			$cat = new Category();
			$cat->setTitle($this->catTree['items']['title']);
			$this->em->persist($cat);
			$arr = $this->catTree['items']['children'];
		}
		foreach ($arr as $child)
		{
			$c = new Category();
			$c->setTitle($child['title']);
			$c->setParent($cat);
			$this->em->persist($c);
			if (isset($child['children']))
			{
				$this->createTree($child['children'], $c);
			}
		}
		$this->em->flush();
	}

	/**
	 * Test if a category can be found given a slug 
	 */
	public function testCategoryFoundBySlug()
	{
		$this->createTree();

		$britain = $this->repo->findOneBySlug('britain-three');
		$this->assertTrue($britain->getTitle() == 'Britain Three');
	}

	/**
	 * Test if a category can be found given an id 
	 */
	public function testCategoryFoundById()
	{
		$this->createTree();

		$britain = $this->repo->findOneById(14);
		$this->assertTrue($britain->getTitle() == 'Britain Three');
	}

	/**
	 * Test a category move up function in the same parent 
	 */
	public function testCategoryMoveUp()
	{
		$this->createTree();

		//Antiques, Cartoon Characters, Dolls, Coins
		//Current: level=2, lft=13, rgt=26
		$cat = $this->repo->findOneBySlug('coins');
		$this->assertEquals(13, $cat->getLeft());
		$this->assertEquals(26, $cat->getRight());
		$this->repo->moveUp($cat, 1);
		//Antiques, Cartoon Characters, Coins, Dolls
		//After: level=2, lft=11, rgt=24
		$this->assertEquals(11, $cat->getLeft());
		$this->assertEquals(24, $cat->getRight());
	}

	/**
	 * Test a category move down function in the same parent 
	 */
	public function testCategoryMoveDown()
	{
		$this->createTree();

		//Antiques, Cartoon Characters, Dolls, Coins
		//Current: level=2, lft=7, rgt=8
		$cat = $this->repo->findOneBySlug('antiques');
		$this->assertEquals(7, $cat->getLeft());
		$this->assertEquals(8, $cat->getRight());
		$this->repo->moveDown($cat, 2);

		//Cartoon Characters, Coins, Antiques, Dolls
		//After: level=2, lft=11, rgt=12
		$this->assertEquals(11, $cat->getLeft());
		$this->assertEquals(12, $cat->getRight());
	}

	/**
	 * Test a category move to other parent 
	 */
	public function testCategoryMoveToOtherParent()
	{
		$this->createTree();

		/**
		 * Currently Britain is a child of COINS
		 * Move to other parent like COMPUTERS 
		 */
		$britain = $this->repo->findOneBySlug('britain');
		$computers = $this->repo->findOneBySlug('computers');
		$britain->setParent($computers);
		$this->em->persist($britain);
		$this->em->flush();

		$path = $this->repo->getPath($britain);
		$this->assertTrue($path[0]['slug'] == 'items');
		$this->assertTrue($path[1]['slug'] == 'computers');
	}

	/**
	 * Test a category if has children 
	 */
	public function testCategoryIfHasChildren()
	{
		$this->createTree();

		$collectibles = $this->repo->findOneBySlug('collectibles');
		$this->assertEquals(4, $this->repo->childCount($collectibles, TRUE));
	}

	/**
	 * Test category if has no children 
	 */
	public function testCategoryIfNoChildren()
	{
		$this->createTree();

		$dolls = $this->repo->findOneBySlug('dolls');
		$this->assertEquals(0, $this->repo->childCount($dolls, TRUE));
	}

	/**
	 * test category if has a child with a given id 
	 */
	public function testCategoryIfHasChildrenById()
	{
		$this->createTree();

		$britainOne = $this->repo->findOneBySlug('britain-one');
		foreach ($this->repo->getChildren($britainOne, TRUE) as $child)
		{
			$this->assertEquals('britain-two', $child->getSlug());
		}
	}

	/**
	 * Test category if has no child with a given id 
	 */
	public function testCategoryIfHasNoChildrenById()
	{
		$this->createTree();

		$britainOne = $this->repo->findOneBySlug('britain-one');
		foreach ($this->repo->getChildren($britainOne, TRUE) as $child)
		{
			$this->assertFalse('britain-three' == $child->getSlug());
		}
	}

	/**
	 * Test category children count 
	 */
	public function testCategoryChildrenCount()
	{
		$this->createTree();
		$root = $this->repo->findOneById(1);
		$this->assertEquals(0, $root->getLevel());
		$this->assertEquals(1, $root->getLeft());
		$this->assertEquals(3, $this->repo->childCount($root, TRUE));
	}

	/**
	 * Test category path 
	 */
	public function testCategoryPath()
	{
		$this->createTree();
		$britain = $this->repo->findOneBySlug('britain-three');
		$path = $this->repo->getPath($britain);
		$this->assertTrue($path[0]['slug'] == 'items');
		$this->assertTrue($path[1]['slug'] == 'collectibles');
		$this->assertTrue($path[2]['slug'] == 'coins');
		$this->assertTrue($path[3]['slug'] == 'britain');
		$this->assertTrue($path[4]['slug'] == 'britain-one');
		$this->assertTrue($path[5]['slug'] == 'britain-two');
		$this->assertTrue($path[6]['slug'] == 'britain-three');
	}

	public function testCacheCreated()
	{
		$this->createTree();
		$britain = $this->repo->findOneBySlug('britain-three');
		$cacheDriver = $this->em->getConfiguration()->getResultCacheImpl();
		$cacheName = $this->repo->getCachePrefix() . 'findOneBySlug' . $britain->getSlug();
		$this->assertTrue($cacheDriver->contains($cacheName));
	}

	public function testCacheRemoved()
	{
		$this->createTree();
		$britain = $this->repo->findOneBySlug('britain-three');
		$cacheDriver = $this->em->getConfiguration()->getResultCacheImpl();
		$cacheName = $this->repo->getCachePrefix() . 'findOneBySlug' . $britain->getSlug();
		$this->assertTrue($cacheDriver->contains($cacheName));
		$cacheDriver->delete($cacheName);
		$this->assertFalse($cacheDriver->contains($cacheName));

		$britain = $this->repo->findOneById(14);
		$cacheDriver = $this->em->getConfiguration()->getResultCacheImpl();
		$cacheName = $this->repo->getCachePrefix() . $britain->getId() . 'findOneById';
		$this->assertTrue($cacheDriver->contains($cacheName));
		$cacheDriver->delete($cacheName);
		$this->assertFalse($cacheDriver->contains($cacheName));
	}

}