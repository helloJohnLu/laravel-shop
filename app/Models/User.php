<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
  use HasApiTokens, HasFactory, Notifiable;
  use DefaultDatetimeFormat;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'email',
    'password',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];


  /**
   * 用户 - 地址 一对多
   *
   * @return void
   */
  public function addresses()
  {
    return $this->hasMany(UserAddress::class);
  }

  /**
   * 用户 - 收藏商品 关联
   */
  public function favoriteProducts()
  {
    return $this->belongsToMany(Product::class, 'user_favorite_products')
                ->withTimestamps()  //  代表中间表带有时间戳字段
                ->orderBy('user_favorite_products.created_at', 'desc');
  }

  /**
   * 购物车商品 关联
   */
  public function cartItems()
  {
    return $this->hasMany(CartItem::class);
  }
}
