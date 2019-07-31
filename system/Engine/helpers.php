<?php
/**
 * @author  Samir Rustamov <rustemovv96@gmail.com>
 * @link    https://github.com/srustamov/TT
 * @category Helper functions
 */


use System\Engine\App;
use System\Engine\Load;

function app(string $class = null)
{
    $app = App::instance();

    if ($class === null) {
        return $app;
    }

    return $app[$class];
}

/**
 * @param String|null $class
 * @param mixed ...$args
 * @return mixed|Load
 * @throws Exception
 */
function load(string $class = null, ...$args)
{
    if ($class === null) {
        return Load::instance();
    }

    return Load::class($class,...$args);
}


if (!function_exists('getallheaders')) {

    function getallheaders() {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (strpos($name, 'HTTP_') === 0) {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}


/**
 * @param String|null $name
 * @param null $default
 * @return mixed
 * @throws Exception
 */
function config(String $name = null, $default = null)
{
    if ($name === null) {
        return Load::class('config');
    }
    return Load::class('config')->get($name, $default);
}


function setting($key, $default = null)
{
    return $_ENV[$key] ?? $default;
}


/**
 * @param String $file
 * @param bool $once
 * @return mixed
 * @throws Exception
 */
function import(String $file, $once = true)
{
    return load('file')->import($file, $once);
}


/**
 * @param $directory
 * @param bool $once
 * @throws Exception
 */
function importFiles($directory, $once = true)
{
    foreach (glob(rtrim($directory, DIRECTORY_SEPARATOR)."/*") as $file) {
        import($file, $once);
    }
}



function storage_path($path = '')
{
    return App::instance()->storagePath($path);
}



function app_path($path = '')
{
    return App::instance()->appPath($path);
}



function public_path($path = '')
{
    return App::instance()->publicPath($path);
}



function path($path = '')
{
    return App::instance()->path($path);
}


/**
 * @param Int $http_code
 * @param null $message
 * @param array $headers
 * @throws Exception
 */
function abort(Int $http_code, $message = null, $headers = [])
{
    if (file_exists(app_path('Views/errors/'.$http_code.'.blade.php'))) {
        $content =  view('errors.'.$http_code);
    }

    $response = Load::class('response')->setStatusCode($http_code, $message);

    $response->withHeaders($headers);

    $response->setContent($content ?? null);

    $response->send();

    App::instance()->end();
}



function inConsole()
{
    return CONSOLE;
}


/**
 * @return String
 * @throws Exception
 */
function csrf_token():String
{
    static $token;

    if ($token === null) {
        $token = Load::class('session')->get('_token');
    }

    return $token;
}



function csrf_field():String
{
    return '<input type="hidden" name="_token" value="' . csrf_token() . '" />';
}


/**
 * @param $name
 * @param array $parameters
 * @return mixed
 * @throws Exception
 */
function route($name, array $parameters = [])
{
    return Load::class('route')->getName($name, $parameters);
}



if (!function_exists('flash')) {
    /**
     * @param $key
     * @return mixed
     * @throws Exception
     */
    function flash($key)
    {
        return Load::class('session')->flash($key);
    }
}


if (!function_exists('is_base64')) {
    /**
     * @param String $string
     * @return bool
     */
    function is_base64(String $string):Bool
    {
        return base64_encode(base64_decode($string)) === $string;
    }
}



if (!function_exists('response')) {
    /**
     * @return mixed
     * @throws Exception
     */
    function response()
    {
        return Load::class('response',...func_get_args());
    }
}


if (!function_exists('json')) {
    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    function json($data)
    {
        return Load::class('response')->json($data);
    }
}


if (!function_exists('report')) {
    /**
     * @param String $subject
     * @param String $message
     * @param null $destination
     * @return mixed
     * @throws Exception
     */
    function report(String $subject, String $message, $destination = null)
    {
        if (empty($destination)) {
            $destination = str_replace(' ', '-', $subject);
        }

        $logDir = path('storage/logs/');

        $extension = '.report';

        if (!is_dir($logDir) && !mkdir($logDir, 0755, true) && !is_dir($logDir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $logDir));
        }

        $report = '----------------------------' . PHP_EOL .
                  ' Report                     ' . PHP_EOL .
                  '----------------------------' . PHP_EOL .
                  '|IP: ' . Load::class('http')->ip() . PHP_EOL .
                  '|Subject: ' . $subject . PHP_EOL .
                  '|File: ' . debug_backtrace()[ 0 ][ 'file' ] ?? '' . PHP_EOL .
                  '|Line: ' . debug_backtrace()[ 0 ][ 'line' ] ?? '' . PHP_EOL .
                  '|Date: ' . strftime('%d %B %Y %H:%M:%S') . PHP_EOL .
                  '|Message: ' . $message . PHP_EOL . PHP_EOL . PHP_EOL;
        return Load::class('file')->append($logDir . $destination . $extension, $report);
    }
}


if (!function_exists('env')) {
    /**
     * @param $name
     * @return array|bool|false|mixed|string
     */
    function env($name)
    {
        if (function_exists('getenv') && getenv($name)) {
            return getenv($name);
        }
        if (function_exists('apache_getenv') && apache_getenv($name)) {
            return apache_getenv($name);
        }

        return $_ENV[ $name ] ?? $_SERVER[ $name ] ?? false;
    }
}


if (!function_exists('cookie')) {
    /**
     * @return mixed
     * @throws Exception
     */
    function cookie()
    {
        if (func_num_args() === 0) {
            return Load::class('cookie');
        }

        if (func_num_args() === 1) {
            return Load::class('cookie')->get(func_get_args(0));
        }

        return Load::class('cookie',...func_get_args());
    }
}


if (!function_exists('cache')) {
    /**
     * @return mixed
     * @throws Exception
     */
    function cache()
    {
        if (func_num_args() === 0) {
            return Load::class('cache');
        }

        if (func_num_args() === 1) {
            return Load::class('cache')->get(func_get_arg(0));
        }

        return Load::class('cache')->put(...func_get_args());
    }
}


if (!function_exists('session')) {
    /**
     * @return mixed
     * @throws Exception
     */
    function session()
    {
        if (func_num_args() === 0) {
            return Load::class('session');
        }

        if (func_num_args() === 1) {
            return Load::class('session')->get(func_get_arg(0));
        }

        return Load::class('session')->set(...func_get_args());
    }
}


if (!function_exists('view')) {
    /**
     * @param String $file
     * @param array $data
     * @param bool $cache
     * @return mixed
     * @throws Exception
     */
    function view(String $file, $data = [], $cache = false)
    {
        return Load::class('view')->render($file, $data, $cache);
    }
}


if (!function_exists('redirect')) {
    /**
     * @param bool $link
     * @param int $refresh
     * @param int $http_response_code
     * @return mixed
     * @throws Exception
     */
    function redirect($link = false, $refresh = 0, $http_response_code = 302)
    {
        if ($link) {
            return Load::class('redirect')->to($link, $refresh, $http_response_code);
        }

        return Load::class('redirect');
    }
}



if (!function_exists('lang')) {
    /**
     * @param null $word
     * @param array $replace
     * @return mixed
     * @throws Exception
     */
    function lang($word = null, $replace = [])
    {
        if ($word !== null) {
            return Load::class('language')->translate($word, $replace);
        }

        return Load::class('language');
    }
}


if (!function_exists('validator')) {
    /**
     * @param null $data
     * @param array $rules
     * @return mixed
     * @throws Exception
     */
    function validator($data = null, $rules = [])
    {
        if ($data !== null) {
            return Load::class('validator')->make($data, $rules);
        }

        return Load::class('validator');
    }
}


if (!function_exists('get')) {
    function get($name = false)
    {
        return Load::class('input')->get($name);
    }
}


if (!function_exists('post')) {
    function post($name = false)
    {
        return Load::class('input')->post($name);
    }
}


if (!function_exists('request')) {
    function request()
    {
        if (func_num_args() === 0) {
            return Load::class('request');
        }

        if (func_num_args() === 1) {
            return Load::class('request')->{func_get_arg(0)};
        }

        return Load::class('request')->{func_get_arg(0)} = func_get_arg(1);
    }
}


if (!function_exists('xssClean')) {
    function xssClean($data)
    {
        return Load::class('input')->xssClean($data);
    }
}


if (!function_exists('fullTrim')) {
    function fullTrim($str, $char = ' '): String
    {
        return str_replace($char, '', $str);
    }
}


if (!function_exists('encode_php_tag')) {
    function encode_php_tag($str): String
    {
        return str_replace(array( '<?' , '?>' ), array( '&lt;?' , '?&gt;' ), $str);
    }
}


if (!function_exists('preg_replace_array')) {
    function preg_replace_array($pattern, array $replacements, $subject): String
    {
        /**
         * @return mixed
         */
        $callback = static function () use (&$replacements) {
                return array_shift($replacements);
        };

        return preg_replace_callback($pattern, $callback, $subject);
    }
}


if (!function_exists('str_replace_first')) {
    function str_replace_first($search, $replace, $subject): String
    {
        return Load::class('str')->replace_first($search, $replace, $subject);
    }
}


if (!function_exists('str_replace_last')) {
    function str_replace_last($search, $replace, $subject): String
    {
        return Load::class('str')->replace_last($search, $replace, $subject);
    }
}


if (!function_exists('str_slug')) {
    function str_slug($str, $separator = '-'): String
    {
        return Load::class('str')->slug($str, $separator);
    }
}


if (!function_exists('str_limit')) {
    function str_limit($str, $limit = 100, $end = '...'): String
    {
        return Load::class('str')->limit($str, $limit, $end);
    }
}


if (!function_exists('upper')) {
    function upper(String $str, $encoding = 'UTF-8'): String
    {
        return mb_strtoupper($str, $encoding);
    }
}


if (!function_exists('lower')) {
    function lower(String $str, $encoding = 'UTF-8'): String
    {
        return mb_strtolower($str, $encoding);
    }
}


if (!function_exists('title')) {
    /**
     * @param String $str
     * @param string $encoding
     * @return string
     */
    function title(String $str, $encoding = 'UTF-8'): String
    {
        return mb_convert_case($str, MB_CASE_TITLE, $encoding);
    }
}


if (!function_exists('len')) {
    /**
     * @param array|string $value
     * @param null|string $encoding
     * @return int|bool
     */
    function len($value, $encoding = null)
    {
        if (is_string($value)) {
            return mb_strlen($value, $encoding);
        }

        if (is_array($value)) {
            return count($value);
        }

        return 0;
    }
}


if (!function_exists('str_replace_array')) {
    function str_replace_array($search, array $replace, $subject): String
    {
        return Load::class('str')->replace_array($search, $replace, $subject);
    }
}


if (!function_exists('url')) {
    function url($url = null, $parameters = [])
    {
        if ($url === null) {
            return Load::class('url');
        }

        return Load::class('url')->to(...func_get_args());
    }
}


if (!function_exists('current_url')) {
    function current_url($url = ''): String
    {
        return Load::class('url')->current($url);
    }
}


if (!function_exists('clean_url')) {
    function clean_url($url): String
    {
        if ($url === '') {
            return '';
        }

        $url = str_replace(array('http://', 'https://'), '', strtolower($url));

        if (strpos($url, 'www.') === 0) {
            $url = substr($url, 4);
        }
        $url = explode('/', $url);

        $url = reset($url);

        $url = explode(':', $url);

        $url = reset($url);

        return $url;
    }
}


if (!function_exists('segment')) {
    /**
     * @param Int $number
     * @return mixed
     * @throws Exception
     */
    function segment(Int $number)
    {
        return Load::class('url')->segment($number);
    }
}


if (!function_exists('debug')) {
    /**
     * @param $data
     */
    function debug($data)
    {
        ob_get_clean();
        echo '<pre style="background-color:#fff; color:#222; line-height:1.2em; font-weight:normal; font:12px Monaco, Consolas, monospace; word-wrap: break-word; white-space: pre-wrap; position:relative; z-index:100000">';

        if (is_array($data)) {
            print_r($data);
        } else {
            var_dump($data);
        }
        echo '</pre>';
        die();
    }
}





if (!function_exists('is_mail')) {
    /**
     * @param String $mail
     * @return mixed
     * @throws Exception
     */
    function is_mail(String $mail)
    {
        return Load::class('validator')->is_mail($mail);
    }
}


if (!function_exists('is_url')) {
    function is_url(String $url)
    {
        return Load::class('validator')->is_url($url);
    }
}


if (!function_exists('is_ip')) {
    function is_ip($ip)
    {
        return Load::class('validator')->is_ip($ip);
    }
}


if (!function_exists('css')) {
    function css($file, $modifiedTime = false): String
    {
        return Load::class('html')->css($file, $modifiedTime);
    }
}


if (!function_exists('js')) {
    function js($file, $modifiedTime = false): String
    {
        return Load::class('html')->js($file, $modifiedTime);
    }
}


if (!function_exists('img')) {
    function img($file, $attributes = []): String
    {
        return Load::class('html')->img($file, $attributes);
    }
}
