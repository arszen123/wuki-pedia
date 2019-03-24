<?php

namespace App\Providers;

use App\Helpers\LanguageHelper;
use Illuminate\Support\ServiceProvider;

class HtmlServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        \HTML::bind('languageByCode', function ($langId) {
            return LanguageHelper::getLanguageByCode($langId);
        });
        \HTML::bind('listTags', function ($tags, $glue = ', ') {
            if (empty($tags)) {
                return '';
            }
            $result = [];
            foreach ($tags as $tag) {
                $result[] = $tag->tag;
            }
            return implode($glue, $result);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
