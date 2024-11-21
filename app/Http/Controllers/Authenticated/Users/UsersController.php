<?php

namespace App\Http\Controllers\Authenticated\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Gate;
use App\Models\Users\User;
use App\Models\Users\Subjects;
use App\Searchs\DisplayUsers;
use App\Searchs\SearchResultFactories;

class UsersController extends Controller
{

    public function showUsers(Request $request){ // showUsers(/show/users)を閲覧してる方がブラウザから入力したデータを渡してください
        $keyword = $request->keyword; // $keyword = "keyword"に入力されたデータを下さい
        $category = $request->category; // $category = "category"に入力されたデータを下さい
        $updown = $request->updown; // $updown = "updown"に入力されたデータを下さい
        $gender = $request->sex; // $gender = "sex"に入力されたデータを下さい
        $role = $request->role; // $role = "role"に入力されたデータを下さい

        //$subjects = null; // $subjects = 空 // ここで検索時の科目を受け取る
        $subjects = $request->subjects; // $subjects = "subjects"に入力されたデータを下さい
        //$subjects = Subjects::find($request->subjects);
        //$subjects = Subjects::where($request->subjects)->get();
        //$subjects = "";
        //$subjects = Subjects::get();
        //search,array☆
        /*$subjects = [1,2,3]; // $subjects =
        $query = User::query();
        $query->whereHas('subjects', function($q) use($subjects)  {
        $q->orWhereIn('subjects.id', $subjects);
        });*/
        //ddd($subjects);

        // △
        $userFactory = new SearchResultFactories(); // $userFactory = SearchResultFactories を作成
        $users = $userFactory->initializeUsers($keyword, $category, $updown, $gender, $role, $subjects);
        // $users = $userFactory-> (SearchResultFactories)initialize(初期化する)Usersメソッド($keyword, $category, $updown, $gender, $role, $subjects)

        $subjects = Subjects::all(); // $subjects = Subjectsテーブル内の全ての情報を一覧として表示 //Subjects::all();
        //ddd($subjects);

        return view('authenticated.users.search', compact('users', 'subjects')); // ビューに、authenticated.users.search(blade.php)を表示。$users$subjectsを渡す。
    }

    public function userProfile($id){
        $user = User::with('subjects')->findOrFail($id);
        $subject_lists = Subjects::all();
        return view('authenticated.users.profile', compact('user', 'subject_lists'));
    }

    public function userEdit(Request $request){
        $user = User::findOrFail($request->user_id);
        $user->subjects()->sync($request->subjects);
        return redirect()->route('user.profile', ['id' => $request->user_id]);
    }
}
