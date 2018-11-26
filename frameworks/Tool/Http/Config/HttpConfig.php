<?php
/**
 * http配置
 * Date: 2018/11/16
 * Time: 22:59
 */
namespace Frameworks\Tool\Http\Config;

class HttpConfig
{
    const METHOD_GET    = 0;
    const METHOD_POST   = 1;
    const METHOD_BOTH   = 2;
    const PARAM_NUMBER_TYPE     = 0;
    const PARAM_NUMBER_DEFAULT  = 0;
    const PARAM_TEXT_TYPE       = 1;
    const PARAM_TEXT_DEFAULT    = '';
    const PARAM_FILE_TYPE       = 2;
    const PARAM_FILE_DEFAULT    = '';

    const METHOD_CURL_POST_STRING   = 0;
    const METHOD_CURL_POST_ASYNC    = 1;
    const METHOD_CURL_POST_ARRAY    = 2;

    public static function getMethodList()
    {
        return [static::METHOD_GET, static::METHOD_POST, static::METHOD_BOTH];
    }
}