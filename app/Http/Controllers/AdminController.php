<?php

namespace App\Http\Controllers;

use App\Charts\BahanChart;
use App\Charts\JumlahOrderChart;
use App\Charts\LineBahan;
use App\Charts\LineProduksi;
use App\Charts\OrderChart;
use App\Charts\ProductChart;
use App\Charts\ProduksiBahanChart;
use App\Charts\ProduksiProdukChart;
use App\Models\Catalog;
use App\Models\Category;
use App\Models\Information;
use App\Models\Paymen;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;

class AdminController extends Controller {
    public function index(BahanChart $bahanChart, ProductChart $productChart,
        ProduksiProdukChart $produksiProdukChart, ProduksiBahanChart $produksiBahanChart, 
        OrderChart $orderChart, JumlahOrderChart $jumlahOrderChart,
        LineProduksi $lineProduksi, LineBahan $lineBahan
        ) {
        $count_catalog = Catalog::count();
        $count_category = Category::count();
        $count_video = Video::count();
        $count_information = Information::count();

        $latest_products = Catalog::orderBy('created_at', 'desc')->take(5)->get();
        $latest_video = Video::orderBy('created_at', 'desc')->take(1)->first();
        $latest_informations = Information::orderBy('created_at', 'desc')->take(3)->get();

        return view('admin.dashboard', [
            'title' => 'Dashboard',
            'subtitle' => '',
            'active' => 'dashboard',
            'count_catalog' => $count_catalog,
            'count_category' => $count_category,
            'count_video' => $count_video,
            'count_information' => $count_information,
            'latest_products' => $latest_products,
            'latest_video' => $latest_video,
            'latest_informations' => $latest_informations,
            'bahanChart' => $bahanChart->build(),
            'productChart' => $productChart->build(),
            'ProduksiProduct' => $produksiProdukChart->build(),
            'produksiBahan' => $produksiBahanChart->build(),
            'orderChart' => $orderChart->build(),
            'jumlahOrder' => $jumlahOrderChart->build(),
            'lineProduksi' => $lineProduksi->build(),
            'lineBahan' => $lineBahan->build()
        ]);
    }

    public function accountSetting() {
        return view('admin.settings.account-setting.index', [
            'title' => 'Account Setting',
            'subtitle' => '',
            'active' => 'account-setting',
            'payment' => Paymen::first(),
        ]);
    }

    public function payment(Request $request) {
        $this->validate($request, [
            'bank' => 'required',
            'rekening' => 'required',
            'pemilik' => 'required'
        ], [

        ]);

        $payment = Paymen::first();
        $payment->update([
            'bank' => $request->bank,
            'nomor_rekening' => $request->rekening,
            'pemilik_rekening' => $request->pemilik,
        ]);

        return redirect()->back()->with('success', 'Payement Information been changed');
    }

    public function changePassword(Request $request, $id) {
        $this->validate($request, [
            'current_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_new_password' => 'required|same:new_password',
        ], [
            'current_password.required' => 'Current Password is required',
            'new_password.required' => 'New Password is required',
            'new_password.min' => 'New Password must be at least 8 characters',
            'confirm_new_password.required' => 'Confirm New Password is required',
            'confirm_new_password.same' => 'Confirm New Password must be same with New Password',
        ]);

        $user = User::findOrFail($id);

        if(password_verify($request->current_password, $user->password)) {
            $user->update([
                'password' => bcrypt($request->new_password),
            ]);

            return redirect()->back()->with('success', 'Password has been changed');
        } else {
            return redirect()->back()->with('error', 'Current Password is wrong');
        }
    }

    public function changeInformation(Request $request, $id) {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
        ], [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->back()->with('success', 'Information has been changed');
    }
}
