<?php

namespace DxBuySell\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class Item extends EntityRepository
{

	/**
	 * Enable/Disable caching
	 * @var boolean
	 */
	protected $cacheEnabled = TRUE;

	/**
	 * Cache lifetime in seconds
	 * @var integer
	 */
	protected $cacheLifetime = 100000;

	/**
	 * The cache name prefix
	 * @var string
	 */
	protected $cachePrefix = 'DxBuySellEntityItem';

	/**
	 * array of suffixes added to cache name
	 * @var array
	 */
	protected $cacheNodeSuffixes = array();

	/**
	 * If to query only enabled (is_enabled)
	 * @var boolean
	 */
	protected $onlyEnabled = TRUE;
	
	/**
	 * Return a cache name
	 * @param object|string $node The Node or the cacheName
	 * @param string $method Suffix to add or the class function or method name __FUNCTION__
	 * @return string
	 */
	protected function cacheName($node = NULL, $method = NULL)
	{
		$meta = $this->getClassMetadata();
		if ($node !== NULL)
		{
			if ($node instanceof $meta->name)
			{
				return $this->cachePrefix . $node->getId() . $method;
			}
			if (is_string($node))
			{
				return $this->cachePrefix . $node . $method;
			}
		}
		return $this->cachePrefix . $method;
	}

	/** 	
	 * Clear cache for this entity
	 * @param object $node 
	 */
	public function clearCache($node = NULL)
	{
		$cacheDriver = $this->_em->getConfiguration()->getResultCacheImpl();
		if ($node !== NULL)
		{
			$meta = $this->getClassMetadata();
			if ($node instanceof $meta->name)
			{
				foreach ($this->cacheNodeSuffixes as $c)
				{
					$cacheDriver->delete($this->cachePrefix . $node->getId() . $c);
				}
			}
		}
		else
		{
			$cacheDriver->delete($this->cachePrefix);
		}
	}

	/**
	 * Return the Cache prefix
	 * @return string
	 */
	public function getCachePrefix()
	{
		return $this->cachePrefix;
	}

	/**
	 * IF enabled will return only enabled Categories
	 * @param boolean $onlyEnabled
	 * @return \DxBuySell\Entity\Repository\Item
	 */
	public function setOnlyEnabled($onlyEnabled)
	{
		$this->onlyEnabled = $onlyEnabled;
		return $this;
	}

}