<?php

use App\Models\Lang;

function getLangs()
{
    return Lang::get();
}


function getFieldVal($val) {

    $locale = app()->getLocale() ?? 'ar';
   return is_array($val) ? ($val[$locale] ?? $val['en'] ?? $val['ur'] ?? $val) : $val;  

    
}