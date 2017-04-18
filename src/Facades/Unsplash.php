<?php namespace Cbyte\Unsplash\Facades;
use Illuminate\Support\Facades\Facade;
class Unsplash extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'unsplash';
    }
}