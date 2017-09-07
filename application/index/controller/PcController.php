<?php
namespace app\index\controller;
use app\index\model\News;
use app\index\model\TechNews;
use app\index\model\BigData;
use think\Controller;
use think\Request;
/**
* 
*/
class PcController extends Controller
{
	public function pcIndex()
    {
        $day=0;
        $present_day=date("Y年n月j日");
        if(count($_POST)==0)
        {
            $day = (int)date("j");
        }else{
            $present_day=$_POST["date"];
            $day=$_POST["day"];
        }
        //高校
        $news = new News;
        $map['date'] = ['=',$day];
        $map['title'] = ['like','%大学%'];
        $my_news = $news->where($map)
            ->order('point_num desc')
            ->limit(14)
            ->select();
        foreach ($my_news as &$i_sum)
        {
            if(mb_strlen($i_sum['summary'],'utf-8')>120)
            {
                $i_sum['summary']=mb_substr($i_sum['summary'],0,120,'utf-8');
                $i_sum['summary']=$i_sum['summary']."...";
            }
        }
        $this->assign('news',$my_news);
        $this->assign('today',$present_day);
        $this->assign('date',date('Y年m月d日',time()).'新闻速览');

        //科技
        $t_news = new TechNews;
        $tech_news = $t_news->where('date',$day)
            ->order('insert_time desc')
            ->limit(10)
            ->select();
        $this->assign('tech_news',$tech_news);

        //大数据
        $b_news = new BigData;
        $map2['date'] = ['=',$day];
        $map['img'] = ['=','is not null'];
        $bd_news = $b_news->where($map2)
            ->limit(10)
            ->select();
        $this->assign('bd_news',$bd_news);

        return $this->fetch();
    }

    public function pcDetail(){
        $news = new News;
        //$day = Request::instance()->post('day');
        $day = (int)date("j");
        $schools = $news->where('date',$day)
                        ->distinct(true)
                        ->field('school')
                        ->select();
        $all_news = $news->where('date',$day)
                        ->select();
        foreach ($all_news as &$value) {
            $value['title'] = mb_substr($value['title'], 0, 30, 'utf8').'...';
            $value['summary'] = mb_substr($value['summary'], 0, 60, 'utf8').'...';
        }
        $this->assign([
            'schools' => $schools,
            'all_news' => $all_news,
            'day' => $day
        ]);
        return $this->fetch();
    }
    public function pcTechDetail(){
		$tech = new TechNews;
		//$day = Request::instance()->post('day');
		$day = (int)date("j");
		$tech_news = $tech->where('date',$day)
						  ->order('insert_time desc')
						  ->select();
		$this->assign([
			'tech_news' => $tech_news,
			'day' => $day
		]);
		return $this->fetch();
	}
	public function pcBdDetail(){
		$bd = new BigData;
		//$day = Request::instance()->post('day');
		$day = (int)date("j");
		$map['date'] = ['eq',$day];
        $bd_news = $bd->where("date=$day and img is not null")
        			  ->select();
		$this->assign([
			'bd_news' => $bd_news,
			'day' => $day
		]);
		return $this->fetch();
	}
    public function pcSearch(){
	    $key=$_POST["searchbox"];
	    $key2=$_POST["searchbox"];
	    if($key=="")
            $key="gggggggggggggggg";
        $news = new News;
        $map['title'] = ['like',"%$key%"];
        $re1 = $news->where($map)
                    ->distinct(true)
                    ->field('title,url')
                    ->select();
        $t_news = new TechNews;
        $re2 = $t_news->where($map)
                    ->distinct(true)
                    ->field('title,url')
                    ->select();
        $b_news = new BigData;
        $re3 = $b_news->where($map)
                    ->distinct(true)
                    ->field('title,url')
                    ->select();
        $replace="<font color=orange>$key</font>";
        foreach ($re1 as &$i1)
        {
            $i1['title']=str_replace($key,$replace,$i1['title']);
        }
        foreach ($re2 as &$i1)
        {
            $i1['title']=str_replace($key,$replace,$i1['title']);
        }
        foreach ($re3 as &$i1)
        {
            $i1['title']=str_replace($key,$replace,$i1['title']);
        }
        $count =count($re1)+count($re2)+ count($re3);
        $this->assign('result1',$re1);
        $this->assign('result2',$re2);
        $this->assign('result3',$re3);
        $this->assign('keyword',$key2);
        $this->assign('count',$count);
        return $this->fetch();
    }
}