<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /** 储存share到redis */
        $id=Auth::id();
        $share_key=Auth::user()->share;
        $key='share:'.$share_key."_".$id;
        $value=$share_key;
        Redis::set($key,$value);
        Redis::expire($key,3600);
        return view('home');
    }

    /**
     * 获取内容
     * @param string $search
     *
     */
    public function getContent(Request $request,$page=0)
    {

        $search=$request->get('s');
        $search=empty($search) ? '':$search;
        $info=[];
        $ssurl=hh_out((get_URL("http://zhannei.baidu.com/cse/search?q=" . $search . "&p=".$page."&s=17194782488582577862&nsid=")));
        preg_match_all('/<a cpos="title" href="(.*?)" title="(.*?)" class="result-game-item-title-link" target="_blank">/', $ssurl, $tt);
        preg_match_all('/<span class="result-game-item-info-tag-title preBold">类型：<\/span> <span class="result-game-item-info-tag-title">(.*?)<\/span>/', $ssurl, $txtid);
        preg_match_all('/<span class="result-game-item-info-tag-title preBold">作者：<\/span> <span>(.*?)<\/span>/', $ssurl, $by);
        preg_replace('/utf-8/','gbk',$ssurl);
        $labels=$this->getLabel($ssurl);
        $articles=$this->getArticle($ssurl);
//        dump($articles);
        $info['label']=$labels;
        $info['books']=$articles;
        $info['last']=$this->getLast($ssurl);
        $info['next']=$this->getNext($ssurl);
//        $info['content']=$ssurl;
        dump($info);
        return view('article.index')->with('info',$info);
    }

    /**
     * get Label
     * @param $content
     * @return array $allLabel
     */
    private function getLabel($content){
        preg_match_all('/class="result-filter-bd-row-list-item-link sc-game-select">(.*?)<\/a>/',$content,$label);
        $labels=$label[1];
        foreach ($labels as &$v){
            if($v=='0'){
                $v='全部';
            }
        }
        sort($labels);
        return $labels;
    }

    /**
     * get article with author,summary,img and link
     * @param string $content
     * @return array $article
     */
    private function getArticle($content){
        $result=[];
        //get img
        preg_match_all('/class="result-game-item-pic-link" target="_blank" style="width:110px;height:150px;">                <img src="(.*?)" alt="/',$content,$img);
        foreach ($img[1] as $key=>$value){
            $result[$key]['img']=$value;
        }
        //get link
        preg_match_all('/<a cpos="title" href="(.*?)" title/',$content,$link);
        foreach ($link[1]as $key=>$value){
            $specific=strstr($value,'xiaoshuo');
            $specificArray=explode('/',$specific);
            $array[0]=$specificArray[1];
            $array[1]=$specificArray[2];

            $result[$key]['link']=$array;
        }
        //get name
        preg_match_all("/class=\"result-game-item-title-link\" target=\"_blank\">                                    (.*?)                            <\/a> /",$content,$name);
        foreach ($name[1] as $key=>$value){
            $result[$key]['name']=$value;
        }
        //get Summary
        preg_match_all("/<p class=\"result-game-item-desc\">(.*?)<\/p>/",$content,$summary);
         foreach ($summary[1] as $key=>$value){
             $result[$key]['summary']=$value;
         }
        //get author


        return $result;

    }

    /**
     * 查找有没有下一页
     * @param $content
     * @return bool
     */
    private function getLast($content){
        preg_match_all("/a href=\"search?(.*)\" class=\"pager-previous-foot n\" /",$content,$last);
        if(empty($last)) return false;
        if(empty($last[1])) return false;
        $lastArray=explode('=',$last[1][0]);
        $lastNum=substr($lastArray[2],0,1);
        return $lastNum;
    }

    /**
     * 查找有没有下一页
     * @param $content
     * @return bool
     */
    private function getNext($content){
        preg_match_all("/<\/a>                                                                                            <a href=\"search?(.*)\" class=\"pager-next-foot n\" /",$content,$next);
        if(empty($next)) return false;
        if(empty($next[1])) return false;
        $nextArray=explode('=',$next[1][0]);
        $nextNum=substr($nextArray[2],0,1);
        return $nextNum;
//        return $next[1][0];
    }
}
