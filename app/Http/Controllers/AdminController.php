<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Routing\Controller as BaseController;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends BaseController
{

    public function __construct()
    {
        $request = request();

        $this->middleware(function ($request, $next) {
            if (!\Session::get('uid')) {
                $this->fail_jump("/blog/public/login", "You must login");
            }
            return $next($request);
        });
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

    public function index()
    {
        $cates = Category::where('parent_id', '<>', null)->get();
        $uid = \Session::get('uid');
        $type = \Session::get('type');
        if ($type == 'admin') {
            $blogs = DB::table('articles')->join('categories', 'articles.cate_id', '=', 'categories.cate_id')
                ->orderBy("create_time", "desc")->paginate(5);

            //$blogs = DB::select('select * from articles a, categories c where a.cate_id=c.cate_id order by a.create_time desc')->paginate(10);
        } else {
            $blogs=DB::table('articles')->join('categories', 'articles.cate_id', '=', 'categories.cate_id')
            ->where("articles.uid", $uid)
            ->orderBy("create_time", "desc")->paginate(5);
        }
        return view('admin/index', ["blog" => $blogs, 'cates' => $cates]);
    }

    public function addcomment(Request $request)
    {
        $article_no = $request->input('article_no');
        $content = $request->input('comment');
        if (trim($content, " ") == "" || mb_strlen($content) < 1) {
            $arr['code'] = 0;
            $arr['msg'] = "content can not be empty";
            return response()->json($arr);
        }
        $uid = \Session::get('uid');
        $create_time = date("Y-m-d H:i:s", time());
        $result = Comment::insertGetId(
            ["article_no" => $article_no, 'content' => $content, 'create_time' => $create_time, 'uid' => $uid]
        );
        if ($result > 0) {
            $arr['code'] = 1;
            $arr['msg'] = "comment successfully";
            $arr['create_time'] = $create_time;
        } else {
            $arr['code'] = 0;
            $arr['msg'] = "comment error!";
        }
        return response()->json($arr);
    }

    public function add_blog(Request $request)
    {
        $title = $request->input('title');
        $content = $request->input('content');
        $cate_id = $request->input('cate_id');
        if (trim($title, " ") == "" || mb_strlen($title) < 1) {
            $arr['code'] = 0;
            $arr['msg'] = "title can not be empty";
            return response()->json($arr);
        }
        if (trim($content, " ") == "" || mb_strlen($content) < 1) {
            $arr['code'] = 0;
            $arr['msg'] = "content can not be empty";
            return response()->json($arr);
        }
        $uid = \Session::get('uid');
        $create_time = date("Y-m-d H:i:s", time());
        $last_update_time = date("Y-m-d H:i:s", time());
        $result = Article::insertGetId(
            ["title" => $title, 'content' => $content, 'create_time' => $create_time, 'last_update_time' => $last_update_time, 'uid' => $uid, 'cate_id' => $cate_id]
        );
        if ($result > 0) {
            $arr['code'] = 1;
            $arr['msg'] = "write blog successfully";
        } else {
            $arr['code'] = 0;
            $arr['msg'] = "write blog error!";
        }
        return response()->json($arr);
    }

    public function delete_blog(Request $request)
    {
        $article_no = $request->input('id');
        $uid = \Session::get('uid');
        $type = \Session::get('type');
        if ($type == 'admin') {
            $deleted = Article::where('article_no', $article_no)->delete();
            if ($deleted) {
                $arr['code'] = 1;
                $arr['msg'] = "delete blog successfully";
            } else {
                $arr['code'] = 0;
                $arr['msg'] = "delete blog failed!";
            }
        } else {
            $articles = Article::where('uid', $uid)->get();
            $flag = false;
            foreach ($articles as $v) {
                if ($v->article_no == $article_no) {
                    $flag = true;
                    break;
                }
            }
            if ($flag) {
                $deleted = Article::where('article_no', $article_no)->delete();
                if ($deleted) {
                    $arr['code'] = 1;
                    $arr['msg'] = "delete blog successfully";
                } else {
                    $arr['code'] = 0;
                    $arr['msg'] = "delete blog failed!";
                }
            } else {
                $arr['code'] = 0;
                $arr['msg'] = "This blog is not yours!";
            }
        }
        return response()->json($arr);
    }

    public function get_blog(Request $request)
    {
        $article_no = $request->input('id');
        $arr['code'] = 1;
        $arr['msg'] = "get blog success!";
        $arr['blog'] = Article::where('article_no', $article_no)->first();
        return response()->json($arr);
    }

    public function edit_blog(Request $request)
    {
        $article_no = $request->input('article_no');
        $title = $request->input('title');
        $content = $request->input('content');
        $cate_id = $request->input('cate_id');
        if (trim($title, " ") == "" || mb_strlen($title) < 1) {
            $arr['code'] = 0;
            $arr['msg'] = "title can not be empty";
            return response()->json($arr);
        }
        if (trim($content, " ") == "" || mb_strlen($content) < 1) {
            $arr['code'] = 0;
            $arr['msg'] = "content can not be empty";
            return response()->json($arr);
        }
        $uid = \Session::get('uid');
        $type = \Session::get('type');
        $last_update_time = date("Y-m-d H:i:s", time());
        if ($type == 'admin') {
            $updated = Article::where('article_no', $article_no)
                ->update(["title" => $title, 'content' => $content, 'last_update_time' => $last_update_time, 'cate_id' => $cate_id]);
            if ($updated) {
                $arr['code'] = 1;
                $arr['msg'] = "delete blog successfully";
            } else {
                $arr['code'] = 0;
                $arr['msg'] = "delete blog failed!";
            }
        } else {
            $articles = Article::where('uid', $uid)->get();
            $flag = false;
            foreach ($articles as $v) {
                if ($v->article_no == $article_no) {
                    $flag = true;
                    break;
                }
            }
            if ($flag) {
                $updated = Article::where('article_no', $article_no)
                    ->update(["title" => $title, 'content' => $content, 'last_update_time' => $last_update_time, 'cate_id' => $cate_id]);
                if ($updated) {
                    $arr['code'] = 1;
                    $arr['msg'] = "update blog successfully";
                } else {
                    $arr['code'] = 0;
                    $arr['msg'] = "update blog failed!";
                }
            } else {
                $arr['code'] = 0;
                $arr['msg'] = "This blog is not yours!";
            }
        }
        return response()->json($arr);
    }

    public function verify_blog(Request $request)
    {
        $article_no = $request->input('id');
        $type = \Session::get('type');
        $last_update_time = date("Y-m-d H:i:s", time());
        if ($type == 'admin') {
            $updated = Article::where('article_no', $article_no)
                ->update(["verify" => 1, 'last_update_time' => $last_update_time]);
            if ($updated) {
                $arr['code'] = 1;
                $arr['msg'] = "verify blog successfully";
            } else {
                $arr['code'] = 0;
                $arr['msg'] = "verify blog failed!";
            }
        } else {
            $arr['code'] = 0;
            $arr['msg'] = "You do not have permission";
        }
        return response()->json($arr);
    }

    public function get_comments()
    {
        $uid = \Session::get('uid');
        $type = \Session::get('type');
        if ($type == 'admin') {
            $comments = Comment::join('articles', 'article_comments.article_no', '=', 'articles.article_no')
                ->join('users', 'users.uid', '=', 'article_comments.uid')
                ->select(['article_comments.comment_no', 'article_comments.content', 'article_comments.create_time', 'users.nick_name', 'articles.title', 'articles.article_no'])
                ->orderBy("create_time", "desc")->paginate(10);
        } else {
            $comments = Comment::join('articles', 'article_comments.article_no', '=', 'articles.article_no')
                ->join('users', 'users.uid', '=', 'article_comments.uid')
                ->where('users.uid', $uid)
                ->select(['article_comments.comment_no', 'article_comments.content', 'article_comments.create_time', 'users.nick_name', 'articles.title', 'articles.article_no'])
                ->orderBy("create_time", "desc")->paginate(10);
        }
        return view('admin/comment', ["comments" => $comments]);
    }

    public function delete_comment(Request $request)
    {
        $comment_no = $request->input('id');
        $uid = \Session::get('uid');
        $type = \Session::get('type');
        if ($type == 'admin') {
            $deleted = Comment::where('comment_no', $comment_no)->delete();
            if ($deleted) {
                $arr['code'] = 1;
                $arr['msg'] = "delete comment successfully";
            } else {
                $arr['code'] = 0;
                $arr['msg'] = "delete comment failed!";
            }
        } else {
            $comments = Comment::where('uid', $uid)->get();
            $flag = false;
            foreach ($comments as $v) {
                if ($v->comment_no == $comment_no) {
                    $flag = true;
                    break;
                }
            }
            if ($flag) {
                $deleted = Comment::where('comment_no', $comment_no)->delete();
                if ($deleted) {
                    $arr['code'] = 1;
                    $arr['msg'] = "delete comment successfully";
                } else {
                    $arr['code'] = 0;
                    $arr['msg'] = "delete comment failed!";
                }
            } else {
                $arr['code'] = 0;
                $arr['msg'] = "This comment is not yours!";
            }
        }
        return response()->json($arr);
    }

    public function count_comment()
    {
        $uid = \Session::get('uid');
        $num = Comment::join('articles', 'article_comments.article_no', '=', 'articles.article_no')
        ->join('users', 'articles.uid', '=', 'users.uid')
        ->where('articles.uid', $uid)->count();
        $arr['code'] = 1;
        $arr['num'] = $num;
        return response()->json($arr);
    }




}