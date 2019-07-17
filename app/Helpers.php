<?php
    function route_class()
    {
        // str_replace https://www.php.net/manual/zh/function.str-replace.php
        return str_replace('.', '-', Route::currentRouteName());
    }

    // TopicObserver 使用此函数
    function make_excerpt($body, $length = 200)
    {
        // strip_tags https://php.net/manual/zh/function.strip-tags.php
        // preg_replace https://www.php.net/manual/zh/function.preg-replace.php
        $excerpt = trim(preg_replace('/\r\n\|\r|\n+/', '', strip_tags($body)));
        return Str::limit($excerpt, $length);
    }