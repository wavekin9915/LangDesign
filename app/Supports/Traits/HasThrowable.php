<?php

Trait HasThrowable
{
    /**
     * try-catch公共方法
     * @param callable $func
     * @param $on_transaction
     * @return void
     */
    public static function tryCatch(callable $func, $on_transaction = false)
    {
        //...
    }

    public static function exception($message, $code)
    {
        //...
    }
}