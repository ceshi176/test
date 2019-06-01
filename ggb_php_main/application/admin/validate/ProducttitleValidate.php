<?php
// +----------------------------------------------------------------------
// | snake
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 http://baiyf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: NickBai <1902822973@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\validate;

use think\Validate;

class ProducttitleValidate extends Validate
{
    protected $rule = [
        ['title', 'require', '标题的名字不能为空'],
        ['normal_id', 'require', '所属页面不能为空'],
        ['thumbnail', 'require', '缩略图不能空']
    ];

}