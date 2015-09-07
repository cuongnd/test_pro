<?php
/*
 * Copyright 2011 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

require_once JPATH_ROOT . '/libraries/phpunit-master/src/Framework/TestCase.php';
require_once JPATH_ROOT . '/libraries/google-api-php-client-master/src/Google/Service/Pagespeedonline.php';
require_once JPATH_ROOT . '/libraries/google-api-php-client-master/tests/BaseTest.php';

class PageSpeedTest extends BaseTest
{
    public $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new Google_Service_Pagespeedonline($this->getClient());
    }

    public function testPageSpeed()
    {
        //$this->checkToken();
        $psapi = $this->service->pagespeedapi;

        $result = $psapi->runpagespeed('http://vatgia.com',
            array(
                'screenshot'=>true
                ,'width'=>300
            )
        );

        $this->assertArrayHasKey('kind', $result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('responseCode', $result);
        $this->assertArrayHasKey('title', $result);
        $this->assertArrayHasKey('score', $result);
        $this->assertInstanceOf('Google_Service_Pagespeedonline_ResultPageStats', $result->pageStats);
        $this->assertInstanceOf('Google_Service_Pagespeedonline_ResultScreenshot', $result->screenshot);
        $this->assertArrayHasKey('minor', $result['version']);
        $result->screenshot->setWidth(300);
        $imageData=$result->screenshot->getData();



        $imageData=(str_pad(strtr($imageData, '-_', '+/'), strlen($imageData) % 4, '=', STR_PAD_RIGHT));

        //$imageData=str_replace('_','/',$imageData);
        ?>
        <img src="data:image/jpeg;base64,<?php echo $imageData ?>" />
<?php
        die;

    }
}
