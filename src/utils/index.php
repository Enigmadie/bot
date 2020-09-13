<?php
namespace Bot\Utils;

function mb_lcfirst($str) {
    return mb_strtolower(mb_substr($str, 0, 1)) . mb_substr($str, 1);
}
