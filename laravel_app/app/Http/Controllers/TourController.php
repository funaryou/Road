<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TourController extends Controller
{
    // 一覧表示
    function select() {
        $tours = \App\Models\Tour::all();
        return view("tour.select", ["tours" => $tours]);
    }

    /**
     * ツアー作成フォームを表示する
     */
    public function tourForm()
    {
        return view('tour.form');
    }

    // ルートが期待するメソッド名に合わせたラッパー
    public function tourSelect()
    {
        return $this->select();
    }

    public function tourStore(Request $request)
    {
        return $this->store($request);
    }

    // 保存処理
    function store(Request $request) {

        // 入力値を取得
        $title = $request["title"];
        $content = $request["content"];

        // ログインユーザー取得
        $user = request()->user();

        // tours に保存
        $user->tours()->create([
            "title" => $title,
            "content" => $content
        ]);

        // 一覧にリダイレクト
        return redirect()->route("tour.select");
    }
}