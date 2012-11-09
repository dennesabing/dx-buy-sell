<?php

namespace DxBuySell\Entity\Repository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class Category extends NestedTreeRepository
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
	protected $cachePrefix = 'DxBuySellEntityCategory';

	/**
	 * array of suffixes added to cache name
	 * @var array
	 */
	protected $cacheNodeSuffixes = array(
		'getPath',
		'getNodesHierarchy',
		'findOneById'
	);

	/**
	 * If to query only enabled (is_enabled) category
	 * @var boolean
	 */
	protected $onlyEnabled = TRUE;

	/**
	 * Fetch One By a slug
	 * @param string $slug string to search in column "slug"
	 * @return \DxBuySell\Entity\Category 
	 */
	public function findOneBySlug($slug)
	{
		$meta = $this->getClassMetadata();
		$config = $this->listener->getConfiguration($this->_em, $meta->name);
		$qb = $this->_em->createQueryBuilder();
		$query = $qb
				->select('node')
				->from($config['useObjectClass'], 'node')
				->where($qb->expr()->eq('node.slug', "'" . $slug . "'"))
				->getQuery()
				->useResultCache($this->cacheEnabled, $this->cacheLifetime,
					 $this->cacheName(__FUNCTION__ . $slug));
		return $query->getSingleResult();
	}

	/**
	 * Find one row by an id
	 * @param integer $id id to search in column "id"
	 * @return \DxBuySell\Entity\Category 
	 */
	public function findOneById($id)
	{
		$meta = $this->getClassMetadata();
		$config = $this->listener->getConfiguration($this->_em, $meta->name);
		$qb = $this->_em->createQueryBuilder();
		$query = $qb
				->select('node')
				->from($config['useObjectClass'], 'node')
				->where($qb->expr()->eq('node.id', $id))
				->getQuery()
				->useResultCache($this->cacheEnabled, $this->cacheLifetime,
					 $this->cacheName($id . __FUNCTION__));
		return $query->getSingleResult();
	}

	/**
	 * Fetch items by node
	 * http://docs.doctrine-project.org/en/latest/tutorials/pagination.html
	 * http://stackoverflow.com/questions/10043588/doctrine-2-pagination-with-association-mapping
	 * http://docs.doctrine-project.org/en/latest/reference/query-builder.html
	 * @param object $node The node
	 * @param array $options Associtive array
	 * $options = array(
	 *  'page' => $theCurrentPage,
	 *  'rowsPerPage' => $theRowsPerPage
	 *  'sortField' => $theFieldToSort
	 *  'sortDirection' => $theDirectionToSort
	 * )
	 * @return object \Zend\Paginator
	 */
	public function fetchItems($node, $options = array())
	{
		$page = (int) isset($options['page']) ? $options['page'] : 0;
		$maxRows = (int) isset($options['rowsPerPage']) ? $options['rowsPerPage'] : 15;
		$sortField = isset($options['sortField']) ? $options['sortField'] : 'created';
		$sortDirection = isset($options['sortDirection']) ? $options['sortDirection'] : 'DESC';
		$cacheName = $this->cacheName($node->getId() . 'collection' . __FUNCTION__ . $page . $maxRows . $sortField . $sortDirection);

		$query = $this->_em->createQuery("
			SELECT i
			 FROM DxBuySell\Entity\Item i 
			  INNER JOIN i.categories c
			  WHERE c.id = " . $node->getId() . " 
			  ORDER By i." . $sortField . " " . $sortDirection
		);
		
		$query->useResultCache($this->cacheEnabled, $this->cacheLifetime, $cacheName);
		$paginator = new Paginator($query, $fetchJoinCollection = true);
		$zendPaginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\Iterator($paginator->getIterator()));
		$zendPaginator->setItemCountPerPage($maxRows)
				->setCurrentPageNumber($page);
		return $zendPaginator;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getRootNodes($sortByField = NULL, $direction = 'asc')
	{
		$query = $this->getRootNodesQuery($sortByField, $direction);
		$query->useResultCache($this->cacheEnabled, $this->cacheLifetime,
						 $this->cacheName(NULL, __FUNCTION__) . \Dx\StringManager::arrayToString(array('sort' => $sortByField, 'direction' => $direction)));
		return $query->getResult();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getRootNodesQueryBuilder($sortByField = NULL,
			$direction = 'asc')
	{
		$qb = parent::getRootNodesQueryBuilder($sortByField, $direction);
		if ($this->onlyEnabled)
		{
			$qb->andWhere($qb->expr()->eq('node.is_enabled', 1));
		}
		return $qb;
	}

	/**
	 * {@inheritDoc}
	 */
	public function childrenQueryBuilder($node = NULL, $direct = FALSE,
			$sortByField = NULL, $direction = 'ASC', $includeNode = FALSE)
	{
		$qb = parent::childrenQueryBuilder($node, $direct, $sortByField, $direction,
									 $includeNode);
		if ($this->onlyEnabled)
		{
			$qb->andWhere($qb->expr()->eq('node.isEnabled', 1));
		}
		return $qb;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getPath($node)
	{
		$query = $this->getPathQuery($node);
		$query->useResultCache($this->cacheEnabled, $this->cacheLifetime,
						 $this->cacheName($node, __FUNCTION__));
		return $query->getArrayResult();
	}

	/**
	 * {@inheritDoc}
	 */
	public function children($node = NULL, $direct = FALSE, $sortByField = NULL,
			$direction = 'ASC', $includeNode = FALSE)
	{
		$query = $this->childrenQuery($node, $direct, $sortByField, $direction,
								$includeNode);
		$query->useResultCache($this->cacheEnabled, $this->cacheLifetime,
						 $this->cacheName($node, __FUNCTION__));
		return $query->getResult();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getNodesHierarchy($node = NULL, $direct = FALSE,
			array $options = array(), $includeNode = FALSE)
	{
		$query = $this->getNodesHierarchyQuery($node, $direct, $options, $includeNode);
		$query->useResultCache($this->cacheEnabled, $this->cacheLifetime,
						 $this->cacheName($node, __FUNCTION__));
		return $query->getArrayResult();
	}

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
				$cacheDriver->delete($this->cachePrefix . 'findOneBySlug' . $node->getSlug());
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
	 * @return \DxBuySell\Entity\Repository\Category
	 */
	public function setOnlyEnabled($onlyEnabled)
	{
		$this->onlyEnabled = $onlyEnabled;
		return $this;
	}

}