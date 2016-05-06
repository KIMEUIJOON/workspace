<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'User_Controller.php' );

class Map extends User_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->output->set_header("Content-Type: text/xml; charset=utf-8");
		$this->output->set_header("cache-control: no-cache");
	}
	
	
	/**
	 * パラメーターより各mapを取得
	 */
	public function control()
	{
		$mode = $this->input->get('mode');
		
		if($mode == 'location')
		{
			$this->location();
		}
		else if($mode == 'area')
		{
			$this->area();
		}
		else if($mode == 'town')
		{
			$this->town();
		}
		else
		{
			$this->load->model('Post_code_Model', 'Post', TRUE);
			$view['status'] = STATUS_NG;
			$view['message'] = 'マップ情報が取得できませんでした';
			$this->load->view('api/map/error', $view);
			$this->Post->error($view['message'] . __LINE__, TRUE);
			return;
		}
	}
	
	
	/**
	 * 緯度・経度より近隣のショップを取得
	 */
	public function location()
	{
		$view = array();
		$view['status'] = STATUS_OK;
		
		$this->load->model('Shop_Model', 'Shop', TRUE);
		
		$view['lat'] = $this->input->get('lat');
		$view['lng'] = $this->input->get('lng');
		
		$this->shops = $this->Shop->get_location($view['lat'], $view['lng'], $this->get_carrier(), MAP_RADIUS, LOCATION_LIMIT);
		
		$view['total'] = count($this->shops);
		/*
		if(strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false)
		{
			header('Content-Encoding: gzip');
			header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
			header('Pragma: no-cache');
			header("Content-Type: text/xml; charset=utf-8");
			echo gzencode($this->load->view('api/map/maps', $view, TRUE),9);
			exit;
		}
		*/
		$this->load->view('api/map/maps', $view);
	}
	
	
	/**
	 * 指定エリアより近隣のショップを取得
	 */
	public function area()
	{
		$view = array();
		$view['status'] = STATUS_OK;
		
		$this->load->model('Post_code_Model', 'Post', TRUE);

		$area = $this->Post->get_area($this->input->get('code'));
		
		if ($area == FALSE || is_null($area['lat']))
		{
			$view['status'] = STATUS_NG;
			$view['message'] = 'エリア情報が取得できませんでした';
			$this->load->view('api/map/error', $view);
			$this->Post->error($view['message'] . __LINE__, TRUE);
			return;
		}

		$this->load->model('Shop_Model', 'Shop');

		$view['lat'] = $area['lat'];
		$view['lng'] = $area['lng'];

		$this->shops = $this->Shop->get_location($area['lat'], $area['lng'], $this->get_carrier(), MAP_RADIUS, LOCATION_LIMIT);
		
		$view['total'] = count($this->shops);
		
		$this->load->view('api/map/maps', $view);
	}
	
	
	/**
	 * 指定タウンより近隣のショップを取得
	 */
	public function town()
	{
		$view = array();
		$view['status'] = STATUS_OK;
		
		$this->load->model('Post_code_Model', 'Post', TRUE);

		$town = $this->Post->get_town($this->input->get('code'));
		
		// タウンコードが間違っている場合
		if ($town == FALSE)
		{
			$view['status'] = STATUS_NG;
			$view['message'] = 'エリア情報が取得できませんでした';
			$this->load->view('api/map/error', $view);
			$this->Post->error($view['message'] . __LINE__, TRUE);
			return;
		}

		// 緯度・経度が取得済みかチェック
		if (isset($town['lat']) && isset($town['lng']))
		{
			$view['lat'] = $town['lat'];
			$view['lng'] = $town['lng'];
		}
		else
		{
			// 緯度・経度が取得済みでない場合はarea情報を取得
			$area = $this->Post->get_area(substr($town['id'], 0, 5));
			
			if ($area == FALSE || is_null($area['lat']))
			{
				$view['status'] = STATUS_NG;
				$view['message'] = 'エリア情報が取得できませんでした';
				$this->load->view('api/map/error', $view);
				$this->Post->error($view['message'] . __LINE__, TRUE);
				return;
			}
			
			$view['lat'] = $area['lat'];
			$view['lng'] = $area['lng'];
			
			$address = $area['name'] . $town['name'];
			
			// 緯度・経度取得
			$geocode = geocode($address);
			
			if($geocode['lat'] && $geocode['lng'])
			{
				// 正常に緯度・経度が取得できた場合はデータベースに保存
				$this->db->where('id', $town['id']);
				$this->db->update('town_code', array('lat' => (float)$geocode['lat'], 'lng' => (float)$geocode['lng']));
				
				$view['lat'] = $geocode['lat'];
				$view['lng'] = $geocode['lng'];
			}
		}
		
		$this->load->model('Shop_Model', 'Shop');

		$this->shops = $this->Shop->get_location($view['lat'], $view['lng'], $this->get_carrier(), MAP_RADIUS, LOCATION_LIMIT);
		
		$view['total'] = count($this->shops);
		
		$this->load->view('api/map/maps', $view);
	}
}
