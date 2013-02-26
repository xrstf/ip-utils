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
use IpUtils\Address\IPv4;
use IpUtils\Address\IPv6;

class Subnet implements ExpressionInterface {
	protected $lower;
	protected $netmask;

	public function __construct($expression) {
		if (strpos($expression, '/') === false) {
			throw new \UnexpectedValueException('Invalid subnet expression "'.$expression.'" given.');
		}

		list($lower, $netmask) = explode('/', $expression, 2);

		if (strpos($netmask, '.') !== false) {
			throw new \UnexpectedValueException('Netmasks may not use the IP address format ("127.0.0.1/255.0.0.0").');
		}

		$netmask = (int) $netmask;

		if ($netmask < 0 || $netmask > 32) {
			throw new \UnexpectedValueException('Netmask must be in range [0..32].');
		}

		$this->netmask = $netmask;

		if (IPv4::isValid($lower)) {
			$this->lower = new IPv4($lower);
		}
		elseif (IPv6::isValid($lower)) {
			$this->lower = new IPv6($lower);
		}
		else {
			throw new \UnexpectedValueException('Subnet expression "'.$expression.'" contains an invalid IP.');
		}
	}

	/**
	 * check whether the expression matches an address
	 *
	 * @param  AddressInterface $address
	 * @return boolean
	 */
	public function matches(AddressInterface $address) {
		$lower = $this->lower->getExpanded();
		$addr  = $address->getExpanded();

		// http://stackoverflow.com/questions/594112/matching-an-ip-to-a-cidr-mask-in-php5
		if ($address instanceof IPv4 && $this->lower instanceof IPv4) {
			$addr    = ip2long($addr);
			$lower   = ip2long($lower);
			$netmask = -1 << (32 - $this->netmask) & ip2long('255.255.255.255');
			$lower  &= $netmask;

			return ($addr & $this->netmask) == $lower;
		}
		elseif ($address instanceof IPv6 && $this->lower instanceof IPv6) {
			$lower = unpack('n*', inet_pton($lower));
			$addr  = unpack('n*', inet_pton($addr));

			for ($i = 1; $i <= ceil($this->netmask / 16); $i++) {
				$left = $this->netmask - 16 * ($i-1);
				$left = ($left <= 16) ? $left : 16;
				$mask = ~(0xffff >> $left) & 0xffff;

				if (($addr[$i] & $mask) != ($lower[$i] & $mask)) {
					return false;
				}
			}

			return true;
		}

		throw new \UnexpectedValueException('Can only compare mixed IP versions.');
	}
}
