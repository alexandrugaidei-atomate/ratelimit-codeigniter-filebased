<?php

/*
Copyright (c) 2014 Alexandru Gaidei
http://alexandru.master.pro.md/

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*/

if(!function_exists('limitRequests')) {

    //limit for IP to 100 requests per 5 min
    function limitRequests($ip, $max_requests = 100, $sec = 300){
        $CI =& get_instance();

        $CI->load->driver('cache', array('adapter'=>'file'));

        $cache_key = $ip . "_key";
        $cache_remain_time = $ip . "_tmp";

        $current_time = date("Y-m-d H:i:s");

        //if first request
        if ($CI->cache->get($cache_key) === false){

            $current_time_plus = date("Y-m-d H:i:s", strtotime("+".$sec." seconds"));

            $CI->cache->save($cache_key, 1, $sec);
            $CI->cache->save($cache_remain_time, $current_time_plus, $sec * 2);
        }
        else{

            $requests = $CI->cache->get($cache_key);

            $time_lost = $CI->cache->get($cache_remain_time);

            if($current_time > $time_lost){

                //as first time request
                $current_time_plus = date("Y-m-d H:i:s", strtotime("+".$sec." seconds"));

                $CI->cache->save($cache_key, 1, $sec);
                $CI->cache->save($cache_remain_time, $current_time_plus, $sec * 2);

            }
            else{
                $CI->cache->save($cache_key, $requests + 1, $sec);
            }

            $requests = $CI->cache->get($cache_key);
            if($requests > $max_requests){
                header("HTTP/1.0 429 Too Many Requests");
                exit;
            }

        }

    }

}