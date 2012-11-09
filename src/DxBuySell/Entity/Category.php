<?php

namespace DxBuySell\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="buysell_category")
 * @ORM\Entity(repositoryClass="DxBuySell\Entity\Repository\Category")
 * @ORM\HasLifecycleCallbacks
 */
class Category
{

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 */
	private $id;

	/**
	 * @ORM\Column(length=64)
	 */
	private $title;

	/**
	 * @Gedmo\Slug(fields={"title"})
	 * @ORM\Column(length=255, unique=true)
	 */
	private $slug;

	/**
	 * @Gedmo\TreeLevel
	 * @ORM\Column(type="integer")
	 */
	private $level;
	
	/**
	 * @Gedmo\TreeLeft
	 * @ORM\Column(type="integer")
	 */
	private $lft;

	/**
	 * @Gedmo\TreeRight
	 * @ORM\Column(type="integer")
	 */
	private $rgt;

	/**
	 * @Gedmo\TreeRoot
	 * @ORM\Column(type="integer", nullable=TRUE)
	 */
	private $root;
	
	/**
	 * @Gedmo\TreeParent
	 * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
	 * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	private $parent;

	/**
	 * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
	 */
	private $children;

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
	 * @ORM\Column(name="is_enabled" ,type="integer")
	 */
	private $isEnabled;

	/**
	 * @ORM\Column(type="text", nullable=TRUE)
	 */
	private $description;
	
	/**
	 * @ORM\Column(name="meta_keywords", type="text", nullable=TRUE)
	 */
	private $metaKeywords;

	/**
	 * @ORM\Column(name="meta_title", type="text", nullable=TRUE)
	 */
	private $metaTitle;
	
	/**
	 * @ORM\Column(name="meta_description", type="text", nullable=TRUE)
	 */
	private $metaDescription;

	/**
	 * @ORM\Column(name="serialized_data", type="text", nullable=TRUE)
	 */
	private $serializedData;
	
	/**
	 * Data to be serialized
	 * @var array
	 */
	private $serializedDatas = array();

	/**
	 * @ORM\ManyToMany(targetEntity="DxBuySell\Entity\Item", mappedBy="categories", cascade={"ALL"})
	 * https://gist.github.com/3121916
	 * 
	 */
	private $items;

	public function __construct()
	{
		$this->isEnabled = 1;
		$this->children = new ArrayCollection();
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

	public function setSlug($slug)
	{
		$this->slug = $slug;
	}
	
	public function getSlug()
	{
		return $this->slug;
	}
	
	public function getLevel()
	{
		return $this->level;
	}
	
	public function getLeft()
	{
		return $this->lft;
	}
	
	public function getRight()
	{
		return $this->rgt;
	}
	
	public function getChildren()
	{
		return $this->children;
	}
	
	public function setParent($parent = NULL)
	{
		$this->parent = $parent;
	}

	public function getParent()
	{
		return $this->parent;
	}
	
	public function __toString()
	{
		return $this->getTitle();
	}
	
	public function setItems(array $items)
	{
		$this->items = $items;
	}
	
	public function addItem($item)
	{
		$this->items[] = $item;
	}
	
	public function getItems()
	{
		return $this->items;
	}

	/**
	 * Return the unserialized data
	 * @return array
	 */
	public function getSerializedData()
	{
		$serializedData = $this->serializedData;
		if (!empty($serializedData))
		{
			return unserialize($serializedData);
		}
		return NULL;
	}

	public function setSerializedData($serializedData)
	{
		$this->serializedData = $serializedData;
	}

	/**
	 * Add key=>value to be serialized
	 * @param string|integer $key
	 * @param mixed $val 
	 */
	public function addToSerializedData($key, $val)
	{
		$this->serializedDatas[$key] = $val;
	}

	/**
	 * @ORM\PrePersist
	 * @ORM\PreUpdate
	 */
	public function doOnUpdatePersist()
	{
		$this->setSerializedData(serialize($this->serializedDatas));
	}
}