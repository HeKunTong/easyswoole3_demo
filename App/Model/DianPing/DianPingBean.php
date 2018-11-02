<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-24
 * Time: 上午9:25
 */

namespace App\Model\DianPing;


use EasySwoole\Spl\SplBean;

class DianPingBean extends SplBean
{
    protected $shopId;
    protected $name;
    protected $branch;
    protected $stars;
    protected $view;
    protected $shopPrice;
    protected $shopTagCate;
    protected $shopTagRegion;
    protected $address;
    protected $recommend;
    protected $taste;
    protected $surrounding;
    protected $service;
    protected $latitude;
    protected $longitude;
    protected $thumb;

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $branch
     */
    public function setBranch($branch): void
    {
        $this->branch = $branch;
    }

    /**
     * @return mixed
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address): void
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $recommend
     */
    public function setRecommend($recommend): void
    {
        $this->recommend = $recommend;
    }

    /**
     * @return mixed
     */
    public function getRecommend()
    {
        return $this->recommend;
    }

    /**
     * @param mixed $shopId
     */
    public function setShopId($shopId): void
    {
        $this->shopId = $shopId;
    }

    /**
     * @return mixed
     */
    public function getShopId()
    {
        return $this->shopId;
    }

    /**
     * @param mixed $service
     */
    public function setService($service): void
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param mixed $shopPrice
     */
    public function setShopPrice($shopPrice): void
    {
        $this->shopPrice = $shopPrice;
    }

    /**
     * @return mixed
     */
    public function getShopPrice()
    {
        return $this->shopPrice;
    }

    /**
     * @param mixed $shopTagCate
     */
    public function setShopTagCate($shopTagCate): void
    {
        $this->shopTagCate = $shopTagCate;
    }

    /**
     * @return mixed
     */
    public function getShopTagCate()
    {
        return $this->shopTagCate;
    }

    /**
     * @param mixed $shopTagRegion
     */
    public function setShopTagRegion($shopTagRegion): void
    {
        $this->shopTagRegion = $shopTagRegion;
    }

    /**
     * @return mixed
     */
    public function getShopTagRegion()
    {
        return $this->shopTagRegion;
    }

    /**
     * @param mixed $stars
     */
    public function setStars($stars): void
    {
        $this->stars = $stars;
    }

    /**
     * @return mixed
     */
    public function getStars()
    {
        return $this->stars;
    }

    /**
     * @param mixed $surrounding
     */
    public function setSurrounding($surrounding): void
    {
        $this->surrounding = $surrounding;
    }

    /**
     * @return mixed
     */
    public function getSurrounding()
    {
        return $this->surrounding;
    }

    /**
     * @param mixed $taste
     */
    public function setTaste($taste): void
    {
        $this->taste = $taste;
    }

    /**
     * @return mixed
     */
    public function getTaste()
    {
        return $this->taste;
    }

    /**
     * @param mixed $view
     */
    public function setView($view): void
    {
        $this->view = $view;
    }

    /**
     * @return mixed
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @param mixed $thumb
     */
    public function setThumb($thumb): void
    {
        $this->thumb = $thumb;
    }

    /**
     * @return mixed
     */
    public function getThumb()
    {
        return $this->thumb;
    }
}