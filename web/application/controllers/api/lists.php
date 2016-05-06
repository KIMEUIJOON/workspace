<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'User_Controller.php' );

class Lists extends User_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->output->set_header("Content-Type: text/xml; charset=utf-8");
	}
	
	/**
	 * 地域のショップリストを取得
	 */
	public function area()
	{
		$view = array();
		$view['status'] = STATUS_OK;
		
		$this->load->model('Shop_Model', 'Shop', TRUE);
		
		$view['prefecture'] = $this->config->item('prefecture');
		
		$area = $this->input->get('code');
		
		$this->shops = $this->Shop->get_area_list($area, $this->get_carrier());
		
		$view['total'] = count($this->shops);
		
		$this->load->view('api/lists/place', $view);
	}
	
	
	/**
	 * タウンのショップリストを取得
	 */
	public function town()
	{
		$view = array();
		$view['status'] = STATUS_OK;
		
		$this->load->model('Shop_Model', 'Shop', TRUE);
		
		$view['prefecture'] = $this->config->item('prefecture');
		
		$town = $this->input->get('code');
		
		$this->shops = $this->Shop->get_town_list($town, $this->get_carrier());
		
		$view['total'] = count($this->shops);
		
		$this->load->view('api/lists/place', $view);
	}
	
	
	/**
	 * 緯度・経度より近隣のショップを取得
	 */
	public function location()
	{
		$view = array();
		$view['status'] = STATUS_OK;
		
		$this->load->model('Shop_Model', 'Shop', TRUE);
		
		$lat = $this->input->get('lat');
		$lng = $this->input->get('lng');
		
		$this->shops = $this->Shop->get_location($lat, $lng, $this->get_carrier(), MAP_RADIUS, LOCATION_LIMIT);
		
		$view['total'] = count($this->shops);
		
		$this->load->view('api/lists/location', $view);
	}
	
	
	/**
	 * 郵便番号より近隣のショップを取得
	 */
	public function zip()
	{
		$view = array();
		$view['status'] = STATUS_NG;
		
		$post_code = $this->input->get('code');
		
		$post_flag = FALSE;
		
		// 郵便番号チェック
		if (preg_match("/^\d{3}\-\d{4}$/", $post_code))
		{
		    $post_flag = TRUE;
		}
		else
		{
			if (preg_match("/^\d{7}/", $post_code))
			{
			    $post_flag = TRUE;
			    $post_code = substr($post_code, 0, 3) . '-' . substr($post_code, 3);
			}
		}

		if ($post_flag !== TRUE)
		{
			$view['message'] = '郵便番号は半角数字7桁で入力してください';
			$this->load->view('api/lists/error', $view);
			return;
		}

		$this->load->model('Post_code_Model', 'Post', TRUE);

		$zip = $this->Post->read($post_code, '*', TRUE);
		
		// 郵便番号が間違っている場合
		if ($zip == FALSE)
		{
			$view['message'] = '郵便番号が正しくありません';
			$this->load->view('api/lists/error', $view);
			return;
		}

		// 緯度・経度が取得済みかチェック
		if (isset($zip['lat']) && isset($zip['lng']))
		{
			$view['lat'] = $zip['lat'];
			$view['lng'] = $zip['lng'];
		}
		else
		{
			// 緯度・経度取得
			$geocode = geocode($zip['id']);
			
			if($geocode['lat'] && $geocode['lng'])
			{
				// 正常に緯度・経度が取得できた場合はデータベースに保存
				$this->db->where('id', $zip['id']);
				$this->db->update('post_code', array('lat' => (float)$geocode['lat'], 'lng' => (float)$geocode['lng']));
				
				$view['lat'] = $geocode['lat'];
				$view['lng'] = $geocode['lng'];
			}
			else
			{
				// 緯度・経度が取得できなかった場合は、areaかtownの緯度・経度を取得
				$location = $this->Post->get_area_tonw_location_by_postcode($zip['id']);
				
				if ($location['town_lat'] && $location['town_lng'])
				{
					$view['lat'] = $location['town_lat'];
					$view['lng'] = $location['town_lng'];
				}
				else if($location['area_lat'] && $location['area_lng'])
				{
					$view['lat'] = $location['area_lat'];
					$view['lng'] = $location['area_lng'];
				}
				else
				{
					$view['message'] = '郵便番号よりエリア情報が取得できませんでした';
					$this->load->view('api/lists/error', $view);
					return;
				}
			}
		}
		
		$this->load->model('Shop_Model', 'Shop');

		$this->shops = $this->Shop->get_location($view['lat'], $view['lng'], $this->get_carrier(), MAP_RADIUS, LOCATION_LIMIT);
		
		$view['total'] = count($this->shops);
		
		$view['status'] = STATUS_OK;
		$this->load->view('api/lists/zip', $view);
	}
}
