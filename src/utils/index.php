<?php

namespace Bot\Utils;

function mb_lcfirst($str) {
  return mb_strtolower(mb_substr($str, 0, 1)) . mb_substr($str, 1);
}

function mb_ucfirst($str) {
  return mb_strtoupper(mb_substr($str, 0, 1)) . mb_substr($str, 1);
}

function isAssoc(array $arr)
{
    if (array() === $arr) return false;
    return array_keys($arr) !== range(0, count($arr) - 1);
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

function select_vk_icon($icon) {
  switch($icon) {
    case 1:
    case 2:
    case 33:
    case 34:
      return '&#9728;'; //Sun/moon
    case 3:
    case 4:
    case 5:
    case 35:
    case 36:
    case 37:
      return '&#127780;'; //Mostly sun/moon
    case 6:
    case 38;
      return '&#9925;'; //Low cloud
    case 7:
      return '&#127781;'; //Middle cloud
    case 8:
      return '&#9729;'; //Mostly cloud
    case 11:
      return '&#127787;'; //Fog
    case 12:
    case 18:
      return '&#127783;'; //Rain
    case 13:
    case 14:
    case 39:
    case 40:
      return '&#127782;'; //Cloud and rain
    case 15:
    case 16:
    case 17:
    case 41:
    case 42;
      return '&#9928;'; //Storm
    case 19:
    case 20:
    case 21:
    case 32:
    case 43:
      return '&#127788;'; //Wind
    case 22:
    case 23:
    case 25:
    case 26:
    case 29:
    case 44:
      return '&#127784'; //snow
    case 24:
      return '&#10052;'; //ice
    case 30:
    case 31:
      return '&#127777;'; //temperature
    default:
      return '&#9728;';
  }
}
/* function get_weather_icon($weather) { */

/* } */
