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

use Nette;



class Extension extends Nette\DI\CompilerExtension
{

    public function loadConfiguration()
    {
        $config = $this->getConfig();
        $builder = $this->getContainerBuilder();

        unset($config['services']);
        isset($config['tables']) ?: $config['tables'] = array();

        $builder->addDefinition($this->prefix('settings'))
        	->setClass('Ndab\Settings')
			->addTag('Ndab\Settings')
        	->setFactory('Ndab\Settings::from', array($config));
		
		$builder->addDefinition($this->prefix('rowFactory'))
			->setClass('Ndab\RowFactory');
	}
	
	public function beforeCompile() {
		parent::beforeCompile();
		$builder = $this->getContainerBuilder();
		foreach($builder->getDefinitions() as $definition) {
			if($definition->class == 'Nette\Database\Connection') {
				foreach($definition->setup as $statement) {
					if($statement->entity == 'setSelectionFactory') {
						$selectionFactory = array_shift($statement->arguments);
						/* @var $selectionFactory \Nette\DI\Statement */
						$selectionFactory->entity = '\Ndab\SelectionFactory';
						array_unshift($selectionFactory->arguments, '@' . $this->prefix('rowFactory'));
						array_unshift($statement->arguments, $selectionFactory);
					}
				}
			}
		}
	}

}
