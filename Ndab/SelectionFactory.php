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
 * Ndab selection factory
 *
 * @author Karel Hak
 */
class SelectionFactory extends Nette\Database\SelectionFactory {
	/** @var RowFactory */
	protected $rowFactory;
	/** @var Nette\Database\Connection */
	protected $connection;
	/** @var Nette\Database\IReflection */
	protected $reflection;
	/** @var Nette\Caching\IStorage */
	protected $cacheStorage;
	public function __construct(RowFactory $rowFactory, Nette\Database\Connection $connection, Nette\Database\IReflection $reflection = NULL, Nette\Caching\IStorage $cacheStorage = NULL) {
		parent::__construct($connection, $reflection, $cacheStorage);
		$this->rowFactory = $rowFactory;
		$this->connection = $connection;
		$this->reflection = $reflection;
		$this->cacheStorage = $cacheStorage;
	}
	
	public function create($table) {
		return new Selection($this->rowFactory, $this->connection, $table, $this->reflection, $this->cacheStorage);
	}
}