<x-layout title="Login">
    <div>
        <!-- ログインフォーム -->
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <input type="email" name="email" placeholder="メールアドレス">
            <input type="password" name="password" placeholder="パスワード">
            <button type="submit">ログイン</button>
        </form>

        <!-- ログイン失敗時のエラーメッセージ -->
        @if (session('error'))
            <div class="text-red-500 mb-4">
                {{ session('error') }}
            </div>
        @endif

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
