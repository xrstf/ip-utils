<?php
/*
 * Copyright (c) 2013, webvariants GbR, http://www.webvariants.de
 *
 * This file is released under the terms of the MIT license. You can find the
 * complete text in the attached LICENSE file or online at:
 *
 * http://www.opensource.org/licenses/mit-license.php
 */

namespace IpUtils\Expression;

use IpUtils\Address\AddressInterface;

class Literal implements ExpressionInterface {
	protected $expression;

	public function __construct($expression) {
		$this->expression = strtolower(trim($expression));
	}

	/**
	 * check whether the expression matches an address
	 *
	 * @param  AddressInterface $address
	 * @return boolean
	 */
	public function matches(AddressInterface $address) {
		return $address->getExpanded() === $this->expression;
	}
}
