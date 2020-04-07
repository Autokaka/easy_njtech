<?php

require_once(__DIR__ . '/../vendor/autoload.php');
use Goutte\Client;

class Njtech {
	
	private $configs;
	private $browser;
	
	private $entrance = 'https://i.njtech.edu.cn';
	private $jwb = 'http://jwb.njtech.edu.cn';
	private $jwgl = 'https://jwgl.njtech.edu.cn/sso/ktiotlogin';
	private $jwglReal = 'https://jwgl.njtech.edu.cn';
	
	public function __construct($configs) {
		$this->configs = $configs;
		$this->browser = new Client();
	}
	
	private function packMsg($statusCode = false, $message = '') {
		return json_encode([
			'statusCode' => $statusCode,
			'message' => $message,
		], JSON_UNESCAPED_UNICODE);
	}
	
	/// 附带强制下线处理的登录解决方案
	private function loginCheck() {
		try {
			$result = json_decode($this->login());
			$this->browser->request('GET', $this->jwgl);
			return $result->statusCode;
		} catch (Exception $e) {
			$result = json_decode($this->login());
			$this->browser->request('GET', $this->jwgl);
			return $result->statusCode;
		}
	}
	
	/// 登录
	public function login() {
		// 登录环境初始化
		$configs = $this->configs;
		$browser = $this->browser;
		$entrance = $this->entrance;
		
		try {
			// 执行登录
			$crawler = $browser->request('GET', $entrance);
			$form = $crawler->selectButton('login')->form();
			$form['username'] = $configs['username'];
			$form['password'] = $configs['password'];
			$crawler = $browser->submit($form)
			->filter('#msg')
			->text();
			
			// 找到了#msg说明还在登录页面, 登录失败
			return $this->packMsg(false, '登录失败');
		} catch (Exception $e) {
			// 没找到, 会抛出异常, 说明登录成功
			$profile = $entrance
			.'/app/profile/data?accountKey=XH&accountValue='
			.$configs['username']
			.'&uri=open_api/customization/bxsjbxx/list';
			$crawler = $browser->request('GET', $profile);
			$response = $browser->getResponse()->getContent();
			return $this->packMsg(true, json_decode($response));
		}
	}
	
	/// 校历
	public function calendar() {
		// 登录检查
		if(!$this->loginCheck()) return $this->packMsg(false, '登录失败');
		
		// 教务部环境初始化
		$configs = $this->configs;
		$browser = $this->browser;
		$jwb = $this->jwb;
		
		try {
			// 校历链接查询
			$crawler = $browser->request('POST', $jwb)
			->filter('.see > a')
			->attr('href');
			
			// 查询校历图片
			$calendar = $jwb.'/'.$crawler;
			$crawler = $browser->request('POST', $calendar)
			->filter('.img_vsb_content')->attr('src');
			
			// 返回查询链接
			return $this->packMsg(true, $crawler);
		} catch(Exception $e) {
			return $this->packMsg(false, '接口需要更新, 请联系开发者维护接口');
		}
	}
	
	/// 课表
	public function schedule() {
		// 登录检查
		if(!$this->loginCheck()) return $this->packMsg(false, '登录失败');
		
		// 教务管理环境初始化
		$configs = $this->configs;
		$browser = $this->browser;
		$schedule = $this->jwglReal.'/kbcx/xskbcx_cxXsKb.html?gnmkdm=N2151';
		
		try {
			// 查询当前学校时间
			$schoolTerm = $this->entrance.'/index.php';
			$crawler = $browser->request('GET', $schoolTerm)->html();
			$pattern = '/"schoolTerm":({.*?})/';
			preg_match($pattern, $crawler, $match);
			$schoolTerm = json_decode($match[1], true);
			/*
			// $schoolTerm
			Array (
				[currentWeek] => 6
				[startDate] => 2020-02-17T00:00:00+08:00
				[endDate] => 2020-07-05T00:00:00+08:00
				[academicYear] => 2019-2020
			)
			*/
			
			// 学年学期判断
			$yearSplit = explode('-', $schoolTerm['academicYear']);
			$formData = [
				'xnm' => $yearSplit[0],
				'xqm' => $yearSplit[1],
			];
			
			// 请求课表数据
			$crawler = $browser->request('POST', $schedule, $formData);
			$response = $browser->getResponse()->getContent();
			$response = json_decode($response, true);
			$response['schoolTerm'] = $schoolTerm;
			return $this->packMsg(true, $response);
		} catch(Exception $e) {
			return $this->packMsg(false, '接口需要更新, 请联系开发者维护接口');
		}
	}
	

	/// 成绩
	public function score() {
		// 登录检查
		if(!$this->loginCheck()) return $this->packMsg(false, '登录失败');
		
		// 教务部环境初始化
		$configs = $this->configs;
		$browser = $this->browser;
		$score = $jwglReal.'/cjcx/cjcx_cxDgXscj.html?doType=query&gnmkdm=N305005';
		
		try {
			// 直接查询所有数据
			$formData = [
				'xnm' => '',
				'xqm' => '',
				'_search' => false,
				'queryModel.showCount' => 512,
				'queryModel.currentPage' => 1,
				'queryModel.sortOrder' => 'asc',
				'time' => 0,
			];
			
			// 请求成绩数据
			$crawler = $browser->request('POST', $score, $formData);
			$response = $browser->getResponse()->getContent();
			return $this->packMsg(true, json_decode($response));
		} catch (Exception $e) {
			return $this->packMsg(false, '接口需要更新, 请联系开发者维护接口');
		}
	}
	
	/// 校车
	public function bus() {
		$browser = $this->browser;
		$bus = 'http://hqjt.njtech.edu.cn/jtfw/xcsk.htm';
		
		try {
			// 请求校车数据
			$crawler = $browser->request('GET', $bus)->html();
			// print_r($crawler);
			$pattern = '/<tbody style="box-sizing: border-box;">(.*)?/';
			preg_match($pattern, $crawler, $match);
			print_r($match);
		} catch (Exception $e) {
			return $this->packMsg(false, '接口需要更新, 请联系开发者维护接口');
		}
	}
	
}

?>