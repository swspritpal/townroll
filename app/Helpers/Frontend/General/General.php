<?php

Use App\Models\Access\User\User;
Use App\Models\Country\Country;
Use App\Models\Country\City;

/**
 * App helpers
 */

if (!function_exists('isAdmin')) {
    function isAdmin($user)
    {
        return $user != null && $user instanceof User && $user->id === 1;
    }
}

if (!function_exists('isAdminById')) {
    function isAdminById($user_id)
    {
        return $user_id === 1;
    }
}

if (!function_exists('getAdminUser')) {
    function getAdminUser()
    {
        return User::findOrFail(1);
    }
}

if (!function_exists('getMilliseconds')) {
    function getMilliseconds()
    {
        return round(microtime(true) * 1000);
    }
}

if (!function_exists('array_safe_get')) {
    function array_safe_get($array, $key, $default = '')
    {
        if (array_has($array, $key)) {
            return $array[$key];
        }
        return $default;
    }
}

if (!function_exists('getUrlEndWithSlash')) {
    function getUrlEndWithSlash($url)
    {
        if (!ends_with($url, '/')) {
            return $url . '/';
        }
        return $url;
    }
}

if (!function_exists('getUrlByFileName')) {
    function getUrlByFileName($fileName)
    {
        /**
         * https domain first
         */
        $qiniu_domain = config('filesystems.disks.qiniu.domains.https');
        if ($qiniu_domain) {
            $qiniu_domain = getUrlEndWithSlash($qiniu_domain);
        } else {
            $qiniu_domain = getUrlEndWithSlash($qiniu_domain = config('filesystems.disks.qiniu.domains.default'));
        }
        return $qiniu_domain . $fileName;
    }
}

if (!function_exists('processImageViewUrl')) {

    function processImageViewUrl($rawImageUrl, $width = null, $height = null, $mode = 1)
    {
        $para = '?imageView2/' . $mode;
        if ($width)
            $para = $para . '/w/' . $width;
        if ($height)
            $para = $para . '/h/' . $height;
        return $rawImageUrl . $para;
    }
}

if (!function_exists('getImageViewUrl')) {
    /**
     * @see http://developer.qiniu.com/code/v6/api/kodo-api/image/imageview2.html
     * @param $key
     * @param null $width
     * @param null $height
     * @param int $mode
     * @return string
     */
    function getImageViewUrl($key, $width = null, $height = null, $mode = 1)
    {
        return processImageViewUrl(getUrlByFileName($key), $width, $height, $mode);
    }
}


if (!function_exists('formatBytes')) {
    function formatBytes($size, $precision = 2)
    {
        if ($size > 0) {
            $size = (int)$size;
            $base = log($size) / log(1024);
            $suffixes = [' bytes', ' KB', ' MB', ' GB', ' TB'];

            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        } else {
            return $size;
        }
    }
}

if (!function_exists('getMentionedUsers')) {
    function getMentionedUsers($content)
    {
        preg_match_all("/(\S*)\@([^\r\n\s]*)/i", $content, $atlist_tmp);
        $usernames = [];
        foreach ($atlist_tmp[2] as $k => $v) {
            if ($atlist_tmp[1][$k] || strlen($v) > 25) {
                continue;
            }
            $usernames[] = $v;
        }
        $users = User:: whereIn('name', array_unique($usernames))->get();
        return $users;
    }
}


if (!function_exists('httpUrl')) {
    function httpUrl($url)
    {
        if ($url == null || $url == '')
            return '';
        if (!starts_with($url, 'http'))
            return 'http://' . $url;
        return $url;
    }
}


if (!function_exists('getCountiesList')) {
    function getCountiesList()
    {
        $countries = Country::orderBy('name')->pluck('name', 'sortname')->toArray();
        if(!empty($countries)){
            return $countries;
        }else{
            return "NA";
        }
    }
}

if (!function_exists('getCountryId')) {
    function getCountryId($sortnameOfCountry)
    {
        $country = Country::where('sortname','=',$sortnameOfCountry)->select('id')->firstOrFail();
        if(!empty($country)){
            return $country->id;
        }else{
            return "NA";
        }
    }
}


if (!function_exists('is_username_unique')) {
    function is_username_unique($username)
    {
    	$username=clean_username($username);
        $model = User::where('profile_uri', '=',$username)->get(['username', 'id']);
        if(empty($model)){
            return false;
        }else{
            return true;
        }
    }
}


if (!function_exists('clean_username')) {
    function clean_username($string)
    {
        $string=strtolower($string);
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        return (string) preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }
}


if (!function_exists('get_city_name')) {
    function get_city_name($city_id)
    {
    	if(!empty($city_id)){
    		$model = City::whereId($city_id)->first(['name']);
	        if(!empty($model)){
	            return $model->name;
	        }else{
	            return 'NA';
	        }
    	}
    }
}


if (!function_exists('get_latitude')) {
    function get_latitude()
    {
    	if(!empty($_COOKIE['latitude'])){
    		return $_COOKIE['latitude'];
    	}else{
    		return '';
    	}
    }
}


if (!function_exists('get_longitude')) {
    function get_longitude()
    {
    	if(!empty($_COOKIE['longitude'])){
    		return $_COOKIE['longitude'];
    	}else{
    		return '';
    	}
    }
}

if (!function_exists('is_user_already_joined_place')) {
    function is_user_already_joined_place($place_id = null)
    {
        return (bool) \App\Category::where('geo_place_id', $place_id)->whereHas(
                'users',function($user_q){
                    $user_q->where('users.id',\Auth::user()->id);
                })
                ->first();
    }
}

if (!function_exists('get_place_users_count')) {
    function get_place_users_count($place_id = null)
    {
        $category=\App\Category::where('geo_place_id', $place_id)->first();

        if(!empty($category)){
            return $category->users->count();
        }else{
            return false;
        }
    }
}

