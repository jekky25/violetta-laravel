<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

trait TStr {

    /**
     * replace string by pattern
     * @param  string  $str
     * @param  string  $pattern
     * @param  string  $preplacement
     * 
     * @return string
     */
	public function replaceStringByPattern($str, $pattern, $replacement) {
        return preg_replace($pattern, $replacement, $str);
    }
}