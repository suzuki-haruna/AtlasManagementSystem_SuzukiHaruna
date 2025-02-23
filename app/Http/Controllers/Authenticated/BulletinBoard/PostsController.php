<?php

namespace App\Http\Controllers\Authenticated\BulletinBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Posts\Like;
use App\Models\Users\User;
use App\Http\Requests\BulletinBoard\PostFormRequest;
use Auth;


class PostsController extends Controller
{

//→
    public function show(Request $request){
        $posts = Post::with('user', 'postComments', 'subCategories', 'mainCategory')->get(); // $posts = Postモデル('user'と'postComments'のデータを一緒に取得。モデルにはリレーションのみ)->全てのPostレコードを取得。返す。/追加subCategories,mainCategory
        //ddd($posts);
        $categories = MainCategory::get(); // $categories = MainCategoryモデル::main_categoriesテーブルの全てのレコードを取得。
        $sub_categories = SubCategory::all(); // 【追加】
        $like = new Like; // $like = Likeクラスのテンプレートをもとに、新しいオブジェクトを作成。
        $post_comment = new Post; // $post_comment = Postクラスのテンプレートをもとに、新しいオブジェクトを作成。

        // 検索にキーワードが入力されていたら
        if(!empty($request->keyword)){
            $posts = Post::with('user', 'postComments', 'subCategories') // $posts = Postモデル('user'と'postComments'のデータを一緒に取得。)＋ 'subCategories'追加。
            ->where('post_title', 'like', '%'.$request->keyword.'%') // ->条件追加(posts'post_title'カラム：部分一致検索)
            ->orWhere('post', 'like', '%'.$request->keyword.'%') // ->条件追加、または(posts'post'カラム：部分一致検索)
            //->orWhere('sub_category', 'like', '%'.$request->keyword.'%') // 【追加】サブカテゴリー
            ->orWhereHas('subCategories', function ($query) use ($request) {
            $query->where('sub_category', $request->keyword);
            })
            ->get(); // 結果を取得。

            // カテゴリーがクリックされたら
        /*}else if($request->category_word){
            $sub_category = $request->category_word; // $sub_category = category_wordから送信されたリクエストデータを取得。
            $posts = Post::with('user', 'postComments', 'subCategories')

            ->whereHas('subCategories', function ($query) use ($sub_category) {
            $query->where('sub_category', $sub_category); // サブカテゴリーが一致する投稿を取得
        })

            ->get(); // $posts = Postモデル('user'と'postComments'のデータを一緒に取得。)->全てのPostレコードを取得。返す。*/

        /*}else if ($request->has('category_word')) {
            $categoryId = $request->category_word;
            $posts = Post::with('user', 'postComments', 'subCategories')
            ->whereHas('subCategories', function ($query) use ($categoryId) {
            $query->where('id', $categoryId);
        })
        ->get();*/

        }else if ($request->filled('category_word')) {
            $sub_category = $request->category_word;
            //ddd($request->all());
            $posts = Post::with('user', 'postComments', 'subCategories')
            ->whereHas('subCategories', function ($query) use ($sub_category) {
            $query->where('sub_categories.id', $sub_category);
        })
        ->get();

        }else if($request->like_posts){
            $likes = Auth::user()->likePostId()->get('like_post_id');
            $posts = Post::with('user', 'postComments')
            ->whereIn('id', $likes)->get();
        }else if($request->my_posts){
            $posts = Post::with('user', 'postComments')
            ->where('user_id', Auth::id())->get();
        }
        //ddd($posts);
        //ddd($sub_categories);
        return view('authenticated.bulletinboard.posts', compact('posts', 'categories', 'like', 'post_comment','sub_categories')); // sub_categories追加
    }
//←

    public function postDetail($post_id){
        $post = Post::with('user', 'postComments')->findOrFail($post_id);
        return view('authenticated.bulletinboard.post_detail', compact('post'));
    }

    public function postInput(){
        $main_categories = MainCategory::get();
        $sub_categories = SubCategory::get(); // 追加
        return view('authenticated.bulletinboard.post_create', compact('main_categories', 'sub_categories')); //'sub_categories'
    }

//→
    public function postCreate(PostFormRequest $request){

        // 新しい投稿を作成
        $post = Post::create([
            'user_id' => Auth::id(),
            'post_title' => $request->post_title,
            'post' => $request->post_body,
            'created_at' => now() // 追加/現在時刻を設定
        ]);

        //$subCategories->posts()->sync($request->input('post_category_id'));

        //この辺から
        //$subCategories = $request->input('subCategories', []); // フォームから送信されたサブカテゴリのIDを取得
        //$subCategories = $request->
//$subCategories = $request->post_category_id;
        // 中間テーブルに登録
//if (!empty($subCategories)) {
//$post->subCategories()->attach($subCategories);
//}
        //ddd($request->all());

        // サブカテゴリを中間テーブルに登録
        $subCategories = $request->input('post_category_id', []);
        if (!empty($subCategories)) {
            $post->subCategories()->sync($subCategories);
        }

        return redirect()->route('post.show');
    }
//←

    public function postEdit(Request $request){
        $request->validate([
              'post_title' => 'required|string|max:100',
              'post_body' => 'required|string|max:5000',
        ]);

        Post::where('id', $request->post_id)->update([
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function postDelete($id){
        Post::findOrFail($id)->delete();
        return redirect()->route('post.show');
    }

    public function mainCategoryCreate(Request $request){ //(Request $request)
        $request->validate([
              'main_category_name' => 'required|max:100|string|unique:main_categories,main_category',
        ]);

        MainCategory::create(['main_category' => $request->main_category_name]);
        return redirect()->route('post.input');
    }

    // サブカテゴリー追加
    public function subCategoryCreate(Request $request){
        $request->validate([
              'main_category_id' => 'exists:main_categories,id',
              'sub_category_name' => 'required|max:100|string|unique:sub_categories,sub_category',
        ]);

        SubCategory::create([ //☆
            'sub_category' => $request->sub_category_name,
            //'main_category_id' => $request->sub_category_name,
            'main_category_id' => $request->main_category_id,
            //'main_category_id' => MainCategory::id(),
        ]);
        //ddd($request);
        return redirect()->route('post.input');
    }

    public function commentCreate(Request $request){
        $request->validate([
              'comment' => 'required|string|max:250',
        ]);

        PostComment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function myBulletinBoard(){
        $posts = Auth::user()->posts()->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_myself', compact('posts', 'like'));
    }

    public function likeBulletinBoard(){ // 【掲示板風】
        $like_post_id = Like::with('users')->where('like_user_id', Auth::id())->get('like_post_id')->toArray();
        $posts = Post::with('user')->whereIn('id', $like_post_id)->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_like', compact('posts', 'like'));
    }

    public function postLike(Request $request){
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->like_user_id = $user_id;
        $like->like_post_id = $post_id;
        $like->save();

        return response()->json();
    }

    public function postUnLike(Request $request){
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->where('like_user_id', $user_id)
             ->where('like_post_id', $post_id)
             ->delete();

        return response()->json();
    }
}
