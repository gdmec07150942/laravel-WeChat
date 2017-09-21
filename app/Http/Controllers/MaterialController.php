<?php

namespace App\Http\Controllers;

use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    //
    public $material;

    /**
     * MaterialController constructor.
     * @param $material
     */
    public function __construct(Application $material)
    {
        $this->material = $material->material;
    }

    public function materials()
    {
        // upload
        try {
            $photo = $this->material->uploadImage(public_path().'/images/miss.jpg');
            return $photo;
        } catch (\Exception $e) {
            return '出现错误：'.$e->getMessage();
        }
    }

    public function material($openId)
    {
        try{
            $photo = $this->material->get($openId);
            return $photo;
        } catch (\Exception $e) {
            return '出现错误：'.$e->getMessage();
        }

    }
}
