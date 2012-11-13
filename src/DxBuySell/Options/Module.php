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
}
