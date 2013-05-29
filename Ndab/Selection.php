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
 * Ndab selection
 *
 * @author  Jan Skrasek
 */
class Selection extends Table\Selection
{
	/** @var RowFactory */
	protected $rowFactory;

	public function __construct(RowFactory $rowFactory, Nette\Database\Connection $connection, $table, Nette\Database\IReflection $reflection, Nette\Caching\IStorage $cacheStorage = NULL) {
		parent::__construct($connection, $table, $reflection, $cacheStorage);
		$this->rowFactory = $rowFactory;
	}

	public function getTable()
	{
		return $this->name;
	}


	public function createRow(array $row)
	{
		return $this->rowFactory->create($row, $this);
	}

	
	public function createSelectionInstance($table = NULL)
	{
		return new Selection($this->rowFactory, $this->connection, $table ?: $this->getTable(), $this->reflection, $this->cache->getStorage());
	}



	protected function createGroupedSelectionInstance($table, $column)
	{
		return new GroupedSelection($this->rowFactory, $this, $table, $column);
	}

}
