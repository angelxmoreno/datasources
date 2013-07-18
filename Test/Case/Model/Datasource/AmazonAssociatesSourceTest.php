<?php
/**
 * Amazon Associates DataSource Test file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       datasources
 * @subpackage    datasources.tests.cases.models.datasources
 * @since         CakePHP Datasources v 0.3
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Import Required libraries
 *
 */
App::uses('AmazonAssociatesSource', 'Datasources.Model/Datasource');

/**
 * AmazonAssociatesTestCase
 *
 * @package       datasources
 * @subpackage    datasources.tests.cases.models.datasources
 */
class AmazonAssociatesTestCase extends CakeTestCase {

/**
 * Amazon Datasource object
 *
 * @var MockAmazonAssocatesSource
 * @access public
 */
	var $Amazon = null;

/**
 * Configuration
 *
 * @var array
 * @access public
 */
	var $config = array(
		'key' => 'PUBLICKEY',
		'secret' => 'SECRETKEY',
		'tag' => 'ASSID',
		'locale' => 'com' //(ca,com,co,uk,de,fr,jp)
	);

/**
 * Start Test
 *
 * @return void
 * @access public
 */
	function startTest($method){
		$this->Amazon = $this->getMock('AmazonAssociatesSource', array('_request'), array($this->config));
	}

/**
 * testFind
 *
 * @return void
 * @access public
 */
	function testFind(){
		$this->Amazon->expects($this->any())
					 ->method('_request');
		$this->Amazon->find('DVD', array('title' => 'harry'));

		$this->assertEqual('AWSECommerceService', $this->Amazon->query['Service']);
		$this->assertEqual('PUBLICKEY', $this->Amazon->query['AWSAccessKeyId']);
		$this->assertEqual('ASSID', $this->Amazon->query['AccociateTag']);
		$this->assertEqual('DVD', $this->Amazon->query['SearchIndex']);
		$this->assertEqual('2009-03-31', $this->Amazon->query['Version']);
		$this->assertEqual('harry', $this->Amazon->query['Title']);
		$this->assertEqual('ItemSearch', $this->Amazon->query['Operation']);

		$this->Amazon->expects($this->any())
					 ->method('_request');
		$this->Amazon->find('DVD', 'harry');
		$this->assertEqual('AWSECommerceService', $this->Amazon->query['Service']);
		$this->assertEqual('PUBLICKEY', $this->Amazon->query['AWSAccessKeyId']);
		$this->assertEqual('ASSID', $this->Amazon->query['AccociateTag']);
		$this->assertEqual('DVD', $this->Amazon->query['SearchIndex']);
		$this->assertEqual('2009-03-31', $this->Amazon->query['Version']);
		$this->assertEqual('harry', $this->Amazon->query['Title']);
		$this->assertEqual('ItemSearch', $this->Amazon->query['Operation']);
	}

/**
 * testFindById
 *
 * @return void
 * @access public
 */
	function testFindById(){
		$this->Amazon->expects($this->any())
					 ->method('_request');
		$this->Amazon->findById('ITEMID');

		$this->assertEqual('AWSECommerceService', $this->Amazon->query['Service']);
		$this->assertEqual('PUBLICKEY', $this->Amazon->query['AWSAccessKeyId']);
		$this->assertEqual('ASSID', $this->Amazon->query['AccociateTag']);
		$this->assertEqual('ItemLookup', $this->Amazon->query['Operation']);
		$this->assertEqual('2009-03-31', $this->Amazon->query['Version']);
		$this->assertEqual('ITEMID', $this->Amazon->query['ItemId']);
	}

/**
 * testSignQuery
 *
 * @return void
 * @access public
 */
	function testSignQuery(){
		$query = array(
			'Service' => 'AWSECommerceService',
			'AWSAccessKeyId' => 'PUBLICKEY',
			'Timestamp' => '2010-03-01T07:44:03Z',
			'AccociateTag' => 'ASSID',
			'Version' => '2009-03-31',
			'Operation' => 'ItemSearch',
		);
		$this->Amazon->find(null, $query);
		$results = $this->Amazon->getLog();
		$expected = 'http://ecs.amazonaws.com/onca/xml?AWSAccessKeyId=PUBLICKEY&AccociateTag=ASSID&Operation=ItemSearch&Service=AWSECommerceService&Timestamp=2010-03-01T07%3A44%3A03Z&Version=2009-03-31&Signature=oEbqdS17pJmjRaSzbBX14zcnlprDbRlpDhQEvjo9mUA%3D';
		$this->assertEqual($expected, $results['log'][0]);
	}

/**
 * testRequest
 *
 * @return void
 * @access public
 */
	function testRequest() {
		$query = array(
		    'Service' => 'AWSECommerceService',
		    'AWSAccessKeyId' => 'PUBLICKEY',
		    'Timestamp' => '2010-03-01T07:44:03Z',
		    'AccociateTag' => 'ASSID',
		    'Version' => '2009-03-31',
		    'Operation' => 'ItemSearch',
		);
		$results = $this->Amazon->find(null, $query);
		$resultsType = gettype($results);
		$expected = array('array', 'boolean');
		$this->assertContains($resultsType, $expected);
	}

/**
 * End Test
 *
 * @return void
 * @access public
 */
	function endTest($method){
		unset($this->Amazon);
	}
}
