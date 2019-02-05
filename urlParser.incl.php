<?php

/**
 * Description of urlParser
 * Nice litle class for building and parsing URLs. Breaks url of type /module/switch/param1:value1/param2/value2,value3 to
 * array(
 *	'url' = '/module/switch/param1:value1/param2/value2,value3',
 	'module' = 'module',
	'switch' = 'switch'.
	'params' = array(
		'param1' => 'value1',
		'param2' => array(
			'value2' => 'value2',
			'value3' => 'value3'
			)
		),
	'bread' = array(
		'Home' => '/',
		'module' => '/module',
		'switch' => '/module/switch/param1:value1/param2/value2,value3'
		)
 	)
 * @author Todor Kirilov
 */
class urlParser {
    //put your code here
    
    /**
     * Simple URL parser. Parse url as follows: /[module]/[switch]/[param1]:[value1]/[param2]:[/value2]...
     * @param string $url URL to be parsed
     * @param boolean $skipDigitConvert Default - true. If "false" - will convert number values to int(). Keep in mind this can break some values like "0"
     * @return array Array with data, fecthed from URL
     */
    function parseUrlForResults($url = '', $skipDigitConvert = true) {

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

	// iterate all pairs in the url
	for ($i = 2; $i < count($getUrlDetails); $i++) {
            $getPairs = explode(":", $getUrlDetails[$i]);
		// check if the values are multiple (contain [param]/[value1],[value2])
            if (stripos($getPairs[1], ",") !== false) {
                $returnValues = explode(",", $getPairs[1]);
                $explodedValue = array();

                foreach ($returnValues as $valueData) {
		    // if convert to digit is false do not convert detected values...
                    if ($skipDigitConvert === true) {
                        $explodedValue[] = htmlspecialchars($valueData, ENT_QUOTES);
                    } else {
			// check if the value is digit
                        if (ctype_digit($valueData)) {
                            $explodedValue[] = intval($valueData);
                        } else {
                            $explodedValue[] = htmlspecialchars($valueData, ENT_QUOTES);
                        }
                    }
                }
		// populate the 'params' array with values
                $returnArray['params'][$getPairs[0]] = $explodedValue;
            } else {
		    // single value [param]/[value]
		    // if convert to digit is false do not convert detected values...
                if ($skipDigitConvert === true) {
                    $value = htmlspecialchars($getPairs[1], ENT_QUOTES);
                } else {
			// check if the value is digit
                    if (ctype_digit($getPairs[1])) {
                        $value = intval($getPairs[1]);
                    } else {
                        $value = htmlspecialchars($getPairs[1], ENT_QUOTES);
                    }
                }
		    
		    // populate the 'params' array with values
                $returnArray['params'][$getPairs[0]] = $value;
            }
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
