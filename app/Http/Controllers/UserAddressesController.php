<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAddressRequest;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAddressesController extends Controller
{
  // 收列地址列表
  public function index(Request $request)
  {
    return view('user_addresses.index', [
      'addresses' => $request->user()->addresses,
    ]);
  }

  /**
   * 新增收货地址
   */
  public function create()
  {
    return view('user_addresses.create_and_edit', ['address' => new UserAddress()]);
  }

  /**
   * 新增收货地址
   *
   * @param  UserAddressRequest  $request
   */
  public function store(UserAddressRequest $request)
  {
    // 关联写入
    $request->user()->addresses()->create($request->only([
      'province',
      'city',
      'district',
      'address',
      'zip',
      'contact_name',
      'contact_phone',
    ]));

    return redirect()->route('user_addresses.index');
  }

  // 编辑表单
  public function edit(UserAddress $user_address)
  {
    return view('user_addresses.create_and_edit', ['address' => $user_address]);
  }

  // 修改收货地址
  public function update(UserAddress $user_address, UserAddressRequest $request)
  {
    $user_address->update($request->only([
      'province',
      'city',
      'district',
      'address',
      'zip',
      'contact_name',
      'contact_phone',
    ]));

    return redirect()->route('user_addresses.index');
  }

  public function destroy(UserAddress $user_address)
  {
    $user_address->delete();

    return [];
  }
}
