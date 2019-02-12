<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhoto;
use App\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class PhotoController extends Controller
{
    public function __construct()
    {
        //認証が必要
        $this->middleware('auth');
    }

    //写真投稿
    public function create(StorePhoto $request)
    {
       
        $photo = new Photo();
        //第一引数はファイルの保存先のパス。「photo/」配下に保存される
        //第二引数は画像ファイル
        //第三引数は外部からのアクセスの可否。publicにすると許可される
        $path = Storage::disk('s3')->putFile('photo', $request->photo, 'public');

        //アップロード先のファイルパスを取得
        $url = Storage::disk('s3')->url($path);
        $photo->filename = $url;
        Auth::user()->photos()->save($photo);
        
        // リソースの新規作成なので
        // レスポンスコードは201(CREATED)を返却する
        return response($photo, 201);
    }
}


