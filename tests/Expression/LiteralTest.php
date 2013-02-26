<?php
/*
 * Copyright (c) 2013, webvariants GbR, http://www.webvariants.de
 *
 * This file is released under the terms of the MIT license. You can find the
 * complete text in the attached LICENSE file or online at:
 *
 * http://www.opensource.org/licenses/mit-license.php
 */

namespace Expression;

use IpUtils\Expression\Literal;
use IpUtils\Address\IPv4;

class LiteralTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @dataProvider  addressProvider
	 */
	public function testMatches($literal, $address, $expected) {
		$literal = new Literal($literal);

		$this->assertEquals($expected, $address->matches($literal));
		$this->assertEquals($expected, $literal->matches($address));
	}

	public function addressProvider() {
		return array(
			array('0.0.0.0',    new IPv4('0.0.0.0'),    true),
			array('12.0.0.0',   new IPv4('12.0.0.0'),   true),
			array('12.0.0.255', new IPv4('12.0.0.255'), true),
			array('12.0.0.255', new IPv4('12.0.0.'),    false),
			array('12.0.0.255', new IPv4('12..0.255'),  false),
			array('12.0.0.0',   new IPv4('1.0.0.0'),    false),
			array('12.0.0.0',   new IPv4('1.2.0.0'),    false),
			array('12.0.55.0',  new IPv4('12.0.55.0'),  false)
		);
	}
}
