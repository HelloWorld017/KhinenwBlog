<?php
include_once "config.php";
use \khinenw\blog\Config;

function doclist(array $docs, $currentPage = 0){

    if($currentPage < 0) $currentPage = 0;

    $wholePage = ceil(count($docs) / Config::$articleInOnePage);
    if($currentPage > $wholePage) $currentPage = $wholePage;

    $array = array_chunk($docs, Config::$articleInOnePage);

    foreach($array[$currentPage] as $entry){
        $arr = explode(".", $entry);

        echo '<a name="'.$arr[0].'"></a>';
        echo '<iframe src="show_shortdoc.php?id='.$arr[0].'" frameborder="0" width="100%" onload="autoResize(this)" scrolling="no"></iframe>';
    }

    echo "<br>";

    if($currentPage + 1 < $wholePage) echo "<a class='page-navigation' href='?page=" . ($currentPage + 1) . "'>&lt;</a>";
    echo $currentPage + 1;
    if($currentPage > 0) echo "<a class='page-navigation' href='?page=" . ($currentPage - 1) . "'>&lt;</a>";
}
