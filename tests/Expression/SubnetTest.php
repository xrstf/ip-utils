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

use IpUtils\Expression\Subnet;
use IpUtils\Address\IPv4;

class SubnetTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @dataProvider  addressProvider
	 */
	public function testMatches($subnet, $address, $expected) {
		$subnet = new Subnet($subnet);

		$this->assertSame($expected, $address->matches($subnet));
		$this->assertSame($expected, $subnet->matches($address));
	}

	public function addressProvider() {
		return array(
			array('0.0.0.0/1',  new IPv4('0.0.0.0'), true),
			array('0.0.0.0/8',  new IPv4('0.0.0.0'), true),
			array('0.0.0.0/32', new IPv4('0.0.0.0'), true)
		);
	}
}
