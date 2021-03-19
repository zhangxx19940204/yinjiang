<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{
    //首页
    public function index(Request $request){
        return '隐匠官网开发中';
        $index_news  = DB::table('yj_news')->limit(5)->orderBy('updated_at','desc')->orderBy('id','desc')->get();
        return view('home.index',['news'=>$index_news,'page_info'=>(array)$request->get('pageInfo')]);
    }
    //关于我们
    public function about(Request $request){
        return view('home.about',['page_info'=>(array)$request->get('pageInfo')]);
    }
    //品牌优势
    public function brand(Request $request){
        return view('home.brand',['page_info'=>(array)$request->get('pageInfo')]);
    }
    //产品展示
    public function product(Request $request){
        return view('home.product',['page_info'=>(array)$request->get('pageInfo')]);
    }
    //店面展示
    public function store(Request $request){
        $store_list = DB::table('yj_store')->orderBy('id','desc')->simplePaginate(6);
        return view('home.store',['store_list'=>$store_list,'page_info'=>(array)$request->get('pageInfo')]);
    }
    //招商合作
    public function join(Request $request){
        return view('home.join',['page_info'=>(array)$request->get('pageInfo')]);
    }
    //新闻中心
    public function news(Request $request){
        $news = DB::table('yj_news')->orderBy('updated_at','desc')->orderBy('id','desc')->simplePaginate(6);
        return view('home.news',['news'=>$news,'page_info'=>(array)$request->get('pageInfo')]);
    }
    //新闻详情
    public function new_detail(Request $request,$id){
        $new_detail = DB::table('yj_news')->where('id','=',$id)->first();
        $last_info  = DB::table('yj_news')->where('id','<',$id)->orderBy('id','desc')->first();
        $page_info = ['page_title'=>$new_detail->title,'page_keywords'=>$new_detail->keywords,'page_description'=>$new_detail->summary];
        return view('home.new_detail',['new_detail'=>$new_detail,'last_info'=>$last_info,'page_info'=>$page_info]);
    }
    //公司团队
    public function team(Request $request){
        $team_introduce = [];
        $team_introduce[] = ['li_class'=>'main12_second_li_odd','img'=>'home-team-image01@2x.png','title'=>'投资顾问','introduce'=>'为餐饮创业者提供详细的解答','list'=>['为您解答担心和疑虑，介绍公司专业团队。','为您讲解项目保障、优惠，整体项目运营流程。','为您讲解选址团队、设计团队、督导团队、技术培训团队、运营团队、营销团队、客服团队以及建立门店的全流程。']];

        $team_introduce[] = ['li_class'=>'main12_second_li_even','img'=>'home-team-image02@2x.png','title'=>'选址','introduce'=>'大数据选址分析','list'=>['大数据选址分析，制定完整店面评估方案','为您提供具有市场消费力巨大的选址方案。','用专业数据和多年选址经验为您贴心制定完整店面评估方案，从选址到周边环境为您负责，帮您把关。']];
//
//        $team_introduce[] = ['li_class'=>'main12_second_li_odd','img'=>'home-team-image03@2x.png','title'=>'设计','introduce'=>'为餐饮创业者提供详细的室内设计方案','list'=>['设计——平面布局图+效果图','总部专业设计师合理布局，利用好门店的每一平方。','根据门店实情，量身定制，专业指导装修设计，装修进度全程跟进。']];
//
//
//        $team_introduce[] = ['li_class'=>'main12_second_li_even','img'=>'home-team-image04@2x.png','title'=>'督导','introduce'=>'为您负责，为您把关','list'=>['监督完整店面选址评估方案','监督店面整体装修进度和食材质量选用等','按照标准化流程进行督察，提供专业门店管理指导，开业统筹一条龙服务。']];
//
//
//        $team_introduce[] = ['li_class'=>'main12_second_li_odd','img'=>'home-team-image05@2x.png','title'=>'技术培训','introduce'=>'帮您解决担心和疑虑，手把手实操教学 ','list'=>['下江腩针对合作商前期开店，设立了专业的餐饮商学院。','没有经验？不用担心，手把手实操教学。','提供开业前全方位系统培训，如产品培训、营销技能、管理技能等。']];
//
//        $team_introduce[] = ['li_class'=>'main12_second_li_even','img'=>'home-team-image06@2x.png','title'=>'运营教学','introduce'=>'现场观摩门店实际经营过程','list'=>['与各大门户网站合作，自有媒体运营团队。','腾讯、微博、抖音、小红书，全方位媒体曝光。','下江腩专业运营团队运用多年专业经验，手把手教您吸客、锁客、留客。']];
//
//
//        $team_introduce[] = ['li_class'=>'main12_second_li_odd','img'=>'home-team-image07@2x.png','title'=>'营销策划','introduce'=>'针对每个店面经营情况出最适合的营销方案 ','list'=>['专业策划团队，提供各类活动节假日促销方案。','腾讯、微博、抖音、小红书、全方位媒体曝光。','江腩总部大区督导在门店检查后，不仅会按照标准化流程进行督查，还会因地因人制宜，对您进行一系列培训，并针对每个合作伙伴的店面经营情况给出最适合的营销方案。']];
//
//        $team_introduce[] = ['li_class'=>'main12_second_li_even','img'=>'home-team-image08@2x.png','title'=>'客服团队','introduce'=>'一对一跟踪','list'=>['保障产品的标准化统一配送。','服务每一位加盟商。','节省了采购中浪费的金钱人力成本，保证了操作好上手，千店一味。']];

        return view('home.team',['team_introduce'=>$team_introduce,'page_info'=>(array)$request->get('pageInfo')]);
    }
    //留言板
    public function message(Request $request){
        return view('home.message',['page_info'=>(array)$request->get('pageInfo')]);
    }
}
