<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAddressRequest;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAddressesController extends Controller
{
  /**
   * 收列地址列表
   *
   * @param Request $request
   * @return void
   */
  public function index(Request $request)
  {
    return view('user_addresses.index', [
      'addresses' => $request->user()->addresses,
    ]);
  }

  public function create()
  {
    return view('user_addresses.create_and_edit', ['address' => new UserAddress()]);
  }

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
}
