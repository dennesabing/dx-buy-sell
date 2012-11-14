<?php

$config = array(
	'view_manager' => array(
		'display_not_found_reason' => true,
		'display_exceptions' => true,
		'doctype' => 'HTML5',
		'not_found_template' => 'error/404',
		'exception_template' => 'error/index',
		'template_map' => array(
			'layout/layout' =>  __DIR__ . '/../view/theme/default/layout/1column.phtml',
			'layout/1column' => __DIR__ . '/../view/theme/default/layout/1column.phtml',
			'layout/2column-leftbar' => __DIR__ . '/../view/theme/default/layout/2column-leftbar.phtml',
			'layout/2column-rightbar' => __DIR__ . '/../view/theme/default/layout/2column-rightbar.phtml',
			'layout/3column' => __DIR__ . '/../view/theme/default/layout/3column.phtml',
			'error/404' => __DIR__ . '/../view/theme/default/view/error/404.phtml',
			'error/index' => __DIR__ . '/../view/theme/default/view/error/index.phtml',
		),
		'template_path_stack' => array(
			 __DIR__ . '/../view/theme/default/partials/',
			'dx-buy-sell' => __DIR__ . '/../view'
		),
	),
	'controllers' => array(
		'invokables' => array(
			'DxBuySell\Controller\Category' => 'DxBuySell\Controller\CategoryController',
			'DxBuySell\Controller\Item' => 'DxBuySell\Controller\ItemController',
			'DxBuySell\Controller\Create' => 'DxBuySell\Controller\CreateController',
		),
	),
	'router' => array(
		'routes' => array(
			'dx-buy-sell-create' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/create',
					'defaults' => array(
						'__NAMESPACE__' => 'DxBuySell\Controller',
						'controller' => 'DxBuySell\Controller\Create',
						'action' => 'index',
						'entity_type' => 'sell'
					),
				),
				'may_terminate' => true,
				'child_routes' => array(
					'section' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/section',
							'defaults' => array(
								'action' => 'index',
							),
						),
					),
					'category' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/category',
							'defaults' => array(
								'action' => 'category',
							),
						),
					),
					'details' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/details',
							'defaults' => array(
								'action' => 'details',
							),
						),
					),
					'credits' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/credits',
							'defaults' => array(
								'action' => 'credits',
							),
						),
					),
					'success' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/success',
							'defaults' => array(
								'action' => 'success',
							),
						),
					)
				)
			),
			'dx-buy-sell-forsale' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/forsale',
					'defaults' => array(
						'__NAMESPACE__' => 'DxBuySell\Controller',
						'controller' => 'DxBuySell\Controller\Category',
						'action' => 'index',
						'entity_type' => 'sell'
					),
				),
				'may_terminate' => true,
				'child_routes' => array(
					'category' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/[:category_slug[/:page[/:sorting[/:task]]]]',
							'constraints' => array(
								'category_slug' => '[a-zA-Z][a-zA-Z0-9_-]*',
								'sorting' => '[a-zA-Z][a-zA-Z0-9_-]*',
								'page' => '[0-9]+',
							),
							'defaults' => array(
								'action' => 'category',
							),
						),
					),
					'item' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/[:item_slug[/:action]]',
							'constraints' => array(
								'item_slug' => '[0-9]+',
							),
							'defaults' => array(
								'controller' => 'DxBuySell\Controller\Item',
								'action' => 'index'
							),
						),
					),
					'create' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/create[/:category_slug]',
							'defaults' => array(
								'controller' => 'DxBuySell\Controller\Create',
								'action' => 'index',
								'entity_type' => 'sell'
							),
						),
					),
				),
			),
			'dx-buy-sell-tobuy' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/tobuy',
					'defaults' => array(
						'__NAMESPACE__' => 'DxBuySell\Controller',
						'controller' => 'DxBuySell\Controller\Category',
						'action' => 'index',
						'entity_type' => 'buy'
					),
				),
				'may_terminate' => true,
				'child_routes' => array(
					'category' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/[:category_slug[/:page[/:sorting]]]',
							'constraints' => array(
								'category_slug' => '[a-zA-Z][a-zA-Z0-9_-]*',
								'sorting' => '[a-zA-Z][a-zA-Z0-9_-]*',
								'page' => '[0-9]+',
							),
							'defaults' => array(
								'action' => 'category',
							),
						),
					),
					'item' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/[:item_slug[/:action]]',
							'constraints' => array(
								'item_slug' => '[0-9]+',
							),
							'defaults' => array(
								'controller' => 'DxBuySell\Controller\Item',
								'action' => 'index'
							),
						),
					),
					'create' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/create[/:category_slug]',
							'constraints' => array(
								'category_slug' => '[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(
								'controller' => 'DxBuySell\Controller\Create',
								'action' => 'index',
								'entity_type' => 'buy'
							),
						),
					),
				),
			),
		),
	),
);
return \Dx\Module::defaultConfig('dxBuySell', __DIR__, $config);