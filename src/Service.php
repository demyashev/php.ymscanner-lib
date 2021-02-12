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

    private $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://ymscanner.ru/api/']);
    }

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
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getPhotos(int $id) : array
    {
        $response = $this->request('post', 'photos', ['id' => $id]);

        /** @todo via Model */
        return $response->pictures;
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

        if (!in_array($method, ['POST', 'GET'])) {
            throw new \Exception('Wrong request method');
        }

        $data = array_merge($data, ['key' => $this->getKey()]);

        $response = $this->client->request($method, $action, [
            'connect_timeout' => 5,
            'form_params' => $data
        ]);

        if (200 !== $response->getStatusCode()) {
            throw new \Exception("HTTP {$response->getStatusCode()} Error: {$response->getReasonPhrase()}");
        }

        $json = (string) $response->getBody();

        $object = json_decode($json);

        if (is_null($object)) {
            throw new \Exception('Bad json response');
        }

        if (isset($object->error)) {
            throw new \Exception($object->error);
        }

        return $object;
    }
}