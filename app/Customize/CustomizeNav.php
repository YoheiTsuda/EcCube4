<?php

namespace Customize;

use Eccube\Common\EccubeNav;

class CustomizeNav implements EccubeNav
{
    /**
     * @return array
     */
    public static function getNav()
    {
        return [
            // 商品管理に子メニューを追加する場合のサンプル
            // 'product' => [
            //     'children' => [
            //         'sampleplugin_my_product_menu_item' => [
            //             'name' => '商品管理の子（追加）',
            //             'url' => 'admin_homepage',
            //         ],
            //     ],
            // ],
            // 第一階層からオリジナルのメニューを追加する場合のサンプル
            // 'sampleplugin_my_root_menu' => [
            'pw_root_menu' => [
                'name' => 'PWメニュー',
                'icon' => 'fa-cube',
                'children' => [
                    // 'sampleplugin_my_menu_item' => [
                    'pw_menu_item' => [
                        'name' => 'admin.content.sqloutput_management',
                        'url' => 'admin_content_sqloutput',
//                        'url' => 'admin_homepage',
                    ],
                    // 'sampleplugin_my_menu' => [
                    //     'name' => '2階層メニュー（子あり）',
                    //     'children' => [
                    //         'sampleplugin_my_menu_item1' => [
                    //             'name' => '3階層メニュー1',
                    //             'url' => 'admin_homepage',
                    //         ],
                    //         'sampleplugin_my_menu_item2' => [
                    //             'name' => '3階層メニュー2',
                    //             'url' => 'admin_homepage',
                    //         ],
                    //     ],
                    // ],
                ],
            ],
        ];
    }
}
