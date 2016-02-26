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
     * Simple URL parser. Parse url as follows: /[module]/[swicth]/[param1]:[value1]/[param2]:[/value2]...
     * @param string $url URL to be parsed
     * @return array Array with data, fecthed from URL
     */
    function parseUrlForResults($url) {

	$modulesNames = array(
	    "building" => "Сгради",
	    "list" => "Списъци с имоти",
	    "search" => "Търсене",
	    "admin" => "Администрация",
	    "internalSearch" => "Вътрешна търсачка",
	    "agency" => "Агенции/Строители",
	    "activity" => "Регистър на дейности",
	    "blog" => "Блог",
	    "client" => "Клиенти",
	);

	$methodsNames = array(
	    "building" => array(
		"searchBuilding" => "Търсене на сграда",
		"viewBuilding" => "Преглед на сграда",
		"editBuilding" => "Редакция на сграда",
	    ),
	    "admin" => array(
		"priviliges" => "Права на потребители",
		"transferProperties" => "Синхронизация на оферти",
		"transferClients" => "Синхронизация на клиенти",
	    ),
	    "agency" => array(
		"showAgency" => "Профил на агенция/строител",
		"editAgency" => "Редакция/Добавяне на агенция/строител",
	    ),
	    "activity" => array(
		"showActions" => "Списък по критерии",
	    ),
	    "blog" => array(
		"viewArticle" => "Публикация",
	    ),
	    "client" => array(
		"viewBirthdays" => "Списък с рожденици",
		"clientProfile" => "Профил на клиент",
		"editClient" => "Редакция/Добавяне на клиент",
	    ),
	);

	if ($_SERVER["REQUEST_URI"]) {
	    $requestpath = $_SERVER["REQUEST_URI"];
	    $requestpath = preg_replace("/^\//", '', $requestpath);
	    $requestpath = preg_replace("/\/$/", '', $requestpath);
	} else {
	    $requestpath = $url;
	    $requestpath = preg_replace("/^\//", '', $requestpath);
	    $requestpath = preg_replace("/\/$/", '', $requestpath);
	}

//	preg_match("/^(?P<module>[\s\S]*)?\/(?P<switch>[\s\S]*)?\/?(?P<params>[\s\S]*)$/USs", $url, $getURLresults);
//	print_r($getURLresults);
	$returnArray['url'] = $requestpath;
	if (preg_match("/.*?\/p(\d*?)$/", $requestpath, $matches)) {
	    $returnArray['page'] = $matches[1];
	}
	$returnArray['bread']['Начало'] = "/";

	$getUrlDetails = explode("/", $requestpath);

	if (!empty($getUrlDetails[0])) {
	    $returnArray['module'] = $getUrlDetails[0];
	    $currentName = $modulesNames[$getUrlDetails[0]];
	    if (!empty($currentName)) {
		$returnArray['bread'][$currentName] = $getUrlDetails[0];
	    } else {
		$returnArray['bread'][$getUrlDetails[0]] = $getUrlDetails[0];
	    }
	}


	if (!empty($getUrlDetails[1])) {
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
	    $getPairs = explode(":", $getUrlDetails[$i]);
	    $returnArray['params'][$getPairs[0]] = $getPairs[1];
	}
//	print_r($getUrlDetails);
//	print_r($returnArray);
	return $returnArray;
    }

}
