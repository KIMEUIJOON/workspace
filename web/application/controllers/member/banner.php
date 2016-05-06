<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'Member_Controller.php' );

// バナー
class Banner extends Member_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->config->load('my/image');
	}


	/**
	 * バナー画像ランダム取得
	 */
	public function index()
	{
		$json = array();
		
		$this->load->model('Banner_model', 'Banner');
		
		// ショップ情報取得
		$shop = $this->Banner->read(1);
		
		$banners = array();
		
		if($shop['type1'])
		{
			$banners = unserialize($shop['type1']);
			//shuffle($banners);
		}
		
		$json['src'] = '';
		
		$images = array();
		
		for($i = 1; $i <= SHOP_IMAGE_LIMIT; $i++)
		{
			if (isset($banners[$i]['ext']) && isset($banners[$i]['url']) && $banners[$i]['url'])
			{
				$images[] = array('num' => $i, 'ext' => $banners[$i]['ext'], 'time' => $banners[$i]['time'], 'url' => $banners[$i]['url']);
			}
		}

		shuffle($images);

		if($images)
		{
			$json['src'] = '<a href="'.$images[0]['url'].'" target="_blank"><img id="head_banner" src="' . get_banner_url($images[0]['num'], $images[0]['ext']) . '" /></a>';
		}
		
		$this->output->set_output(json_encode($json));
	}
}