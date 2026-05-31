<?php

namespace Models;
class Demo
{
    const CACHE_KEY = "DemoCache_%s";

    const TYPES = [
        'manager' => 1,
        'member' => 2,
        'blogger' => 3,
    ];

    const TYPE_LABELS = [
        1 => '管理员',
        2 => '普通用户',
        3 => '博主',
    ];

    const CONTACT_TYPES = [ //不用担心用大写单词长写错单词的，写错单词的话PhpStorm会报绿波浪线的。
        'phone' => 1,
        'email' => 2,
        'telegram' => 3,
    ];
    const CONTACT_TYPE_LABELS = [ //不用担心常量太长麻烦的，你打Demo::LA 所有 XXX_LABELS 的常量都出来了。
        1 => '手机',
        2 => '邮箱',
        3 => 'Telegram',
    ];

}

echo Demo::TYPES['manager'] . "\n";
