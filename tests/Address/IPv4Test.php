<?php
/*
 * Copyright (c) 2013, webvariants GbR, http://www.webvariants.de
 *
 * This file is released under the terms of the MIT license. You can find the
 * complete text in the attached LICENSE file or online at:
 *
 * http://www.opensource.org/licenses/mit-license.php
 */

namespace Address;

use IpUtils\Address\IPv4;

class IPv4Test extends \PHPUnit_Framework_TestCase {
	/**
	 * @dataProvider  addressProvider
	 */
	public function testIsValid($address, $expected) {
		$this->assertEquals($expected, IPv4::isValid($address));
	}

	public function addressProvider() {
		return array(
			array('0.0.0.0',   true),
			array('127.0.0.1', true),

			array('127.0.0.300', false),
			array('127.0.000.1', false),
			array('127.0. 0.1',  false),
			array('127. ',       false),
			array('',            false),
			array('::1',         false),
			array('1.1.1.1/8',   false),
			array('1.-.1.1',     false),
		);
	}
}
