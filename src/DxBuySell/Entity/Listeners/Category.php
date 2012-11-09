<?php

namespace DxBuySell\Entity\Listeners;

use Doctrine\Common\EventArgs;
use Doctrine\Common\Persistence\ObjectManager;
use DxBuySell\Entity\Category as EntityCategory;
use Dx\Doctrine\Entity\Listeners;

/**
 * Category Entity Event Listener
 *
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @package DxBuySell.Entity.Listeners
 * @subpackage Category
 * @link http://labs.madayaw.com
 */

class Category extends Listeners
{

	/**
	 * Specifies the list of events to listen
	 *
	 * @return array
	 */
	public function getSubscribedEvents()
	{
		return array(
			//'prePersist',
			//'preRemove',
			//'preUpdate',
			//'onFlush',
			//'loadClassMetadata',
			'postPersist',
			'postUpdate',
				//'postRemove'
		);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getNamespace()
	{
		return __NAMESPACE__;
	}

	public function postUpdate(EventArgs $eventArgs)
	{
		$this->clearCache($eventArgs);
	}

	public function postPersist(EventArgs $eventArgs)
	{
		$this->clearCache($eventArgs);
	}

	/**
	 * Clear cache for a specific node
	 * @param EventArgs $eventArgs 
	 */
	protected function clearCache(EventArgs $eventArgs)
	{
		$ea = $this->getEventAdapter($eventArgs);
		$em = $ea->getEntityManager();
		$repo = $em->getRepository('DxBuySell\Entity\Category');
		if ($eventArgs->getEntity() instanceof EntityCategory)
		{
			$repo->clearCache($eventArgs->getEntity());
		}
	}
	
	public function createCache(EventArgs $eventArgs)
	{
		
	}
}
