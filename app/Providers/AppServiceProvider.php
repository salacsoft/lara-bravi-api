<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::macro('whereLike', function ($attributes, string $searchTerm) {
            $this->where(function (Builder $query) use ($attributes, $searchTerm) {
                foreach (Arr::wrap($attributes) as $attribute) {
                    $query->when(
                        str_contains($attribute, '.'),
                        function (Builder $query) use ($attribute, $searchTerm) {
                            [$relationName, $relationAttribute] = explode('.', $attribute);
                            $query->orWhereHas($relationName, function (Builder $query) use ($relationAttribute, $searchTerm) {
                                $criteria = explode(" ", $searchTerm);
                                foreach ($criteria as $lookFor)  {
                                    $query->orWhere($relationAttribute, 'LIKE', "%{$lookFor}%");
                                }

                            });
                        },
                        function (Builder $query) use ($attribute, $searchTerm) {
                            $criteria = explode(" ", $searchTerm);
                                foreach ($criteria as $lookFor)  {
                                    $query->orWhere($attribute, 'LIKE', "%{$lookFor}%");
                                }
                        }
                    );
                }
            });

            return $this;
        });
    }
}
