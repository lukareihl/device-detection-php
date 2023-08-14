<?php
/* *********************************************************************
 * This Original Work is copyright of 51 Degrees Mobile Experts Limited.
 * Copyright 2023 51 Degrees Mobile Experts Limited, Davidson House,
 * Forbury Square, Reading, Berkshire, United Kingdom RG1 3EU.
 *
 * This Original Work is licensed under the European Union Public Licence
 * (EUPL) v.1.2 and is subject to its terms as set out below.
 *
 * If a copy of the EUPL was not distributed with this file, You can obtain
 * one at https://opensource.org/licenses/EUPL-1.2.
 *
 * The 'Compatible Licences' set out in the Appendix to the EUPL (as may be
 * amended by the European Commission) shall be deemed incompatible for
 * the purposes of the Work and the provisions of the compatibility
 * clause in Article 5 of the EUPL shall not apply.
 *
 * If using the Work as, or as part of, a network application, by
 * including the attribution notice(s) required under Article 5 of the EUPL
 * in the end user terms of the application under an appropriate heading,
 * such notice(s) shall fulfill the requirements of that article.
 * ********************************************************************* */

namespace fiftyone\pipeline\devicedetection\tests;

use fiftyone\pipeline\core\tests\classes\HeaderParser;
use fiftyone\pipeline\devicedetection\tests\classes\Constants;
use fiftyone\pipeline\devicedetection\tests\classes\Process;
use PHPUnit\Framework\TestCase;

/**
 * @requires OS Linux
 */
class ExampleWebTests extends TestCase
{
    public static $process;

    public static function setUpBeforeClass(): void
    {
        // start server
        self::$process = new Process('php -S localhost:3000 examples/cloud/gettingStartedWeb.php');
        self::$process->start();
        if (self::$process->status()) {
            shell_exec('lsof -i tcp:3000 1>/dev/null 2>&1');
            echo "Getting Started Web example has started running.\n";
        } else {
            throw new \Exception("Could not start the Getting Started Web example. \n");
        }
    }

    public static function tearDownAfterClass(): void
    {
        // stop server
        if (self::$process->stop()) {
            echo "\nProcess stopped for Getting Started Web example. \n";
        }
    }

    public function testGettingStartedWeb()
    {
        $requestHeaders = Constants::UA_HEADER . Constants::CHROME_UA . '\r\n';

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => $requestHeaders
            ]
        ]);

        $data = @file_get_contents(Constants::URL, false, $context);
        $responseHeaders = HeaderParser::parse($http_response_header);

        $this->assertEquals(200, $responseHeaders['response_code']);
    }
}
