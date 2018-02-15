<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BooksController extends Controller
{
    //
    function getCatalogue($c1,$c2){
        $t=file_get_contents("http://www.bqg5200.com/xiaoshuo/{$c1}/{$c2}");
        $content=iconv("gb2312", "utf-8//IGNORE",$t);
       // preg_match_all("/<li class=\"fj\"><h3>正文<\/h3><div class=\"border-line\"><\/div> <\/li>(.+)<div class=\"all_ad clearfix mtop\" id=\"ad_980_2\"><\/div>/",$content,$list);
        $content=strstr($content,"<li class=\"fj\"><h3>正文</h3><div class=\"border-line\"></div> </li>");
        $content=strstr($content,'</ul>',true);
        $content=strstr($content,'<li>');
        $content=strstr($content,'&nbsp;',true);
        preg_match_all("/html\">(.*)<\/a>/",$content,$contentArray);
        preg_match_all("/\/xiaoshuo\/(.*)\.html\">/",$content,$linkArray);
        $return=array_map([$this,'array_union'],$linkArray,$contentArray);
//        dump($return[1]);
        return view('article.list')->with('lists',$return[1]);
    }

    function array_union($a1,$a2){
        $rs=[];
        foreach ($a1 as $k=>$v){
            $value=explode('/',$v);
            $rs[$k]['link'][]=$value[0];
            $rs[$k]['link'][]=$value[1];
            $rs[$k]['link'][]=$value[2];
            $rs[$k]['name']=$a2[$k];
        }
        return $rs;
    }

    function getContent($c1,$c2,$c3){
        $t=file_get_contents("http://www.bqg5200.com/xiaoshuo/{$c1}/{$c2}/{$c3}.html");
        $content=iconv("gb2312", "utf-8//IGNORE",$t);
        preg_match("/<h1>(.*)<\/h1>/",$content,$header);
       preg_match("/<div class=\"kongwei\"><\/div><div class=\"ad250left\">(.*)/",$content,$detail);
       $rs['header']=$header[1];
      preg_match("/<\/div>(.*)/",$detail[1],$info);
       $rs['detail']=$info[1];
       if(empty($rs['detail'])) $rs['detail']="不意思这个文章已经走丢了！";
      $rs['detail']=preg_replace("/<br \/>/","",$rs['detail']);
        $rs['detail']=preg_replace("/<\/div>/","",$rs['detail']);
        preg_match('/<div class=\"jump1\"><span>(.*)<\/span> <a href=\"(.*).html\"><<上一章/',$content,$lastPage);
        preg_match('/标记书签<\/a> <a href=\"(.*).html\">下一章/',$content,$nextPage);
        $rs['lastPage']=empty($lastPage) ? null :$lastPage[2];
        $rs['nextPage']=empty($nextPage) ? null :$nextPage[1];
        $rs['c1']=$c1;
        $rs['c2']=$c2;
        dump($rs);
        dump($lastPage);
        dump($nextPage);
       return view('article.detail')->with('info',$rs);
    }
}
