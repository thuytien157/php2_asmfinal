<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    use HasFactory;

    protected $table = 'categories'; 
    protected $fillable = ['name', 'id_parent'];
    
    public function parent()
    {
        return $this->belongsTo(CategoryModel::class, 'id_parent'); // Sửa lại
    }

    public function children()
    {
        return $this->hasMany(CategoryModel::class, 'id_parent'); // Sửa lại
    }
}


?>
