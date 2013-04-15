<?php

/**
 * This file is part of the Ndab
 *
 * Copyright (c) 2012 Jan Skrasek (http://jan.skrasek.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Ndab;

use Nette,
	Nette\Database\Table;



/**
 * Ndab base model entity
 *
 * @author  Jan Skrasek
 */
class Entity extends Nette\Object implements \ArrayAccess, \IteratorAggregate {
	/** @var ActiveRow */
	protected $activeRow;
	private $data;
	private $modified;
	
	/**
	 * @param ActiveRow|array $data
	 */
	public function __construct($data = null) {
		if($data instanceof Entity) {
			debug_print_backtrace();
		}
		if($data instanceof ActiveRow) {
			$this->activeRow = $data;
			$this->setData($data->toArray());
		} else {
			$this->setData($data);
		}
	}
	
	public function setData($data) {
		foreach($data as $key => $value) {
			$this->__set($key, $value);
		}
	}
	
	public function toArray() {
		return $this->data;
	}
		
	/********************* interface IteratorAggregate ****************d*g**/

	public function getIterator()
	{
		return new \ArrayIterator($this->data);
	}

	/********************* interface ArrayAccess & magic accessors ****************d*g**/

	/**
	 * Stores value in column.
	 * @param  string column name
	 * @param  string value
	 * @return void
	 */
	public function offsetSet($key, $value)
	{
		$this->__set($key, $value);
	}



	/**
	 * Returns value of column.
	 * @param  string column name
	 * @return string
	 */
	public function offsetGet($key)
	{
		return $this->__get($key);
	}



	/**
	 * Tests if column exists.
	 * @param  string column name
	 * @return bool
	 */
	public function offsetExists($key)
	{
		return $this->__isset($key);
	}



	/**
	 * Removes column from data.
	 * @param  string column name
	 * @return void
	 */
	public function offsetUnset($key)
	{
		$this->__unset($key);
	}
	
	public function __isset($key)
	{
		if (array_key_exists($key, $this->data)) {
			return isset($this->data[$key]);
		}
		return false;
	}
	
	public function __set($name, $value) {
		$this->data[$name] = $value;
	}
	
	public function __unset($name) {
		unset($this->data[$name]);
	}
	
	public function & __get($key)
	{
		$method = "get$key";
		$method[3] = $method[3] & "\xDF";

		if (method_exists($this, $method)) {
			$return = $this->$method();
			return $return;
		} else if(array_key_exists($key, $this->data)) {
			return $this->data[$key];
		} else if(!is_null($this->activeRow)) {
			$return = $this->activeRow->__get($key);
			return $return;
		} else {
			throw new Nette\InvalidStateException("Unknown property '$key'");
		}
	}
	
	public function __call($name, $arguments) {
		return call_user_func_array(array($this->activeRow, $name), $arguments);
	}
	
	
	public function __toString()
	{
		if($this->activeRow) {
			return (string)$this->activeRow;
		} else {
			return null;
		}
	}
}
