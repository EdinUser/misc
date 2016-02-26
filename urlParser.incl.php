<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of urlParser
 *
 * @author Todor Kirilov
 */
class urlParser {
    //put your code here
    
    /**
     * Simple URL parser. Parse url as follows: /[module]/[switch]/[param1]:[value1]/[param2]:[/value2]...
     * @param string $url URL to be parsed
     * @return array Array with data, fecthed from URL
     */
    function parseUrlForResults($url) {

	if ($_SERVER["REQUEST_URI"]) {
	    $requestpath = $_SERVER["REQUEST_URI"];
	    $requestpath = preg_replace("/^\//", '', $requestpath);
	    $requestpath = preg_replace("/\/$/", '', $requestpath);
	} else {
	    $requestpath = $url;
	    $requestpath = preg_replace("/^\//", '', $requestpath);
	    $requestpath = preg_replace("/\/$/", '', $requestpath);
	}

	$returnArray['url'] = $requestpath;
	if (preg_match("/.*?\/p(\d*?)$/", $requestpath, $matches)) {
	    /* Pagination */
	    $returnArray['page'] = $matches[1];
	}
	
	/*
	 * Breadcumb generation - the method generates a breadcrumb sub-array also
	 */
	
	$modulesNames = array(
	    "module" => "module_description",
	);

	$methodsNames = array(
	    "module" => array(
		"method1" => "Method 1 description",
		"method2" => "Method 2 description",
		"method3" => "Method 3 description",
	    ),
	);

	$returnArray['bread']['Home'] = "/";

	$getUrlDetails = explode("/", $requestpath);

	if (!empty($getUrlDetails[0])) {
	    /* Fetched module name */
	    $returnArray['module'] = $getUrlDetails[0];
	    $currentName = $modulesNames[$getUrlDetails[0]];
	    if (!empty($currentName)) {
		$returnArray['bread'][$currentName] = $getUrlDetails[0];
	    } else {
		$returnArray['bread'][$getUrlDetails[0]] = $getUrlDetails[0];
	    }
	}


	if (!empty($getUrlDetails[1])) {
	    /* Fetched method name */
	    $returnArray['switch'] = $getUrlDetails[1];

	    $currentMethodName = $methodsNames[$getUrlDetails[0]][$getUrlDetails[1]];
	    if (!empty($currentMethodName)) {
		if (!empty($getUrlDetails[2])) {
		    $returnArray['bread'][$currentMethodName] = "/" . $getUrlDetails[1] . "/" . $getUrlDetails[2];
		} else {
		    $returnArray['bread'][$currentMethodName] = "/" . $getUrlDetails[1];
		}
	    } else {
		if (!empty($getUrlDetails[2])) {
		    $returnArray['bread'][$getUrlDetails[1]] = "/" . $getUrlDetails[1] . "/" . $getUrlDetails[2];
		} else {
		    $returnArray['bread'][$getUrlDetails[1]] = "/" . $getUrlDetails[1];
		}
	    }
	}

	for ($i = 2; $i < count($getUrlDetails); $i++) {
	    /* Parse the rest of the URL for 'pairs' and putting them in the return array */
	    $getPairs = explode(":", $getUrlDetails[$i]);
	    $returnArray['params'][$getPairs[0]] = $getPairs[1];
	}
	return $returnArray;
    }

    /**
     * 
     * @param array $params Array with params, from whic to build the URL. Format: $array['module'], $array['switch'], $array['params']['param1]=$value1;
     * @return string The url, which can be parced by @internal parseUrlForResults
     */
    function buildUrlByParams($params) {
	if (!empty($params['module'])) {
	    $newUrl = "/" . $params['module'];
	}
	if (!empty($params['switch'])) {
	    $newUrl .= "/" . $params['switch'];
	}

	foreach ($params['params'] as $paramName => $paramValue) {
	    $newUrl .= "/" . $paramName . ":" . $paramValue;
	}

	return $newUrl;
    }
}
