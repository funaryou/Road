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
        if ($user) {
            // ユーザーのリレーションが正しく設定されている場合はそれを使う
            try {
                $user->tours()->create([
                    // migrations のカラム名に合わせて name を使う
                    "name" => $title,
                    "content" => $content
                ]);
            } catch (\Throwable $e) {
                // リレーションに問題がある場合は直接作成
                \App\Models\Tour::create([
                    "company_id" => $user->id,
                    "name" => $title,
                    "content" => $content
                ]);
            }
        } else {
            // 未認証なら company_id をリクエストから受け取る
            $companyId = $request->input('company_id');
            if (!$companyId) {
                return redirect()->back()->with('error', 'company_id が未指定です。ログインしている場合は再度お試しください。');
            }
            \App\Models\Tour::create([
                "company_id" => $companyId,
                "name" => $title,
                "content" => $content
            ]);
        }

        // 一覧にリダイレクト
        return redirect()->route("tour.select");
    }
}