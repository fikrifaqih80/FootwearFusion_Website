<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Adverisement;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\HomePageSetting;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Vendor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function getHomePageData()
    {
        $sliders = Slider::where('status', 1)->orderBy('serial', 'asc')->get();

        $flashSaleDate = FlashSale::first();

        $flashSaleItems = FlashSaleItem::where('show_at_home', 1)
            ->where('status', 1)
            ->pluck('product_id')
            ->toArray();

        $popularCategory = HomePageSetting::where('key', 'popular_category_section')->first();
        $brands = Brand::where('status', 1)->where('is_featured', 1)->get();

        $typeBaseProducts = $this->getTypeBaseProduct();

        // banners
        $homepage_secion_banner_one = Adverisement::where('key', 'homepage_secion_banner_one')->first();
        $homepage_secion_banner_two = Adverisement::where('key', 'homepage_secion_banner_two')->first();
        $homepage_secion_banner_three = Adverisement::where('key', 'homepage_secion_banner_three')->first();
        $homepage_secion_banner_four = Adverisement::where('key', 'homepage_secion_banner_four')->first();

        $recentBlogs = Blog::with(['category', 'user'])
            ->where('status', 1)
            ->orderBy('id', 'DESC')
            ->take(8)
            ->get();

        return response()->json([
            'sliders' => $sliders,
            'flashSaleDate' => $flashSaleDate,
            'flashSaleItems' => $flashSaleItems,
            'popularCategory' => $popularCategory,
            'brands' => $brands,
            'typeBaseProducts' => $typeBaseProducts,
            'homepage_secion_banner_one' => $homepage_secion_banner_one,
            'homepage_secion_banner_two' => $homepage_secion_banner_two,
            'homepage_secion_banner_three' => $homepage_secion_banner_three,
            'homepage_secion_banner_four' => $homepage_secion_banner_four,
            'recentBlogs' => $recentBlogs,
        ]);
    }

    public function getTypeBaseProduct()
    {
        $typeBaseProducts = [];

        $typeBaseProducts['new_arrival'] = Product::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->with(['variants', 'category', 'productImageGalleries'])
            ->where(['product_type' => 'new_arrival', 'is_approved' => 1, 'status' => 1])
            ->orderBy('id', 'DESC')
            ->take(8)
            ->get();

        $typeBaseProducts['featured_product'] = Product::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->with(['variants', 'category', 'productImageGalleries'])
            ->where(['product_type' => 'featured_product', 'is_approved' => 1, 'status' => 1])
            ->orderBy('id', 'DESC')
            ->take(8)
            ->get();

        $typeBaseProducts['top_product'] = Product::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->with(['variants', 'category', 'productImageGalleries'])
            ->where(['product_type' => 'top_product', 'is_approved' => 1, 'status' => 1])
            ->orderBy('id', 'DESC')
            ->take(8)
            ->get();

        $typeBaseProducts['best_product'] = Product::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->with(['variants', 'category', 'productImageGalleries'])
            ->where(['product_type' => 'best_product', 'is_approved' => 1, 'status' => 1])
            ->orderBy('id', 'DESC')
            ->take(8)
            ->get();

        return $typeBaseProducts;
    }

    public function getVendorPageData()
    {
        $vendors = Vendor::where('status', 1)->paginate(20);
        return response()->json(['vendors' => $vendors]);
    }

    public function getVendorProductsPageData(string $id)
    {
        $products = Product::where(['status' => 1, 'is_approved' => 1, 'vendor_id' => $id])
            ->orderBy('id', 'DESC')
            ->paginate(12);

        $categories = Category::where(['status' => 1])->get();
        $brands = Brand::where(['status' => 1])->get();
        $vendor = Vendor::findOrFail($id);

        return response()->json([
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'vendor' => $vendor,
        ]);
    }

    public function showProductModal(string $id)
    {
        $product = Product::findOrFail($id);
        return response()->json(['product' => $product]);
    }
}
