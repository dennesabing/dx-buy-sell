<?php

namespace DxBuySell\Options;

use Zend\Stdlib\AbstractOptions;

class Module extends AbstractOptions
{

	/**
	 * Enable Plans
	 * @var boolean
	 */
	protected $enablePlans = TRUE;

	/**
	 * Enable Plan: FREE
	 * @var boolean
	 */
	protected $enablePlanFree = TRUE;
	
	/**
	 * The valid sections to where ads can be posted
	 * @var array
	 */
	protected $sections = array('items', 'auto', 'realestate', 'jobs');

	/**
	 * Path to XML Forms
	 * @var string
	 */
	protected $pathToXmlForms = '/../../../data/categoryxmlforms/';
	
	/**
	 * Set the Path to Xml Forms
	 * @param string $path
	 * @return \DxBuySell\Options\Module 
	 */
	public function setPathToXmlForms($path)
	{
		$this->pathToXmlForms = $path;
		return $this;
	}
	
	/**
	 * Return the Path to Xml Forms
	 * @return string
	 */
	public function getPathToXmlForms()
	{
		return $this->pathToXmlForms;
	}
	
	/**
	 * Enable the FREE Plan
	 * 
	 * @param boolean $flag
	 * @return \DxBuySell\Options\Module 
	 */
	public function setEnablePlanFree($flag)
	{
		$this->enablePlanFree = $flag;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getEnablePlanFree()
	{
		return $this->enablePlanFree;
	}

	/**
	 * Enable Plans
	 * 
	 * @param boolean $flag
	 * @return \DxBuySell\Options\Module 
	 */
	public function setEnablePlans($flag)
	{
		$this->enablePlans = $flag;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getEnablePlans()
	{
		return $this->enablePlans;
	}
	
	/**
	 * Check if the given section is valid or in the array of sections
	 * @param string $section
	 * @return boolean 
	 */
	public function checkSection($section)
	{
		if(in_array($section, $this->sections))
		{
			return TRUE;
		}
		return FALSE;
	}

}
