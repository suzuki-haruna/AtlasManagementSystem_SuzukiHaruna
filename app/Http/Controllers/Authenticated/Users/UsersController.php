<?php

namespace App\Http\Controllers\Authenticated\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Gate;
use App\Models\Users\User;
use App\Models\Users\Subjects;
use App\Searchs\DisplayUsers;
use App\Searchs\SearchResultFactories;

class UsersController extends Controller //Controllerというクラスを継承したUsersControllerというクラス
{
    public function showUsers(Request $request){ // showUsers(/show/users)を閲覧してる方がブラウザから入力したデータを渡してください
        $keyword = $request->keyword; // $keyword = "keyword"に入力されたデータを下さい
        $category = $request->category; // $category = "category"に入力されたデータを下さい
        $updown = $request->updown; // $updown = "updown"に入力されたデータを下さい
        $gender = $request->sex; // $gender = "sex"に入力されたデータを下さい
        $role = $request->role; // $role = "role"に入力されたデータを下さい

        //$subjects = null; // $subjects = 空 // ここで検索時の科目を受け取る
        //$subjects = $request->subjects; // $subjects = "subjects"に入力されたデータを下さい
        $subjects = $request->input('subjects', []); // $subjects = "subjects"に入力されたデータを1つずつ下さい。デフォルト値として空配列[]を代入。
        //$results = Subjects::whereIn('id', $subjects)->get(); // subjects を条件にデータベースから取得
        //$subjects = Subjects::all(); //↓

        /*$subjectsIds = $request->input('subjects', []); // チェックボックスから選択されたsubjectsIDを取得
        // 選択されたsubjectsIDに基づいて製品を検索
        /*$userSubjects = User::when(!empty($subjectsIds), function ($query) use ($subjectsIds) {
            $query->whereIn('subject', $subjectsIds);
        })->get();*/
        //中間テーブルver
        /*$userSubjects = User::when(!empty($subjectsIds), function ($query) use ($subjectsIds) {
            $query->whereHas('subjects', function ($q) use ($subjectsIds) {
                $q->whereIn('subjects.id', $subjectsIds);
            });
        })->get();*/
        // ユーザーを科目で絞り込むver
        /*$users = User::whereHas('subjects', function ($query) use ($subjects) {
            $query->whereIn('subjects.id', $subjects);
        })->get();*/

        // ベースのクエリ
        /*$query = User::query();
        // 選択科目フィルタ
        if ($request->filled('subjects')) {
        $query->whereHas('subjects', function ($q) use ($request) {
            $q->whereIn('subjects.id', $request->input('subjects'));
        });
        }
        // ユーザーリストと科目リストを取得
        $users = $query->get();*/
        //$subjects = Subject::all();

        /*$query = User::query();

        if ($request->filled('subjects')) {
        $subjects = $request->input('subjects'); // 選択された科目IDを取得

        $query->whereHas('subjects', function ($q) use ($subjects) {
        $q->where(function ($subQuery) use ($subjects) {
            foreach ($subjects as $subjectId) {
                $subQuery->orWhere('subjects.id', $subjectId);
            }
        });
        });
        }

        $users = $query->get();*/

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

        return view('authenticated.users.search', compact('users', 'subjects'));  //,'subjectsIds')); // ビューに、authenticated.users.search(blade.php)を表示。$users$subjectsを渡す。
    }

    /*public function search(Request $request){
        $subjectsIds = $request->input('subjects', []);

        $userSubjects = User::when(!empty($subjectsIds), function ($query) use ($subjectsIds) {
            $query->whereHas('subjects', function ($q) use ($subjectsIds) {
                $q->whereIn('subjects.id', $subjectsIds);
            });
        })->get();

        $subjects = Subjects::all(); // subjectsも再取得してフォームに表示

        return view('authenticated.users.search', compact('users', 'subjects', 'subjectsIds'));
    }*/

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
