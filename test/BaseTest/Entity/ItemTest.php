<?php

namespace Dx\PHPUnit;

use Dx\PHPUnit\BaseTestCase;
use DxBuySell\Entity\Item;
use DxBuySell\Entity\Category;
use DxUser\Entity\User;
use Zend\Crypt\Password\Bcrypt;

class ItemTest extends BaseTestCase
{

	/**
	 * The Category Tree
	 */
	protected $catTree = array();
	
	protected $repoCat = NULL;
	
	protected $repoItem = NULL;
	
	protected $repoUser = NULL;

	public function setup()
	{
		$this->entities = array(
			'DxBuySell\Entity\Category',
			'DxBuySell\Entity\Item',
			'DxUser\Entity\User'
		);
		parent::setup();
		$this->repoCat = $this->em->getRepository('DxBuySell\Entity\Category');
		$this->repoItem = $this->em->getRepository('DxBuySell\Entity\Item');
		$this->repoUser = $this->em->getRepository('DxUser\Entity\User');
		$this->catTree = array(
			'items' => array(
				'title' => 'Items',
				'children' => array(
					'smartphone' => array(
						'title' => 'Smart Phones',
						'children' => array(
							'apple' => array(
								'title' => 'Apple'
							),
							'samsung' => array(
								'title' => 'Samsung'
							),
						)
					),
					'mobilephone' => array(
						'title' => 'Mobile Phone',
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
	
	public function hashPassword($password)
	{
		$zfUserOption = $this->getServiceManager()->get('zfcuser_module_options');
		$bcrypt = new Bcrypt;
		$bcrypt->setCost($zfUserOption->getPasswordCost());
		$pass = $bcrypt->create($password);
		return $pass;
	}

	public function getUserJuan()
	{
		$u = new User();
		$u->setEmail('juan@amigoas.com');
		$u->setUsername($u->getEmail());
		$u->setDisplayName('Juan Tamad');
		$u->setPassword($this->hashPassword('abc123'));
		return $u;
	}

	public function getUserPedro()
	{
		$u = new User();
		$u->setEmail('pedro@amigoas.com');
		$u->setUsername($u->getEmail());
		$u->setDisplayName('Pedro Penduko');
		$u->setPassword($this->hashPassword('abc123'));
		return $u;
	}

	public function getNokiaPhone()
	{
		$i = new Item();
		$i->setTitle('Nokia Phone For Sale');
		return $i;
	}

	public function getSamsungPhone()
	{
		$i = new Item();
		$i->setTitle('Samsung SIII For Sale');
		return $i;
	}

	public function getAppleIPhone()
	{
		$i = new Item();
		$i->setTitle('iPhone 5 For Sale');
		return $i;
	}

	public function getMacBookPro()
	{
		$i = new Item();
		$i->setTitle('MacBook Pro For Sale');
		return $i;
	}

	public function getDonaldDuckToy()
	{
		$i = new Item();
		$i->setTitle('Donald Duck Toy For Sale');
		return $i;
	}

	public function getMickeyMouseToy()
	{
		$i = new Item();
		$i->setTitle('Mickey Mouse Toy For Sale');
		return $i;
	}

	public function getBarbieDoll()
	{
		$i = new Item();
		$i->setTitle('Barbie Doll For Sale');
		return $i;
	}

	public function getDaVinciPainting()
	{
		$i = new Item();
		$i->setTitle('Da Vinci Painting For Sale');
		return $i;
	}

	public function testAddMultipleItemsToCategory()
	{
		$this->createTree();
		$cartoonCharacters = $this->repoCat->findOneBySlug('cartoon-characters');
		$juan = $this->getUserJuan();
		$pedro = $this->getUserPedro();
		$donaldDuck = $this->getDonaldDuckToy();
		$donaldDuck->setUser($juan);
		$mickeyMouse = $this->getMickeyMouseToy();
		$mickeyMouse->setUser($pedro);
		$barbieDoll = $this->getBarbieDoll();
		$barbieDoll->setUser($pedro);
		$davinciPainting = $this->getDaVinciPainting();
		$davinciPainting->setUser($pedro);
		
		$donaldDuck->addCategory($cartoonCharacters);
		$mickeyMouse->addCategory($cartoonCharacters);
		$barbieDoll->addCategory($cartoonCharacters);
		$davinciPainting->addCategory($cartoonCharacters);
		
		$this->em->persist($juan);
		$this->em->persist($pedro);
		$this->em->persist($donaldDuck);
		$this->em->persist($barbieDoll);
		$this->em->persist($davinciPainting);
		$this->em->persist($mickeyMouse);
		$this->em->persist($cartoonCharacters);
		$this->em->flush();

		$cartoonCharacters = $this->repoCat->findOneBySlug('cartoon-characters');
		$mickeyMouse = $this->repoItem->findOneBySlug('mickey-mouse-toy-for-sale');
		$donaldDuck = $this->repoItem->findOneBySlug('donald-duck-toy-for-sale');
		$pedro = $this->repoUser->findByUsername('pedro@amigoas.com');
		$juan = $this->repoUser->findByUsername('juan@amigoas.com');
		
		$this->assertEquals($pedro->getEmail(), $mickeyMouse->getUser()->getEmail());
		$this->assertEquals($juan->getEmail(), $donaldDuck->getUser()->getEmail());
		
		$option = array(
			'page' => 1,
			'rowsPerPage' => 1,
			'sortField' => 'title',
			'sortDirection' => 'ASC'
		);
		$items = $this->repoCat->fetchItems($cartoonCharacters, $option);
		$this->assertEquals(4, $items->getTotalItemCount());
		$this->assertEquals(1, $items->getCurrentItemCount());
		$this->assertEquals(1, $items->getCurrentPageNumber());
		foreach($items as $i)
		{
			$this->assertEquals($barbieDoll->getTitle(), $i->getTitle());
		}
//		$itemTwo = $items->getItem(1);
//		echo "\n" . $itemTwo->getTitle();
//		$this->assertEquals($davinciPainting->getTitle(), $itemTwo->getTitle());
//		$itemTwo = $items->getItem(2);
//		$this->assertEquals($donaldDuck->getTitle(), $itemTwo->getTitle());
	}
//
//	public function testAddItemToMultipleCategories()
//	{
//		$this->createTree();
//		$categoryOne = $this->repoCat->findOneBySlug('apple');
//		$categoryTwo = $this->repoCat->findOneBySlug('mobile-phone');
//		$juan = $this->getUserJuan();
//		$itemOne = $this->getSamsungPhone();
//		$itemOne->setUser($juan);
//		$itemOne->addCategory($categoryOne);
//		$itemOne->addCategory($categoryTwo);
//
//		$this->em->persist($categoryOne);
//		$this->em->persist($categoryTwo);
//		$this->em->persist($itemOne);
//		$this->em->persist($juan);
//		$this->em->flush();
//
//		$categoryOne = $this->repoCat->findOneBySlug('apple');
//		$this->repoCat->fetchItems($categoryOne);
//		
//		
//		$itemOne = $this->repoItem->findOneBySlug('samsung-siii-for-sale');
//		$categories = $itemOne->getCategories();
//		$this->assertEquals(2, count($categories));
//		$this->assertEquals('Mobile Phone', $categories[1]->getTitle());
//		$this->assertEquals('Apple', $categories[0]->getTitle());
//
//	}

}