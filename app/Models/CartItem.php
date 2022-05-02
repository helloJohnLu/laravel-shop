<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
  use HasFactory;

  protected $fillable   = ['amount'];
  public    $timestamps = false;

  /**
   * 购物车商品 - 用户 关联
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }

  /**
   * 购物车商品 SKU 关联
   */
  public function productSku()
  {
    return $this->belongsTo(ProductSku::class);
  }
}
