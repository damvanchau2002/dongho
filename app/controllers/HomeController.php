<?php

class HomeController extends Controller
{
    public function index()
    {
        $store = new StoreModel();
        $data = $store->categories();
        $data1 = $store->products();
        $spnoibat = $store->featuredProducts();
        $spmoinhat = $store->newestProducts();
        $flash_sale_products = $store->flashSaleProducts(); // Lấy sp flash sale
        $data2 = null;

        if (isset($_GET['idloai'])) {
            $data2 = $store->productsByCategory($_GET['idloai']);
        }

        require_once BASE_PATH . '/models/BannerModel.php';
        $bannerModel = new BannerModel();
        $activeBanners = $bannerModel->getActiveBanners();
        
        $banners = [
            'main' => [],
            'side1' => [],
            'side2' => []
        ];
        
        if (is_array($activeBanners)) {
            foreach ($activeBanners as $b) {
                if (isset($banners[$b['vi_tri']])) {
                    $banners[$b['vi_tri']][] = $b;
                }
            }
        }

        $this->renderLegacy('home', [
            'data' => $data,
            'data1' => $data1,
            'data2' => $data2,
            'spnoibat' => $spnoibat,
            'spmoinhat' => $spmoinhat,
            'flash_sale_products' => $flash_sale_products,
            'banners' => $banners
        ]);
    }
}
