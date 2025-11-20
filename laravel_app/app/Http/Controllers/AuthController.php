<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ユーザー登録画面を返す
    public function registerForm()
    {
        //
    }

    // ユーザー登録処理
    public function register(Request $request)
    {
        //
    }

    // ユーザー情報更新画面
    public function updateForm()
    {
        //
    }

    // ユーザー情報更新処理
    public function update(Request $request)
    {
        //
    }

    // ログイン画面
    public function loginForm()
    {
        //
    }

    // ログイン処理
    public function login(Request $request)
    {
        //
    }

    // ログアウト処理
    public function logout(Request $request)
    {
        Auth::logout(); // ユーザーをログアウト

        $request->session()->invalidate(); // セッションを無効化
        $request->session()->regenerateToken(); // CSRFトークンを再生成

        return redirect('/'); // ログアウト後にトップページへリダイレクト
    }
}