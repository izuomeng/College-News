<?php
namespace app\detail\controller;
use app\index\model\News;
use app\index\model\TechNews;
use app\index\model\BigData;
use think\Controller;
use think\Request;
/**
* 
*/
class DetailController extends Controller
{	
	public function list(){
		$news = new News;
		$day = Request::instance()->post('day');
		$schools = $news->where('date',$day)
						->distinct(true)
						->field('school')
						->select();
		$this->assign([
			'schools' => $schools,
			'day' => $day
		]);
		return $this->fetch();
	}

	public function choose(){
		$news = new News;
		$school = Request::instance()->post('school');
		$day = Request::instance()->post('day');
		$map['date'] = ['=',$day];
		$map['school'] = ['=',$school];
		$school_news = $news->where("date=$day and school='$school'")
							->select();
		foreach ($school_news as &$value) {
			$value['title'] = mb_substr($value['title'], 0, 11, 'utf8').'...';
			$value['summary'] = mb_substr($value['summary'], 0, 32, 'utf8').'...';
		}
		$this->assign([
			'school' => $school,
			'school_news' => $school_news,
			'day' => $day
		]);
		return $this->fetch();
	}

	public function all(){
		$news = new News;
		$day = Request::instance()->post('day');
		$schools = $news->where('date',$day)
						->distinct(true)
						->field('school')
						->select();
		$all_news = $news->where("date=$day")
						->select();
		foreach ($all_news as &$value) {
			$value['title'] = mb_substr($value['title'], 0, 11, 'utf8').'...';
			$value['summary'] = mb_substr($value['summary'], 0, 32, 'utf8').'...';
		}
		$this->assign([
			'schools' => $schools,
			'all_news' => $all_news,
			'day' => $day
		]);
		return $this->fetch();
	}

	public function techDetail(){
		$tech = new TechNews;
		$day = Request::instance()->post('day');
		$tech_news = $tech->where('date',$day)
						  ->order('insert_time desc')
						  ->select();
		$this->assign([
			'tech_news' => $tech_news,
			'day' => $day
		]);
		return $this->fetch();
	}

	public function bdDetail(){
		$bd = new BigData;
		$day = Request::instance()->post('day');
		$map['date'] = ['eq',$day];
        $map['img'] = ['neq','is null'];
        $bd_news = $bd->where($map)
        			  ->select();
		$this->assign([
			'bd_news' => $bd_news,
			'day' => $day
		]);
		return $this->fetch();
	}
}