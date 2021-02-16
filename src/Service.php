<?php
/**
 * @link https://ymscanner.ru/doc
 */
namespace YMScanner;

use GuzzleHttp\Client;
use YMScanner\Model\Category;
use YMScanner\Model\Info;
use YMScanner\Model\Price;
use YMScanner\Model\Product;
use YMScanner\Model\Review;
use YMScanner\Model\Category\Specification as CategorySpecification;
use YMScanner\Model\Brand;
use YMScanner\Model\Specification;

class Service {

    private $key;

    public function setKey(string $key) : Service
    {
        $this->key = $key;

        return $this;
    }

    private function getKey() : string
    {
        if (!$this->key) {
            throw new \Exception('Set auth key firstly');
        }

        return $this->key ?? '';
    }

    /**
     * @link https://ymscanner.ru/doc/recharge
     *
     * @param string $certificate
     *
     * @return int
     *
     * @throws \Exception
     */
    public function getRecharge(string $certificate) : int
    {
        $response = $this->request('post', 'recharge', ['sertificate' => $certificate]);

        return (int) $response->success;
    }

    /**
     * @link https://ymscanner.ru/doc/balance
     *
     * @return int
     *
     * @throws \Exception
     */
    public function getBalance() : int
    {
        $response = $this->request('post', 'balance');

        return (int) $response->balance;
    }

    /**
     * @link https://ymscanner.ru/doc/photos
     *
     * @param int $id
     * @param string $size !!! Experimental. Yandex images sizes: original|50x50|75x75|100x100|120x120|150x150|200x200|240x240|250x250|500x500|1000x1000
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getPhotos(int $id, string $size = '') : array
    {
        $response = $this->request('post', 'photos', ['id' => $id]);

        $pictures = $response->pictures ?? [];

        if ($size) {
            $picturesSized = [];

            foreach ($pictures as $pictureNumber => $pictureCollection) {
                foreach ($pictureCollection as $pictureItem) {
                    if ($pictureItem->size === $size) {
                        /** @var array $pictureItem [string url, ...] */
                        $picturesSized[$pictureNumber][] = $pictureItem;
                    }
                }
            }

            $pictures = $picturesSized;
        }

        return $pictures;
    }

    /**
     * @link https://ymscanner.ru/doc/info
     *
     * @param int $id
     *
     * @return Info
     *
     * @throws \Exception
     */
    public function getInfo(int $id) : Info
    {
        $response = $this->request('post', 'info', ['id' => $id]);

        return new Info($response);
    }

    /**
     * @link https://ymscanner.ru/doc/specs
     *
     * @param int $id
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getSpecifications(int $id) : array
    {
        $response = $this->request('post', 'specs', ['id' => $id]);
        $specifications = [];

        foreach ($response->specifications as $specification) {
            $specifications[] = new Specification($specification);
        }

        return $specifications;
    }

    /**
     * @link https://ymscanner.ru/doc/price
     *
     * @param int $id
     *
     * @return Price
     *
     * @throws \Exception
     */
    public function getPrice(int $id) : Price
    {
        $response = $this->request('post', 'price', ['id' => $id]);

        return new Price($response);
    }

    /**
     * @link https://ymscanner.ru/doc/bulkprice
     *
     * @param array $ids
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getPrices(array $ids) : array
    {
        $response = $this->request('post', 'bulkprice', ['id' => implode(',', $ids)]);
        $prices = [];

        foreach ($response as $price) {
            $prices[] = (new Price($price))->setId($response->id);
        }

        return $prices;
    }

    /**
     * @param int $id
     * @param int $quantity
     * @param int $minrating
     *
     * @link https://ymscanner.ru/doc/reviews
     *
     * @throws \Exception
     *
     * @return array
     */
    public function getReviews(int $id, int $quantity = 0, int $minrating = 1) : array
    {
        if ($minrating < 1 || $minrating > 5) {
            throw new \Exception('Rating must be from 1 to 5');
        }

        $response = $this->request('post', 'reviews', [
            'id' => $id,
            'quantity' => $quantity,
            'minrating' => $minrating
        ]);

        $reviews = [];

        foreach ($response->reviews as $review) {
            $reviews[] = (new Review($review))->setId($response->id);
        }

        return $reviews;
    }

    /**
     * @link https://ymscanner.ru/doc/searchmodel
     *
     * @param string $search
     *
     * @return Info\Search
     *
     * @throws \Exception
     */
    public function getSearch(string $search)
    {
        $response = $this->request('post', 'searchmodel', ['search' => urlencode($search)]);

        return new Info\Search($response);
    }

    /**
     * @link https://ymscanner.ru/doc/allcategories
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getCategories() : array
    {
        $response = $this->request('post', 'allcategories');
        $categories = [];

        foreach ($response as $category) {
            $categories[] = new Category($category);
        }

        return $categories;
    }

    /**
     * @link https://ymscanner.ru/doc/categoryspecs
     *
     * @param int $id
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getCategorySpecifications(int $id) : array
    {
        $response = $this->request('post', 'categoryspecs', ['id' => $id]);
        $specifications = [];

        foreach ($response as $specification) {
            $specifications[] = new CategorySpecification($specification);
        }

        return $specifications;
    }

    /**
     * @link https://ymscanner.ru/api/categorybrands
     *
     * @param int $id
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getCategoryBrands(int $id) : array
    {
        $response = $this->request('post', 'categorybrands', ['id' => $id]);
        $brands = [];

        foreach ($response as $brand) {
            $brands[] = new Brand($brand);
        }

        return $brands;
    }

    /**
     * @link https://ymscanner.ru/api/brand
     *
     * @param int $brandId
     * @param int $categoryId
     *
     * @return Brand
     *
     * @throws \Exception
     */
    public function getBrand(int $brandId, int $categoryId = 0) : Brand
    {
        $data = [
            'id' => $brandId
        ];

        if ($categoryId > 0) {
            $data['category'] = $categoryId;
        }

        $response = $this->request('post', 'brand', $data);

        return new Brand($response);
    }

    /**
     * @link https://ymscanner.ru/doc/brandcategories
     *
     * @param int $id
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getBrandCategories(int $id) : array
    {
        $response = $this->request('post', 'brandcategories', ['id' => $id]);
        $categories = [];

        foreach ($response as $category) {
            $categories[] = new Category($category);
        }

        return $categories;
    }

    /**
     * @link https://ymscanner.ru/doc/category
     *
     * @param int $categoryId
     * @param int $brandId
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getProducts(int $categoryId, int $brandId) : array
    {
        $response = $this->request('post', 'category', [
            'id' => $categoryId,
            'brand' => $brandId
        ]);

        $products = [];

        foreach ($response as $product) {
            $products[] = new Product($product);
        }

        return $products;
    }

    public function request(string $method, string $action, array $data = [])
    {
        $method = strtoupper($method);

        $data = array_merge($data, ['key' => $this->getKey()]);
        $url = 'https://ymscanner.ru/api/' . $action;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        switch ($method) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);

                if ($data) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                }
                break;

            case 'GET':
                $url .= '?' . http_build_query($data);
                break;

            default:
                throw new \Exception('Wrong request method');
        }

        curl_setopt($ch, CURLOPT_URL,  $url);

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);

        if (200 !== $info['http_code']) {
            throw new \Exception("HTTP {$info['http_code']} Error");
        }

        $object = json_decode($response);

        if (is_null($object)) {
            throw new \Exception('Bad json response');
        }

        if (isset($object->error)) {
            throw new \Exception($object->error);
        }

        return $object;
    }
}