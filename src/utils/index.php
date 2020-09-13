<?php
namespace Bot\Utils;

function mb_lcfirst($str) {
    return mb_strtolower(mb_substr($str, 0, 1)) . mb_substr($str, 1);
}

function mb_ucfirst($str) {
    return mb_strtoupper(mb_substr($str, 0, 1)) . mb_substr($str, 1);
}

function format_city_name($city) {
    switch($city) {
    case 'спб':
    case 'питер':
    case 'петербург':
      return 'Санкт-Петербург';
    case 'мск':
    case 'москоу':
      return 'Москва';
    case 'екб':
        return 'Екатеринбуург';
    default:
      return $city;
    }
}
