<?php

namespace App\Http\Controllers;

use App\Photo;
use App\Http\Requests\StorePhoto;

use App\Comment;
use App\Http\Requests\StoreComment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class PhotoController extends Controller
{
    public function __construct()
    {
        //認証が必要
        $this->middleware('auth')->except(['index', 'show']);
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

    //写真一覧
    public function index()
    {
        $photos = Photo::with(['user'])
            ->orderBy(Photo::CREATED_AT, 'desc')->paginate();

        return $photos;
    }

    /**
     * 写真詳細
     * @param string $id
     * @return Photo
     */
    public function show(string $id)
    {
        $photo = Photo::where('id', $id)
            ->with(['user', 'comments.author'])->first();

        return $photo ?? abort(404);
    }

    /**
    * コメント投稿
    * @param Photo $photo
    * @param StoreComment $request
    * @return \Illuminate\Http\Response
    */
    public function addComment(Photo $photo, StoreComment $request)
    {
        $comment = new Comment();
        $comment->content = $request->get('content');
        $comment->user_id = Auth::user()->id;
        $photo->comments()->save($comment);

        //authorリレーションをロードするためにコメントを取得し直す
        $new_comment = Comment::where('id', $comment->id)->with('author')->first();

        return response($new_comment, 201);
    }

    /**
     * 写真ダウンロード
     * @param Photo $photo
     * @return \Illuminate\Http\Response
     */
    public function download(Photo $photo)
    {
        // 写真の存在チェック
        if(! Storage::cloud()->exists($photo->filename)){
            abort(404);
        }

        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $photo->filename . '"',
        ];

        return response(Storage::cloud()->get($photo->filename), 200, $headers);
    }

}


