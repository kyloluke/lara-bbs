<?php

namespace App\Handlers;

use Image;

class ImageUploadHandler
{
    // 只允许一下几种格式
    private $allowed_ext = ['png', 'jpeg', 'jpg', 'gif'];

    function save($file, $folder, $prefix, $max_width = false)
    {
        // 获取文件后缀
        $extension = strtolower($file->getClientOriginalExtension());
        // 判断文件是否合法
        if(!in_array($extension, $this->allowed_ext)) {
            return false;
        }

        // 制作存储文件夹规则
        $folder_name = 'uploads/'.$folder.'/'.date('Y/m', time());
        // 存储文件的物理路径
        $upload_path = public_path(). '/' .$folder_name;

        // 制作文件名称
        $file_name = $prefix. '_'. time(). '_' . str_random(10). '.' . $extension;
        // 移动文件
        $file->move($upload_path, $file_name);

        // 如果传入了  $max_width 说明需要裁剪图片
        if($max_width && $extension != 'gif') {
            $this->reduceSize($upload_path.'/'.$file_name, $max_width);
        }

        return [
            'path' => config('app.url'). '/'.$folder_name.'/'.$file_name
        ];
    }
    
    // 裁剪图片
    public function reduceSize($file_path, $max_width)
    {
        // 实例化 Image 注意：参数是文件的物理路径
        $image = Image::make($file_path);
        // 进行调整 1 宽度， 2 高度， 3 回调
        $image->resize($max_width, null, function($constraint) {
            // 高度为 null 进行等比例缩放
            $constraint->aspectRatio();
            // 防止裁剪时图片尺寸变大
            $constraint->upsize();
        });
        // 保存
        $image->save();
    }
}