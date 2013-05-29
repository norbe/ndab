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
class RowFactory extends \Nette\Object {
	/** @var Settings */
	protected $settings;
	/** @var string */
	public $defaultClass = 'Ndab\Entity';
	public function __construct(Settings $settings) {
		$this->settings = $settings;
	}
	
	public function create($data, Table\Selection $selection) {		
		$class = isset($this->settings->tables->{$selection->getName()})
			? $this->settings->tables->{$selection->getName()}
			: $this->defaultClass;
		return new $class(new ActiveRow($data, $selection));
	}
}