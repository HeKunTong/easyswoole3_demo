<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-17
 * Time: 上午9:45
 */

namespace App\Model\Goods;


use EasySwoole\Spl\SplBean;

class GoodBean extends SplBean
{
    protected $goodsId;
    protected $goodsName;
    protected $addTime;

    protected function initialize(): void
    {
        $this->addTime = time();
    }

    /**
     * @param mixed $goodsId
     */
    public function setGoodsId($goodsId): void
    {
        $this->goodsId = $goodsId;
    }

    /**
     * @return mixed
     */
    public function getGoodsId()
    {
        return $this->goodsId;
    }

    /**
     * @param mixed $goodsName
     */
    public function setGoodsName($goodsName): void
    {
        $this->goodsName = $goodsName;
    }

    /**
     * @return mixed
     */
    public function getGoodsName()
    {
        return $this->goodsName;
    }
}