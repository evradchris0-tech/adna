<?php


if (!function_exists("formatNumber")) {
    function formatNumber($number, $decimal = 0, $decimalSep = ",", $thousandSep = " " ){
        return number_format($number, $decimal, $decimalSep, $thousandSep);
    }
}
