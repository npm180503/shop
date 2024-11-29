<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\ProductRequest;
use App\Http\Services\Product\ProductAdminService;
use App\Http\Services\Product\ProductService;
use App\Http\Services\UploadService;
use App\Models\Product;
use App\Models\Menu;
use App\Models\Size;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    protected $productService;
    public function __construct(ProductAdminService $productService){
        $this->productService = $productService;
    }

    public function index(ProductService $service)
    {
        return view('admin.product.list', [
            'title' => 'Danh sách sản phẩm',
            'products' => $service->getWithPagition(request()->all(), request("limit", 10), request("page", 1))
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('admin.product.add', [
            'title' => 'Thêm sản phẩm mới',
            'menus' => Menu::all(),
            'sizes' => Size::all()
       ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProductRequest $request)
    {
        try {
            $fileUploaded = app(UploadService::class)->store($request, "thumb");
            if(!empty($fileUploaded["error"]) && !empty($fileUploaded["url"])){
                $request->merge(['thumb' => $fileUploaded["url"]]);
            }
            
            $product = app(ProductService::class)->store($request->all());

            
            if ($request->has('sizes')) {
                foreach ($request->sizes as $sizeId => $sizeData) {
                    if (isset($sizeData['active'])) {
                        $sizePrice = !empty($sizeData['price']) 
                            ? str_replace('.', '', $sizeData['price']) 
                            : $price;
                        
                        $product->sizes()->attach($sizeId, [
                            'quantity' => $sizeData['quantity'] ?? 0,
                            'price' => $sizePrice
                        ]);
                    }
                }
            }
            

            Session::flash('success', 'Thêm sản phẩm thành công');
            return redirect()->back();
    
        } catch (\Exception $err) {
            Session::flash('error', 'Thêm sản phẩm lỗi: ' . $err->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
