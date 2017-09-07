<?php
namespace app\index\controller;
use app\index\model\News;
use app\index\model\TechNews;
use app\index\model\BigData;
use think\Controller;
use think\Collection;
class IndexController extends Controller
{

    public function index()
    {
    	//高校
    	$day = (int)date("j");
    	$news = new News;
    	$map['date'] = ['=',$day];
    	$map['title'] = ['like','%大学%'];
        $my_news = $news->where($map)
                        ->order('point_num desc')
        				->limit(20)
        				->select();
        $GLOBALS['ten_days'] = array();
        $GLOBALS['post_day'] = array();
        $GLOBALS['post_month'] = array();
        $GLOBALS['post_year'] = array();
        for ($i=1; $i <= 10; $i++) { 
        	$ten_days[$i] = date("n月j日 ",strtotime("-$i day"));
        	$post_year[$i] = date("Y ",strtotime("-$i day"));
        	$post_month[$i] = date("n ",strtotime("-$i day"));
        	$post_day[$i] = date("j ",strtotime("-$i day"));
        }
        $this->assign([
        	'ten_days' => $ten_days,
        	'year' => $post_year,
        	'month' => $post_month,
        	'day' => $post_day,
        	'today' => $day
        ]);
        $this->assign('news',$my_news);
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
        $bd_news = $b_news->where("date = $day and img is not null")
        					->limit(10)
        					->select();
        $this->assign('bd_news',$bd_news);

        return $this->fetch();
    }

    public function search(){
        $text = $_POST['search'];
        $news = new News;
        $tech = new TechNews;
        $bd = new BigData;
        if (strpos($text, '%')!==false || strpos($text, '_')!==false) {
            $text='bucunzaideya';
        }
        $cur['title'] = ['like',"'%$text%'"];
        $school_news = $news->where("title like '%$text%'")
                            ->order('date desc')
                            ->select();
        $tech_news = $tech->where("title like '%$text%'")
                          ->distinct(true)
                          ->field('title,url')
                          ->select();
        $bd_news = $bd->where("title like '%$text%'")
                      ->distinct(true)
                      ->field('title,url')
                      ->select();
        $result = 1;
        if (empty($school_news)) {
            if (empty($tech_news)) {
                if (empty($bd_news)) {
                    $result = 0;
                }
            }
        }
        foreach ($school_news as &$value) {
            $value['title'] = str_replace($text,"<font style='color: orange'>".$text."</font>",$value['title']);
        }
        foreach ($tech_news as &$value) {
            $value['title'] = str_replace($text,"<font style='color: orange'>".$text."</font>",$value['title']);
        }
        foreach ($bd_news as &$value) {
            $value['title'] = str_replace($text,"<font style='color: orange'>".$text."</font>",$value['title']);
        }
        $this->assign([
            'school_news' => $school_news,
            'tech_news' => $tech_news,
            'bd_news' => $bd_news,
            'result' => $result
        ]);
         return $this->fetch();
    }

    public function history(){
    	$year = $_POST['year'];
    	$month = $_POST['month'];
    	$day = $_POST['day'];
        //高校
    	$news = new News;
    	$map['date'] = ['=',$day];
    	$map['title'] = ['like','%大学%'];
        $my_news = $news->where($map)
        				->limit(20)
        				->select();

        $ten_days = array();
        $post_day = array();
        $post_month = array();
        $post_year = array();
        for ($i=1; $i <= 10; $i++) { 
        	$ten_days[$i] = date("n月j日 ",strtotime("-$i day"));
        	$post_year[$i] = date("Y ",strtotime("-$i day"));
        	$post_month[$i] = date("n ",strtotime("-$i day"));
        	$post_day[$i] = date("j ",strtotime("-$i day"));
        }
        $this->assign([
        	'ten_days' => $ten_days,
        	'year' => $post_year,
        	'month' => $post_month,
        	'day' => $post_day,
        	'today' => $day,
        	'date' => $year."年".$month."月".$day."日新闻速览"
        ]);
        $this->assign('news',$my_news);

        //科技
        $t_news = new TechNews;
        $tech_news = $t_news->where('date',$day)
        					->order('insert_time desc')
        					->limit(10)
        					->select();
        $this->assign('tech_news',$tech_news);

        //大数据
        $b_news = new BigData;
        $bd_news = $b_news->where("date = $day and img is not null")
        					->limit(10)
        					->select();
        $this->assign('bd_news',$bd_news);
        return $this->fetch();
    }
}
