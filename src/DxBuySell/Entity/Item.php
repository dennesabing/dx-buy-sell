<?php

namespace DxBuySell\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="buysell_items")
 * @ORM\Entity(repositoryClass="DxBuySell\Entity\Repository\Item")
 * @ORM\HasLifecycleCallbacks
 */
class Item
{

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 */
	private $id;

	/**
	 * @ORM\Column(length=255)
	 */
	private $title;

	/**
	 * @ORM\Column(name="is_displayed" ,type="integer")
	 */
	private $isEnabled;

	/**
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(type="datetime")
	 */
	private $created;

	/**
	 * @Gedmo\Timestampable(on="update")
	 * @ORM\Column(type="datetime")
	 */
	private $updated;

	/**
	 * @ORM\ManyToMany(targetEntity="DxBuySell\Entity\Category", inversedBy="items", cascade={"persist","remove"}, fetch="EXTRA_LAZY")
	 * @ORM\JoinTable(name="buysell_category_items",
	 *   joinColumns={@ORM\JoinColumn(name="item_id", referencedColumnName="id")},
	 *   inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")})
	 */
	private $categories;

	/**
	 * @ORM\ManyToOne(targetEntity="DxUser\Entity\User", inversedBy="buySellItems")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
	 */
	private $user;
	
	/**
	 * @Gedmo\Slug(fields={"title"})
	 * @ORM\Column(length=255, unique=true)
	 */
	private $slug;

	public function __construct()
	{
		$this->isEnabled = TRUE;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}
	
	public function setUser($user)
	{
		$this->user = $user;
	}
	
	public function getUser()
	{
		return $this->user;
	}
	
	public function getCategories()
	{
		return $this->categories;
	}
	
	public function addCategory($category)
	{
		$this->categories[] = $category;
	}
	
	public function setCategories($categories)
	{
		$this->categories = $categories;
	}
	
	/**
	 * @ORM\PrePersist
	 * @ORM\PreUpdate
	 */
	public function doOnUpdatePersist()
	{
		
	}

}
