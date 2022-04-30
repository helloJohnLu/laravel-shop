<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model
{
  use HasFactory;

  protected $fillable = ['title', 'description', 'price', 'stock'];

  // 与商品关联
  public function product()
  {
    return $this->belongsTo(Product::class);
  }
}
