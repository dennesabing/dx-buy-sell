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
				'dxbuysell_form_create_section' => function($sm)
				{
                    $options = $sm->get('dxbuysell_options');
                    $form = new \DxBuySell\Form\CreateSection('postCreate', NULL, $options);
                    return $form;
				},
				'dxbuysell_form_create_section_filter' => function($sm)
				{
                    $options = $sm->get('dxbuysell_options');
                    $filter = new \DxBuySell\Form\CreateSectionInputFilter(NULL, $options);
                    return $filter;
				},
				'dxbuysell_form_create_details' => function($sm)
				{
                    $options = $sm->get('dxbuysell_options');
                    $form = new \DxBuySell\Form\CreateDetails('postCreate', NULL, $options);
                    return $form;
				},
				'dxbuysell_session' => function($sm)
				{
                    return new \Zend\Session\Container('dxbuysell');
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