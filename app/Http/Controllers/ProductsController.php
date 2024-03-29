<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
  public function index(Request $request)
  {
    // 创建一个查询构建器
    $builder = Product::query()->where('on_sale', true);
    // 判断是否有提交 search 参数，如果有就赋值给 $search 变量
    // search 参数用来模糊搜索商品
    if ($search = $request->input('search', '')) {
      $like = '%'.$search.'%';
      // 模糊搜索商品标题、商品详情、SKU 标题、SKU 描述
      $builder->where(function ($query) use ($like) {
        $query->where('title', 'like', $like)
              ->orWhere('description', 'like', $like)
              ->orWhereHas('skus', function ($query) use ($like) {
                $query->where('title', 'like', $like)
                      ->orWhere('description', 'like', $like);
              });
      });
    }

    // 是否有提交 order 参数，如果有就赋值给 $order 变量
    // order 参数用来控制商品的排序规则
    if ($order = $request->input('order', '')) {
      // 是否以 _asc 或 _desc 结尾
      if (preg_match('/^(.+)_(asc|desc)$/', $order, $m)) {
        // 如果字符串的开头是这 3 个字符串之一，说明是一个合法的排序值
        if (in_array($m[1], ['price', 'sold_count', 'rating'])) {
          // 根据传入的排序值来构造排序参数
          $builder->orderBy($m[1], $m[2]);
        }
      }
    }

    $products = $builder->paginate(16);

    return view('products.index', [
      'products' => $products,
      'filters'  => [
        'search' => $search,
        'order'  => $order
      ],
    ]);
  }

  public function show(Product $product, Request $request)
  {
    // 判断商品是否已上架，如果没有上架则招聘异常
    if ( ! $product->on_sale) {
      throw new InvalidRequestException('商品未上架');
    }

    $favored = false;
    // 用户未登录时返回的是 null，已登录时返回的是对应的用户对象
    if ($user = $request->user()) {
      // 从当前用户已收藏的商品中搜索 id 为当前商品 id 的商品
      // boolval() 函数用于把值转为布尔值
      $favored = boolval($user->favoriteProducts()->find($product->id));
    }

    return view('products.show', compact(['product', 'favored']));
  }

  /**
   * 收藏商品
   *
   * @param  Product  $product
   * @param  Request  $request
   * @return array|void
   */
  public function favor(Product $product, Request $request)
  {
    $user = $request->user();
    if ($user->favoriteProducts()->find($product->id)) {
      return;
    }

    $user->favoriteProducts()->attach($product->id);

    return [];
  }

  /**
   * 取消收藏
   *
   * @param  Product  $product
   * @param  Request  $request
   * @return array
   */
  public function disfavor(Product $product, Request $request)
  {
    $user = $request->user();
    $user->favoriteProducts()->detach($product->id);

    return [];
  }

  /**
   * 收藏商品列表
   *
   * @param  Request  $request
   */
  public function favorites(Request $request)
  {
    $products = $request->user()->favoriteProducts()->paginate();

    return view('products.favorites', compact('products'));
  }
}
