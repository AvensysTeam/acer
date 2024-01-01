<?php

namespace App\Http\Middleware;

use Closure;
use App;
use App\Language;
use Illuminate\Http\Request;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        session()->put('lang', $this->getCode());
        app()->setLocale(session('lang', $this->getCode()));
        App::setLocale($this->getCode());
        return $next($request);
    }

    public function getCode()
    {
    //     if (session()->has('lang')) {
    //         return session('lang');
    //     }
    //     $language = Language::where('is_default', 1)->first();
    //     return $language ? $language->code : 'en';
    // }   
//         $ip = Request()->ip();
//         print_r($ip);  
//         $data = \Location::get($ip);  
//         print_r($data);  
//         $countrycode = $data->countryCode; 
//         $languages = ['FR','NL','S','N','PL','EN'];
//         if(in_array($countrycode, $languages)){
//             $lang  = $countrycode;
//         }else{
//             $lang = 'EN';
//         }

     if (session()->has('lang')) {
         return session('lang');
     }

    $acceptLanguage = Request()->header('Accept-Language');  
    $languages = explode(',', $acceptLanguage);

    if (!empty($languages[0])) {
       $preferredLanguage = strtok($languages[0], ';');
    }
 
     $languages = ['fr','nl','s','n','pl','en','de'];
         if(in_array($preferredLanguage, $languages)){
             $lang = $preferredLanguage; 
         }else{
             $lang = 'EN';
         }
     // echo $lang; die();
     $language = Language::where('code',$lang)->first();
     return $language ? $language->code : $lang;
}
}
