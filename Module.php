<?php

// module/Album/Module.php

namespace DxBuySell;

use Dx\Module as xModule;
use Zend\EventManager\EventInterface as Event;

class Module extends xModule
{

	public $namespace = __NAMESPACE__;
	public $dir = __DIR__;

	public function onBootstrap(Event $e)
	{
		$application = $e->getApplication();
		$serviceManager = $application->getServiceManager();
		$evm = $serviceManager->get('doctrine.eventmanager.orm_default');
		
		$category = new \DxBuySell\Entity\Listeners\Category;
		$evm->addEventSubscriber($category);
		$item = new \DxBuySell\Entity\Listeners\Item;
		$evm->addEventSubscriber($item);
	}

	public function getAutoloaderConfig()
	{
		return array(
			'Zend\Loader\ClassMapAutoloader' => array(
				$this->dir . '/autoload_classmap.php',
			),
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					$this->namespace => $this->dir . '/src/' . $this->namespace,
				),
			),
		);
	}

	public function getServiceConfig()
	{
		return array(
            'factories' => array(
                'dxbuysell_options' => function ($sm) {
                    $config = $sm->get('Config');
                    return new Options\Module(isset($config['dxbuysell']) ? $config['dxbuysell'] : array());
                },
				'dxbuysell_form_item' => function($sm)
				{
                    $options = $sm->get('dxbuysell_options');
                    $form = new \DxBuySell\Form\Item('postCreate', NULL, $options);
//                    $form->setInputFilter();
                    return $form;
				}
			)
		);
	}
	
	public function getViewHelperConfig()
	{
		return array(
			'factories' => array(
				'dxBuySellCategory' => function($sm)
				{
					return new \DxBuySell\View\Helper\Category();
				},
			),
		);
	}

}