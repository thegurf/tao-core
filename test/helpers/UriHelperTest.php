<?php
/**  
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 * 
 * Copyright (c) 2008-2010 (original work) Deutsche Institut für Internationale Pädagogische Forschung (under the project TAO-TRANSFER);
 *               2009-2012 (update and modification) Public Research Centre Henri Tudor (under the project TAO-SUSTAIN & TAO-DEV);
 *               2013-2015 (update and modification) Open Assessment Technologies SA;
 */

namespace oat\tao\test\helpers;

use oat\tao\test\TaoPhpUnitTestRunner;
use tao_helpers_Uri;

/**
 *
 * @author Jerome Bogaerts, <taosupport@tudor.lu>
 * @package tao
 *         
 */
class UriHelperTestCase extends TaoPhpUnitTestRunner
{
    /**
     * 
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        parent::setUp();
        TaoPhpUnitTestRunner::initTest();
    }
    
    /**
     * 
     * @author Lionel Lecaque, lionel@taotesting.com
     * @return array
     */
    public function uriDomainProvider(){
        return array(
            array(
                'http://www.google.fr',
                'www.google.fr'
            ),
            array(
                'http://www.google.fr/translate',
                'www.google.fr'
            ),
            array(
                'http://www.google.fr/translate?word=yes',
                'www.google.fr'
            ),
            array(
                'ftp://sub.domain.filetransfer.ulc.ag.be',
                'sub.domain.filetransfer.ulc.ag.be'
            ),
            array(
                'http://mytaoplatform',
                'mytaoplatform'
            )
        );
    }
    
    /**
     * @dataProvider uriDomainProvider
     * @author Lionel Lecaque, lionel@taotesting.com
     */
    public function testUriDomain($uri, $domain)
    {
        $this->assertEquals($domain, tao_helpers_Uri::getDomain($uri));
    }
    
    /**
     * 
     * @author Lionel Lecaque, lionel@taotesting.com
     */
    public function testUriDomainNotADomain()
    {
        $uri = 'flupsTu8tridou:kek';
        $domain = tao_helpers_Uri::getDomain($uri);
        $this->assertNull($domain, "domain should be null but is equal to '${domain}'.");
    }
    
    /**
     * 
     * @author Lionel Lecaque, lionel@taotesting.com
     */
    public function uriPathProvider(){
        return array(
            array(
                'http://www.google.fr/',
                '/'
            ),
            array(
                'http://www.google.fr/translate',
                '/translate'
            ),
            array(
                'http://www.google.fr/translate?word=yes',
                '/translate'
            ),
            array(
                'http://www.google.fr/translate/funky?word=yes',
                '/translate/funky'
            ),
            array(
                'http://www.google.fr/translate/funky/?word=yes',
                '/translate/funky/'
            ),           
            array(
                'ftp://sub.domain.filetransfer.ulc.ag.be/',
                '/'
            ),
            array(
                'http://mytaoplatform/',
                '/'
            )
        );
    }
    
    /**
     * @dataProvider uriPathProvider
     * @author Lionel Lecaque, lionel@taotesting.com
     */
    public function testUriPath($uri,$path)
    {
        $this->assertEquals($path, tao_helpers_Uri::getPath($uri));
    }
    
    /**
     *
     */
    public function uriPathProviderNull()
    { 
        return array(
            array('flupsTu8tridoujkek'),
            array('ftp://sub.domain.filetransfer.ulc.ag.be'),
            array('http://mytaoplatform'),
            array('http://www.google.fr')
        );
    }
    
    /**
     * @dataProvider uriPathProviderNull
     * @author Lionel Lecaque, lionel@taotesting.com
     */
    public function testUriPathNull($uri)
    {
        $this->assertNull(tao_helpers_Uri::getPath($uri));
    }
    
    /**
     *
     * @author Lionel Lecaque, lionel@taotesting.com
     * @return array
     */
    public function uriEncodeProvider()
    {
        return array(
            array(
                'http://www.google.fr',
                'http_2_www_0_google_0_fr'
            ),
            array(
                'http://www.google.fr/translate',
                'http_2_www_0_google_0_fr_1_translate'
            ),
            array(
                'http://www.google.fr/translate?word=yes',
                'http_2_www_0_google_0_fr_1_translate?word=yes'
            ),
            array(
                'ftp://sub.domain.filetransfer.ulc.ag.be',
                'ftp://sub.domain.filetransfer.ulc.ag.be'
            ),
            array(
                'http://mytaoplatform',
                'http_2_mytaoplatform'
            ),
            array(
                'http://mytaoplatform/tao.rdf#toto',
                'http_2_mytaoplatform_1_tao_0_rdf_3_toto'
            ),
            array(
                'http://mytao.platform/tao.rdf#toto',
                'http_2_mytao_0_platform_1_tao_0_rdf_3_toto'
            ),
        );
    }
    
    /**
     * @dataProvider uriEncodeProvider
     * @author Lionel Lecaque, lionel@taotesting.com
     */
    public function testEncode($uri,$result)
    {
        $this->assertEquals($result, tao_helpers_Uri::encode($uri));

    }
    
    /**
     * @dataProvider uriEncodeProvider
     * @author Lionel Lecaque, lionel@taotesting.com
     */
    public function testDecode($uri,$result)
    {
        $this->assertEquals($uri, tao_helpers_Uri::decode($result));
    
    }
    
    /**
     *
     * @author Lionel Lecaque, lionel@taotesting.com
     * @return array
     */
    public function uriEncodeProviderDotMode()
    {
        return array(
            array(
                'http://www.google.fr',
                'http_2_www.google.fr'
            ),
            array(
                'http://www.google.fr/translate',
                'http_2_www.google.fr_1_translate'
            ),
            array(
                'http://www.google.fr/translate?word=yes',
                'http_2_www.google.fr_1_translate?word=yes'
            ),
            array(
                'ftp://sub.domain.filetransfer.ulc.ag.be',
                'ftp://sub.domain.filetransfer.ulc.ag.be'
            ),
            array(
                'http://mytaoplatform',
                'http_2_mytaoplatform'
            ),
            array(
                'http://mytaoplatform/tao.rdf#toto',
                'http_2_mytaoplatform_1_tao.rdf_3_toto'
            ),
            array(
                'http://mytao.platform/tao.rdf#toto',
                'http_2_mytao.platform_1_tao.rdf_3_toto'
            ),
        );
    }
    
    /**
     * @dataProvider uriEncodeProviderDotMode
     * @author Lionel Lecaque, lionel@taotesting.com
     */
    public function testEncodeDotMode($uri,$result)
    {
        $this->assertEquals($result, tao_helpers_Uri::encode($uri,false));
    
    }
    
    /**
     * @dataProvider uriEncodeProviderDotMode
     * @author Lionel Lecaque, lionel@taotesting.com
     */
    public function testDecodeDotMode($uri,$result)
    {
        $this->assertEquals($uri, tao_helpers_Uri::decode($result,false));
    
    }
    
    /**
     * 
     * @author Lionel Lecaque, lionel@taotesting.com
     */
    public function testIsValidAsCookieDomain()
    {
        $this->assertFalse(tao_helpers_Uri::isValidAsCookieDomain('http://mytaoplatform'));
        $this->assertFalse(tao_helpers_Uri::isValidAsCookieDomain('http://my-tao-platform'));
        $this->assertFalse(tao_helpers_Uri::isValidAsCookieDomain('mytaoplatform'));
        $this->assertFalse(tao_helpers_Uri::isValidAsCookieDomain('mytaoplatform/items/'));
        $this->assertFalse(tao_helpers_Uri::isValidAsCookieDomain(''));
        $this->assertTrue(tao_helpers_Uri::isValidAsCookieDomain('http://mytaoplatform.com'));
        $this->assertTrue(tao_helpers_Uri::isValidAsCookieDomain('http://my-tao-platform.ru'));
        $this->assertTrue(tao_helpers_Uri::isValidAsCookieDomain('http://www.mytaoplatform.com'));
        $this->assertTrue(tao_helpers_Uri::isValidAsCookieDomain('http://www.-my-tao-platform.ru'));
        $this->assertTrue(tao_helpers_Uri::isValidAsCookieDomain('http://my.taoplatform.com'));
        $this->assertTrue(tao_helpers_Uri::isValidAsCookieDomain('http://my.tao.platform.qc.ca'));
        $this->assertTrue(tao_helpers_Uri::isValidAsCookieDomain('http://my.TAO.plAtfOrm.qc.cA'));
        $this->assertFalse(tao_helpers_Uri::isValidAsCookieDomain('http://.my.tao.platform.qc.ca'));
    }
}
?>