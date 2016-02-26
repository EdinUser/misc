<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pagination
 *
 * @author Todor Kirilov
 */
class pagination {

    /** @global int $on_page Default items to be displayed*/
    var $on_page = 10;

    function __construct() {
	$this->chtml = tpl::GetFile(DefaultTplDir . "paging");
    }

    /**
     *
     * @global type $sp Page number
     * @param array $arrayToBeSplitted Array with unique IDs to be processed
     * @param string $baseUrl URL for the buttons
     * @param int $on_page How many results to be displayed
     * @return array Two-dimensional array - resultArray contains array with IDs, resultHtml - buttons
     */
    function doPagination($arrayToBeSplitted = array(), $baseUrl = '/', $on_page = 10) {
	global $sp;
//	echo $baseUrl . "<hr />";

	if (!empty($on_page)) {
	    $this->on_page = $on_page;
	}

	$getUrlDetails = utils::parseUrlForResults();
//	print_r($getUrlDetails);
	if (!empty($getUrlDetails['params']['p'])) {
	    $baseUrl = "/".$getUrlDetails['url'];
	    $sp = intval($getUrlDetails['params']['p']);
	    $baseUrl = preg_replace("/(.*?)\/p:(\d*?)$/", "\\1", $baseUrl);
	}
	elseif (preg_match("/.*?\/p(\d*?)$/", $baseUrl, $matches)) {
	    $sp = $matches[1];
	    $baseUrl = preg_replace("/(.*?)\/p(\d*?)$/", "\\1", $baseUrl);
	} else {
	    $sp = 1;
	}

	$allPages = ceil(count($arrayToBeSplitted) / $this->on_page);

	$chtml = $this->chtml;
	$button_tpl = tpl::GetFromTemplate("button", $chtml);

	if (count($arrayToBeSplitted) > $this->on_page) {
	    if (!$sp)
		$sp = 1;
	    $pages = $allPages;

	    $paging = "";
	    $pagingend = '';
	    if ($sp > 5) {
		if ($sp > $pages - 5)
		    $start = $sp - (9 - ($pages - $sp));
		else
		    $start = $sp - 4;
		if ($start > 2) {
		    $but = $button_tpl;
		    $but = str_replace("{but_url}", "$baseUrl/p:" . ($start - 1), $but);
		    $but = str_replace("{but_value}", "<i class='fa fa-angle-left'></i>", $but);
		    $paging .= $but;
		    $chtml = tpl::UseInTemplate("firstpage", $chtml);
		}
	    } else {
		$start = 1;
	    }

	    if ($sp < $pages - 5) {
		if ($sp < 5)
		    $end = 10;
		else
		    $end = $sp + 5;
		if ($end < $pages - 1) {
		    $but = $button_tpl;
		    $but = str_replace("{but_url}", "$baseUrl/p:" . ($end + 1), $but);
		    $but = str_replace("{but_value}", "<i class='fa fa-angle-right'></i>", $but);
		    $pagingend = $but;
		}
		if ($end > $pages)
		    $end = $pages;
		$chtml = tpl::UseInTemplate("lastpage", $chtml);
		$chtml = str_replace("{lastpage}", $pages, $chtml);
	    } else {
		$end = $pages;
	    }

	    if ($start < 1)
		$start = 1;
	    for ($i = $start; $i <= $end; $i++) {
		$but = $button_tpl;
		if ($i == $sp) {
		    $but = str_replace("{but_url}", "$baseUrl/p:" . $i, $but);
		    $but = str_replace("{but_value}", $i, $but);
		    $but = str_replace("{setclass}", "active", $but);
		} else {
		    $but = str_replace("{but_url}", "$baseUrl/p:" . $i, $but);
		    $but = str_replace("{but_value}", "$i", $but);
		}

		$paging .= $but;
	    }
	    $paging .= $pagingend;
	} else
	    $paging = '';

	$chtml = tpl::InsertIntoTemplate("button", $paging, $chtml);

	$chtml = str_replace("{baseurl}", $baseUrl, $chtml);

	$index = ($sp * $this->on_page) - 1;
	$recordsToBeDisplayed = array_chunk($arrayToBeSplitted, $this->on_page, true);
//var_dump($chtml);

	return array(
	    "resultArray" => $recordsToBeDisplayed[$sp - 1],
	    "resultHtml" => tpl::ClearTemplate($chtml)
	);
    }

}
