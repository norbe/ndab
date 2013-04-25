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

/**
 * Ndab sub relation
 *
 * @author  Karel Hak
 */
class SubRelation extends \Nette\Object implements \Iterator, \ArrayAccess, \Countable
{
	/** @var ActiveRow */
	private $activeRow;
	/** @var \ArrayIterator */
	private $entities;
	/** @var string */
	private $subItemSelector;
	/** @var GroupedSelection */
	private $related;
	
	public function __construct(ActiveRow $row, $selector, $throughtColumn = null) {
		$this->activeRow = $row;
		list($relatedSelector, $this->subItemSelector) = explode(':', $selector);
		$this->related = $this->activeRow->related($relatedSelector, $throughtColumn);

		
	}
	
	/**
	 * @return \ArrayIterator
	 */
	protected function getEntities() {
		if(is_null($this->entities)) {
			$entities = array();
			foreach ($this->related as $subItem) {
				$entities[] = $subItem->{$this->subItemSelector};
			}
			$this->entities = new \ArrayIterator($entities);
		}
		
		return $this->entities;
	}

	public function count() {
		return $this->getEntities()->count();
	}

	public function current() {
		return $this->getEntities()->current();
	}

	public function key() {
		return $this->getEntities()->key();
	}

	public function next() {
		$this->getEntities()->next();
	}

	public function rewind() {
		$this->getEntities()->rewind();
	}

	public function valid() {
		return $this->getEntities()->valid();
	}


	public function offsetExists($offset) {
		return $this->getEntities()->offsetExists($offset);
	}

	public function offsetGet($offset) {
		return $this->getEntities()->offsetGet($offset);
	}

	public function offsetSet($offset, $value) {
		$this->getEntities()->offsetSet($offset, $value);
	}

	public function offsetUnset($offset) {
		$this->getEntities()->offsetUnset($offset);
	}
		
	/************************** Group selection API ***************************/
	/* @method Entity fetch()
	* @method GroupSelection limit( integer $limit, integer $offset = NULL )
	* @method GroupSelection page( integer $page, integer $itemsPerPage )
	* @method GroupSelection group( string $columns, string $having = NULL )
	* @method string aggregation( string $function )
	* @method int min( string $column = NULL )
	* @method int max( string $column = NULL )
	* @method int sum( string $column = NULL )
	*/			 
	
	/**
	 * @param string $key
	 * @param string $value
	 * @return array
	 */
	public function fetchPairs($key, $value = null) {
		$return = array();
		foreach ($this as $row) {
			$return[is_object($row[$key]) ? (string) $row[$key] : $row[$key]] = ($value === NULL ? $row : $row[$value]);
		}
		return $return;
	}
	
	public function fetch() {
		$entity = $this->current();
		$this->next();
		return $entity;
	}
	
	/**
	 * Adds where condition, more calls appends with AND.
	 * @param mixed
	 * @param mixed
	 * @return SubRelation
	 */
	public function where($condition, $parameters = array()) {
		$this->related->where($condition, $parameters);
		return $this;
	}		 
	/**
	 * Adds order clause, more calls appends to the end.
	 * @param  string for example 'column1, column2 DESC'
	 * @return SubRelation provides a fluent interface
	 */
	public function order($columns) {
		$this->related->order($columns);
		return $this;
	}
		/**
	 * Sets limit clause, more calls rewrite old values.
	 * @param  int
	 * @param  int
	 * @return SubRelation provides a fluent interface
	 */
	public function limit($limit, $offset = NULL)
	{
		$this->related->limit($limit, $offset);
		return $this;
	}
	/**
	 * Sets offset using page number, more calls rewrite old values.
	 * @param  int
	 * @param  int
	 * @return SubRelation provides a fluent interface
	 */
	public function page($page, $itemsPerPage)
	{
		$this->related->limit($itemsPerPage, ($page - 1) * $itemsPerPage);
		return $this;
	}
}
