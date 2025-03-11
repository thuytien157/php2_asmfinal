<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class ProductBaseModel extends Model
{
    use HasFactory;
    
    protected $table = 'product'; // Bảng trong CSDL
    protected $fillable = ['name', 'description', 'price', 'discount', 'status', 'id_category', 'id_colors'];

    public function getALLProductsQuery()
    {
        return DB::table('product as p')
            ->join('image_detail as i', 'p.id', '=', 'i.id_product')
            ->where('is_main', 1)
            ->select('p.id as id_product', 'p.name', 'p.description', 'p.price', 'p.discount', 'p.status', 'i.image')
            ->get();
    }

    public function getProductByDate()
    {
        return DB::table('product as p')
            ->join('image_detail as i', 'p.id', '=', 'i.id_product')
            ->where('is_main', 1)
            ->orderBy('p.create_at', 'ASC')
            ->limit(4)
            ->select('p.id as id_product', 'p.name', 'p.description', 'p.price', 'p.discount', 'p.status', 'i.image')
            ->get();

    }
}
