<?php
/* *********************************************************************
 * This Original Work is copyright of 51 Degrees Mobile Experts Limited.
 * Copyright 2019 51 Degrees Mobile Experts Limited, 5 Charlotte Close,
 * Caversham, Reading, Berkshire, United Kingdom RG4 7BY.
 *
 * This Original Work is licensed under the European Union Public Licence (EUPL) 
 * v.1.2 and is subject to its terms as set out below.
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

/**
 * @example cloud/webIntegration.php
 * 
 * In this example we create a 51Degrees device detection pipeline and use it 
 * to test if a device is a mobile device. We also include any JavaScript in the 
 * page that can help tell us more about a user's device from the client side.
 *
**/

// First we include the deviceDetectionPipelineBuilder

require(__DIR__ . "/../../vendor/autoload.php");
use fiftyone\pipeline\devicedetection\deviceDetectionPipelineBuilder;

// Create your own recource key for free at https://configure.51degrees.com. 
$resourceKey = "!!YOUR_RESOURCE_KEY!!";
// Check if there is a resource key in the enviornemnt variable and use
// it if there is one.
$envKey = getenv("RESOURCEKEY");
if($envKey !== false){
    $resourceKey = $envKey;
}

if(substr($resourceKey, 0, 2) == "!!") {
    echo "You need to create a resource key at " .
        "https://configure.51degrees.com and paste it into the code, " .
        "replacing !!YOUR_RESOURCE_KEY!!.";
    echo "</br>";
    echo "Make sure to include the IsMobile, ScreenPixelWidth and " .
        "and HardwareName properties as they are used by this example.";
}
else 
{
    $builder = new deviceDetectionPipelineBuilder(array(
        "resourceKey" => $resourceKey,
    ));

    // Next we build the pipeline. We could additionally add extra engines and/or
    // flowElements here before building.
    $pipeline = $builder->build();

    // We create the flowData object that is used to add evidence to and read
    // data from 
    $flowData = $pipeline->createFlowData();

    // We set headers, cookies and more information from the web request
    $flowData->evidence->setFromWebRequest();

    // Now we process the flowData
    $result = $flowData->process();

    // First we check if the property we're looking for has a meaningful result

    if($result->device->hardwarename->hasValue){

        // This should be a list of devices

        var_dump($result->device->hardwarename->value);

    } else {

        // If it doesn't have a meaningful result, we echo out the reason why 
        // it wasn't meaningful

        echo($result->device->ismobile->noValueMessage);

    }

    // We get any JavaScript that should be placed in the page and run it, this 
    // will set cookies and other information allowing us to access extra 
    // properties such as device->screenpixelwidth.

    echo "<script>" . $flowData->javascriptbuilder->javascript . "</script>";
}