<?php
/*
 * Copyright (c) 2013, webvariants GbR, http://www.webvariants.de
 *
 * This file is released under the terms of the MIT license. You can find the
 * complete text in the attached LICENSE file or online at:
 *
 * http://www.opensource.org/licenses/mit-license.php
 */

namespace IpUtils\Address;

use IpUtils\Expression\Subnet;
use IpUtils\Expression\ExpressionInterface;

class IPv4 implements AddressInterface {
	protected $address;

	public function __construct($address) {
		if (!self::isValid($address)) {
			throw new \UnexpectedValueException('"'.$address.'" is no valid IPv4 address.');
		}

		$this->address = $address;
	}

	/**
	 * @param  string $addr
	 * @return boolean
	 */
	public static function isValid($address) {
		return filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false;
	}

	/**
	 * get fully expanded address
	 *
	 * @return string
	 */
	public function getExpanded() {
		return $this->address;
	}

	/**
	 * get compact address representation
	 *
	 * @return string
	 */
	public function getCompact() {
		return $this->getExpanded();
	}

	/**
	 * get IP-specific chunks ([127,000,000,001])
	 *
	 * @return array
	 */
	public function getChunks() {
		return array_map(function($byte) {
			return sprintf('%03d', $byte);
		}, explode('.', $this->getExpanded()));
	}

	/**
	 * returns the compact representation
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->getCompact();
	}

	/**
	 * check whether the IP points to the loopback (localhost) device
	 *
	 * @return boolean
	 */
	public function isLoopback() {
		return $this->getExpanded() === '127.0.0.1';
	}

	/**
	 * check whether the IP is inside a private network
	 *
	 * @return boolean
	 */
	public function isPrivate() {
		return
			$this->matches(new Subnet('10.0.0.0/8')) ||
			$this->matches(new Subnet('172.16.0.0/12')) ||
			$this->matches(new Subnet('192.168.0.0/16'))
		;
	}

	/**
	 * check whether the address matches a given pattern/range
	 *
	 * @param  ExpressionInterface $expression
	 * @return boolean
	 */
	public function matches(ExpressionInterface $expression) {
		return $expression->matches($this);
	}
}
