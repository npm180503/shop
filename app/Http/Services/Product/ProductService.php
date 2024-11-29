<?php


namespace App\Http\Services\Product;


use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    const LIMIT = 16;

    public function get(int $page = 1)
    {
        return Product::select('id', 'name', 'price', 'price_sale', 'thumb')
            ->orderByDesc('id')
            ->when($page != null, function ($query) use ($page) {
                $query->offset($page * self::LIMIT);
            })
            ->limit(self::LIMIT)
            ->get();
    }

    public function getWithPagition(array $conditions, int $limit = 10, int $page = 1): LengthAwarePaginator
    {   
        $query = Product::query();
        if(!empty($conditions["name"])){
            $query->where("name", "LIKE", $conditions["name"]."%");
        }
        return $query->paginate($limit, page:$page);
    }

    public function show($id)
    {
        return Product::where('id', $id)
            ->where('active', 1)
            ->with('menu')
            ->firstOrFail();
    }

    public function store(array $data): Product
    {
        return Product::create([
            'name' => $data["name"] ?? "",
            'description' => $data["description"] ?? "",
            'content' => $data["content"] ?? "",
            'menu_id' => $data["menu_id"] ?? "",
            'price' => $data["price"] ?? "",
            'price_sale' => $data["price_sale"],
            'thumb' => $data["thumbPath"] ?? "",
            'active' => $data["active"] ?? ""
        ]);
    }

    public function more($id)
    {
        return Product::select('id', 'name', 'price', 'price_sale', 'thumb')
            ->where('active', 1)
            ->where('id', '!=', $id)
            ->orderByDesc('id')
            ->limit(8)
            ->get();
    }
}