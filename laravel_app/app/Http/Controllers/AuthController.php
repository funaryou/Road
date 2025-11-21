<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SignUpRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;


class AuthController extends Controller
{
    // ユーザー登録画面を返す
    public function registerForm()
    {
        return view('app.auth.register');
    }

    // ユーザー登録処理
    public function register(SignUpRequest $request)
    {
        $password = $request->input("password");
        $password = Hash::make($password);
        $iconPath = $request->file("icon")->store("icons", "public");
        $user = User::create([
            "name" => $request->input("name"),
            "phone_number" => $request->input("phone_number"),
            "icon" => $iconPath,
            "email" => $request->input("email"),
            "password" => $password,
        ]);
        auth()->login($user);
        return redirect()->route('index');
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
        return view('app.auth.login');
    }

    // ログイン処理
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if (auth()->attempt($credentials)) {
            return redirect()->route('index');
        }
        
        return redirect()->route('login')->with('error', 'メールアドレスまたはパスワードが正しくありません。');
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