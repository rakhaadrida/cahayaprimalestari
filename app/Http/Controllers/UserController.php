<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\SalesOrder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Session;

class UserController extends Controller
{
    public function index()
    {
        $items = User::All();
        $data = [
            'items' => $items
        ];

        $so = SalesOrder::where('id_sales', '')->get();
        foreach($so as $s) {
            $item = SalesOrder::where('id', $s->id)->first();
            $item->{'id_sales'} = $item->customer->id_sales;
            $item->save();
        }

        return view('pages.user.index', $data);
    }

    public function create()
    {
        return view('pages.user.create');
    }

    public function store(Request $request)
    {
        $lastcode = User::withTrashed()->max('id');
        $lastnumber = (int) substr($lastcode, 0, 2);
        $lastnumber++;
        $newcode = sprintf("%02s", $lastnumber);
        
        User::create([
            'id' => $newcode,
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'roles' => $request->roles,
        ]);

        return redirect()->route('user.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $item = User::findOrFail($id);
        $item->delete();

        return redirect()->route('user.index');
    }

    public function trash() {
        $items = User::onlyTrashed()->get();
        $data = [
            'items' => $items
        ];

        return view('pages.user.trash', $data);
    }

    public function restore($id) {
        $item = User::onlyTrashed()->where('id', $id);
        $item->restore();

        return redirect()->back();
    }

    public function restoreAll() {
        $items = User::onlyTrashed();
        $items->restore();

        return redirect()->back();
    }

    public function hapus($id) {
        $item = User::onlyTrashed()->where('id', $id);
        $item->forceDelete();

        return redirect()->back();
    }

    public function hapusAll() {
        $items = User::onlyTrashed();
        $items->forceDelete();

        return redirect()->back();
    }

    public function change() {
        return view('pages.user.password');
    }

    public function process(Request $request) {
        $item = User::where('id', Auth::user()->id)->first();

        if(Hash::check($request->oldPassword, $item->{'password'})) {
            $item->{'password'} = Hash::make($request->newPassword);
            $item->save();
        }

        Session::flash('simpanPassword', 'Password Berhasil Diubah');

        return redirect()->route('user-change');
    }
}
