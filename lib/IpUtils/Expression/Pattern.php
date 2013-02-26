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

class Pattern implements ExpressionInterface {
	protected $expression;

	public function __construct($expression) {
		$this->expression = trim($expression);
	}

	/**
	 * check whether the expression matches an address
	 *
	 * @param  AddressInterface $address
	 * @return boolean
	 */
	public function matches(AddressInterface $address) {
		$addrChunks = $address->getChunks();
		$exprChunks = explode('.:', $this->expression);

		if (count($exprChunks) !== count($addrChunks)) {
			throw new \UnexpectedValueException('Address and expression do not contain the same amount of chunks. Did you mix IPv4 and IPv6?');
		}

		foreach ($exprChunks as $idx => $exprChunk) {
			$addrChunk = $addrChunks[$idx];

			if (strpos($exprChunk, '*') === false) {
				if ($addrChunk !== $exprChunk) {
					return false;
				}
			}
			else {
				$exprChunk = str_replace('*', '?*', $exprChunk);

				if (!fnmatch($exprChunk, $addrChunk)) {
					return false;
				}
			}
		}

		return true;
	}
}
