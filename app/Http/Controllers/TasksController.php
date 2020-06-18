<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
        
        // 認証済みユーザを取得
            $user = \Auth::user();
        // ユーザのタスク一覧を取得
            $tasks = $user->tasks()->get();
            
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        
        //タスク一覧ビューでそれを表示
        // return view('tasks.index',[
        //     'tasks' => $tasks,
        //     ]);
        // }else{
        }
        return view('welcome',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;
        
        //タスク作成ビュー
        return view('tasks.create',[
            'task' => $task,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',
        ]);
        
        //タスクを作成
        $task = new Task;
        $task->status = $request->status;
        $task->content = $request->content;
        $task->user_id = $request->user()->id;
        $task->save();
        
        //トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        //idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        //認証済みユーザ（閲覧者）がそのタスクの所有者であれば、タスク詳細を表示
        if(\Auth::id() === $task->user_id){
        // タスク詳細ビューでそれを表示
        return view('tasks.show',[
            'task' => $task,
            ]);
        }else{
            return redirect('/');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        //認証済みユーザ（閲覧者）がそのタスクの所有者であれば、タスク編集を表示
        if(\Auth::id() === $task->user_id){
        //タスク編集ビューでそれを表示
        return view('tasks.edit',[
            'task' => $task,
            ]);
        }else{
            return redirect('/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',
        ]);
        
        //idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        //タスクを更新
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
        
        //トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        //認証済みユーザ（閲覧者）がそのタスクの所有者であれば、タスクを削除
        if(\Auth::id() === $task->user_id){
            $task->delete();
        }
        
        //トップページへリダイレクトさせる
        return redirect('/');
    }
    

}
