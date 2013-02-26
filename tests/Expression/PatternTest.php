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

use IpUtils\Expression\Pattern;
use IpUtils\Address\IPv4;

class PatternTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @dataProvider  addressProvider
	 */
	public function testMatches($pattern, $address, $expected) {
		$pattern = new Pattern($pattern);

		$this->assertSame($expected, $address->matches($pattern));
		$this->assertSame($expected, $pattern->matches($address));
	}

	public function addressProvider() {
		return array(
			array('0.0.0.0',     new IPv4('0.0.0.0'),     true),
			array('0.0.0.*',     new IPv4('0.0.0.0'),     true),
			array('0.0.0.**',    new IPv4('0.0.0.0'),     true),
			array('0.0.0.*****', new IPv4('0.0.0.0'),     true),
			array('0.0.*.*',     new IPv4('0.0.0.0'),     true),
			array('0.0.*.*',     new IPv4('0.0.0.1'),     true),
			array('0.0.*.*',     new IPv4('0.0.1.0'),     true),
			array('0.0.*.*',     new IPv4('0.0.12.13'),   true),
			array('0.0.*.*',     new IPv4('0.0.0.255'),   true),
			array('0.0.*.*',     new IPv4('0.5.0.255'),   false),
			array('0.0.*.*',     new IPv4('255.5.0.255'), false),
			array('0.0.*7.0',    new IPv4('0.0.17.0'),    true),
			array('0.0.*7.0',    new IPv4('0.0.117.0'),   true),
			array('0.0.*7*.0',   new IPv4('0.0.17.0'),    false),
			array('0.0.*7**.0',  new IPv4('0.0.17.0'),    false),
			array('0.0.1**.0',   new IPv4('0.0.1.0'),     false),
			array('0.0.1**.0',   new IPv4('0.0.17.0'),    true),
			array('0.0.1**.0',   new IPv4('0.0.174.0'),   true),
			array('0.0.1**.0',   new IPv4('0.0.41.0'),    false),
			array('0.0.1**.0',   new IPv4('0.0.211.0'),   false),
			array('0.0.1**1.0',  new IPv4('0.0.121.0'),   true),
			array('0.0.1**1.0',  new IPv4('0.0.11.0'),    false),
			array('0.0.1**1.0',  new IPv4('0.0.122.0'),   false),
			array('0.0.*.255',   new IPv4('0.0.0.255'),   true),
			array('0.0.*.255',   new IPv4('0.0.0.255'),   true),
			array('0.0.0.1*1',   new IPv4('0.0.0.101'),   true),
			array('0.0.0.1*1',   new IPv4('0.0.0.11'),    false),
			array('0.0.0.1*1',   new IPv4('0.0.0.110'),   false),
			array('0.0.0.1*1',   new IPv4('0.0.0.1'),     false)
		);
	}
}
