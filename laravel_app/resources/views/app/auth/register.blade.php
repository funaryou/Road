<x-layout title="Register">
    <div>
        <!-- アカウント登録フォーム -->
        <form action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="text" name="name" placeholder="名前">
            <input type="email" name="email" placeholder="メールアドレス">
            <input type="text" name="phone_number" placeholder="電話番号">
            <input type="file" name="icon" placeholder="アイコン">
            <input type="password" name="password" placeholder="パスワード">
            <input type="password" name="password_confirmation" placeholder="パスワード（確認用）">
            <button type="submit">登録</button>
        </form>

        <!-- エラーメッセージ -->
        @if ($errors->any())
            <div class="text-red-500">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</x-layout>