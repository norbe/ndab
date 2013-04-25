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
 * Extends functionality of active row
 */
class ActiveRow extends Table\ActiveRow {
	
	/**
	 * Returns array of subitems fetched from related
	 * @param string
	 * @return \Ndab\SubRelation
	 */
	public function getSubRelation($selector, $throughtColumn = null) {
		return new \Ndab\SubRelation($this, $selector, $throughtColumn);
	}
}

