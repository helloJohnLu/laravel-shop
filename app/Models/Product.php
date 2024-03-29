<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
  use HasFactory;

  protected $fillable = [
    'title', 'description', 'image', 'on_sale', 'rating', 'sold_count', 'review_count', 'price'
  ];

  // on_sale 是一个布尔类型的字段
  protected $casts = ['on_sale' => 'boolean'];

  // 与商品 SKU 关联
  public function skus()
  {
    return $this->hasMany(ProductSku::class);
  }

  public function getImageUrlAttribute()
  {
    // 如果 image 字段本身就已经是完整的 URL 就直接返回
    if (Str::startsWith($this->attributes['image'], ['http://', 'https://'])) {
      return $this->attributes['image'];
    }

    return Storage::disk('public')->url($this->attributes['image']);
  }
}
