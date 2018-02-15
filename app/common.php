<?php
/**
 * Created by PhpStorm.
 * User: Johnson
 * Date: 2017/12/2
 * Time: 22:47
 */

/**
 * 获取文章
 * @param $url
 * @return mixed
 */
function get_URL($url)
{ //远程读取文件
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $file_contents = curl_exec($ch);
    curl_close($ch);
    return $file_contents;
}

/**
 * utf->gbk
 * @param $str
 * @return mixed|string
 */
function utf8_to_gbk($str){
    return mb_convert_encoding($str, 'gbk', 'utf-8');
}

/**
 * gbk->utf8
 * @param $str
 * @return mixed|string
 */
function gbk_to_utf8($str){
    return mb_convert_encoding($str, 'utf-8', 'gbk');
}

/**
 * 文本转意
 * @param $out_str
 * @return mixed
 */
function hh_out($out_str){
    return str_replace(array("\r\n", "\r", "\n"), "", $out_str);
}
