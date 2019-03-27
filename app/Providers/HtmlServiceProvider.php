<?php

namespace App\Providers;

use App\Helpers\LanguageHelper;
use App\Models\User;
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
        \HTML::bind('listParticipants', function ($users) {
            $result = [];
            /** @var User $user */
            foreach ($users as $user) {
                $href = route('user.view', [$user->id]);
                $name = $user->name;
                $result[] = "<a href=\"${href}\">${name}</a>";
            }
            return implode(', ', $result);
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
