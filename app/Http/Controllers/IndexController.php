<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class IndexController extends BaseController
{

    public function __construct(){
		if(session_status() == 1){
            session_start();    
        }
	}

    private function succ_jump($url, $str = "Operation successfully")
    {
        echo "<pre style='position:relative;z-index:999;padding:10px;border-radius:5px;background:#43ad7f7f;border:1px solid #aaa;font-zize:14px;line-height:18px;opacity:0.9;'>" . $str . "</pre>";
        $u = "refresh:3;url=" . $url;
        header($u);
        exit();
    }

    private function fail_jump($url, $str = "Operation Failed!")
    {
        echo "<pre style='position:relative;z-index:999;padding:10px;border-radius:5px;background:#ddad22;border:1px solid #aaa;font-zize:14px;line-height:18px;opacity:0.9;'>" . $str . "</pre>";
        header("refresh:3;url=" . $url);
        exit();
    }

    public function logout(Request $request)
    {
        session()->forget('uid');
        session()->forget('nickname');
        session()->forget('type');
        $request->session()->forget(['uid', 'nickname', 'type']);
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect('index');
    }

    public function index()
    {
        
        // $blogs = DB::select($sql);
        // $blogs = Article::paginate(5);
        $blogs = Article::where('verify', 1)->orderBy("create_time", "desc")->paginate(5);
        return view('index', ["blogs"=>$blogs]);
    }

    public function article($id){
        $sql = 'select a.article_no, a.uid, a.title, u.nick_name, a.content, a.click, a.create_time, a.last_update_time, t.comment_num, c.cate_id, c.cate_name
        from articles a, categories c, users u,
        (
        select a.article_no, COUNT(*) as comment_num from articles a
        LEFT JOIN article_comments ac ON a.article_no=ac.article_no
        GROUP BY a.article_no
        ) t
        where a.cate_id=c.cate_id and a.uid=u.uid
        and a.article_no=t.article_no
        and a.verify=1
        and a.article_no=?
        order by a.create_time desc';
        $article = DB::select($sql, [$id]);
        if(empty($article)){
            $this->fail_jump('/blog/public/index', 'article not exists');
            return;
        }
        DB::table('articles')->where('article_no', $id)->increment('click');

        $comments = Comment::join('users', 'article_comments.uid', '=', 'users.uid')
        ->select(['article_comments.uid', 'content','article_comments.create_time', 'users.nick_name'])->where('article_no', $id)->get();//->paginate(5);

        return view('article', ["article"=>$article[0], 'comments'=>$comments]);
        //return response()->json($comments);
    }

    public function register_user(Request $request)
    {
        $nickname = $request->input('nickname');
        $email = $request->input('email');
        $password = $request->input('password');

        if (trim($nickname, " ") == "" || mb_strlen($nickname) < 6) {
            $arr['code'] = 0;
            $arr['msg'] = "nickname must be at least 6 charactors";
            return response()->json($arr);
        }
        if (trim($password, " ") == "" || mb_strlen($password) < 6) {
            $arr['code'] = 0;
            $arr['msg'] = "password must be at least 6 charactors";
            return response()->json($arr);
        }
        if (trim($email, " ") == "") {
            $arr['code'] = 0;
            $arr['msg'] = "email can't be empty!";
            return response()->json($arr);
        }

        $user = User::where('email', $email)->first();
        if (!empty($user)) {
            $arr['code'] = 0;
            $arr['msg'] = "The email has been registered by other user, please change a new email.";
            return response()->json($arr);
        }

        $type = 'login';
        $create_time = date("Y-m-d H:i:s", time());
        $last_update_time = date("Y-m-d H:i:s", time());
        $hashed = Hash::make($password, [
            'memory' => 1024,
            'time' => 2,
            'threads' => 2,
        ]);
        $result = User::insertGetId(
            ["email"=>$email, 'nick_name' => $nickname, 'password' => $hashed, 'create_time' => $create_time, 'last_update_time' => $last_update_time, 'type' => $type]
        );
        // $insert_flag = DB::insert(
        //     'insert into users(email, nick_name, password, create_time, last_update_time, type) values(?, ?, ?, ?, ?, ?)',
        //     [$email, $nickname, $hashed, $create_time, $last_update_time, $type]
        // );
        if ($result>0) {
            $arr['code'] = 1;
            $arr['msg'] = "register success";
        } else {
            $arr['code'] = 0;
            $arr['msg'] = "register error!";
        }
        return response()->json($arr);
    }


    public function verify(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $user = User::where('email', $email)->first();
        if (empty($user)) {
            $arr['code'] = 0;
            $arr['msg'] = "The email did not register";
            return response()->json($arr);
        }
        if (Hash::check($password, $user->password)) {
            $arr['code'] = 1;
            $arr['msg'] = "login succcessfully";
            $request->session()->put('uid', $user->uid);
            $request->session()->put('nickname', $user->nick_name);
            $request->session()->put('type', $user->type);
        }else{
            $arr['code'] = 0;
            $arr['msg'] = "password error";
        }
        return response()->json($arr);
    }

    public function profile($id){
        $user = User::where('uid', $id)->first();
        $articles = Article::where('verify', 1)->where('uid', $id)->orderBy("create_time", "desc")->paginate(5);
        $comments = Comment::join('articles', 'article_comments.article_no', '=', 'articles.article_no')
            ->join('users', 'users.uid', '=', 'article_comments.uid')
            ->where('users.uid', $id)
            ->select(['article_comments.comment_no', 'article_comments.content','article_comments.create_time', 'users.nick_name', 'articles.title', 'articles.article_no'])
            ->orderBy("create_time", "desc")->paginate(5);
        return view('profile', ['user'=>$user, "articles"=>$articles, 'comments'=>$comments]);
        //return response()->json($comments);
    }

    
    public function category($id){
        $blogs = Article::where('verify', 1)->where('cate_id', $id)->orderBy("create_time", "desc")->paginate(5);
        return view('category', ["blogs"=>$blogs]);
        //return response()->json($comments);
    }

    public function blogs(){
        $blogs = Article::join('users', 'users.uid', '=', 'articles.uid')
        ->where('verify', 1)->select(['article_no', 'title', 'content', 'users.nick_name', 'articles.create_time', 'articles.last_update_time'])
        ->orderBy("create_time", "desc")->get();
        $arr['code'] = 200;
        $arr['msg'] = 'success';
        $arr['data'] = $blogs;
        return response()->json($arr);
    }


}