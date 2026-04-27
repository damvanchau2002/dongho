<?php

class PageController extends Controller
{
    public function about()
    {
        $store = new StoreModel();
        $spnn = $store->randomProducts(4);
        $this->renderLegacy('gioithieu', [
            'spnn' => $spnn,
        ]);
    }

    public function contact()
    {
        $store = new StoreModel();
        $spnn = $store->randomProducts(8);
        $this->renderLegacy('lienhe', [
            'spnn' => $spnn,
        ]);
    }
}
