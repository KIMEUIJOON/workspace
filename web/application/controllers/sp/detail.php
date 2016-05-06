<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'User_Controller.php' );

class Detail extends User_Controller
{
	public $appli = false;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('Shop_Model', 'Shop', TRUE);
		$this->config->load('my/s_config');
		$this->config->load('my/image');
		$this->config->load('my/pdf');
	}
	
	
	/**
	 * 詳細ページ
	 */
	public function index($id = '')
	{
		// idが無くcodeがある場合はアプリからアクセス
		if(!$id)
		{
			$id = $this->input->get('code');
			
			if(!$id) show_404();
			
			$this->appli = true;
		}

		$shop = $this->Shop->read($id, '*', TRUE);
		
		if(!$shop)
		{
			show_404();
		}
		
		if($shop['photos'])
		{
			$shop['photos'] = unserialize($shop['photos']);
		}
		
		if($shop['flyer_image'])
		{
			$shop['flyer_image'] = unserialize($shop['flyer_image']);
		}

		if($shop['flyer_pdf'])
		{
			$shop['flyer_pdf'] = unserialize($shop['flyer_pdf']);
		}
		
		// youtubeが表示できるかチェック
		$shop['youtube_view'] = false;

		if($shop['op8'] && $shop['youtube'])
		{
			$shop['youtube_view'] = true;
		}
		
		// レビューが表示できるかチェック
		$shop['review_view'] = false;
		$shop['review_send'] = false;
		
		if($shop['op7'] && $shop['review_flag'] == 0)
		{
			$shop['review_view'] = false;
		}
		else
		{
			$this->load->model('Review_Model', 'Review');
			
			$review = $this->Review->counting($id);
			
			$shop['review_count'] = $review['cnt'];
				
			if($review['cnt'])
			{
				$shop['review_score'] = round($review['score_sum'] / $review['cnt'], 1);
			}
			
			$shop['review_view'] = true;
			$shop['review_send'] = true;
		}
		
		// 画像・動画が表示できるかチェック
		$shop['photos_view'] = false;
		
		if($shop['op8'])
		{
			for($i = 1; $i <= SHOP_IMAGE_LIMIT; $i++)
			{
				if (isset($shop['photos'][$i]))
				{
					$shop['photos_view'] = true;
					break;
				}
			}
		}
		
		// チラシ・POPが一つでも表示できるかチェック
		$shop['flyer_view'] = false;
		
		if($shop['op5'])
		{
			for($i = 1; $i <= SHOP_FLYER_IMAGE_LIMIT; $i++)
			{
				if (isset($shop['flyer_image'][$i]['ext']) && $shop['flyer_image'][$i]['txt'])
				{
					$shop['flyer_view'] = true;
					break;
				}
			}
			for($i = 1; $i <= SHOP_FLYER_PDF_LIMIT; $i++)
			{
				if (isset($shop['flyer_pdf'][$i]['time']) && $shop['flyer_pdf'][$i]['txt'])
				{
					$shop['flyer_view'] = true;
					break;
				}
			}
		}
		
		// メールマガジン表示できるかチェック
		$shop['newsletter_view'] = false;
		
		if($shop['op4'])
		{
			$shop['newsletter_view'] = true;
		}
		
		// 求人情報表示できるかチェック
		$shop['job_view'] = false;
		
		if($shop['op6'] && $shop['job'])
		{
			$shop['job_view'] = true;
		}
		
		// クーポン情報が表示できるかチェック
		$shop['coupon_view'] = false;
		
		if($shop['op3'] && $shop['coupon_body'])
		{
			if($shop['coupon_date'] > time())
			{
				$shop['coupon_view'] = true;
			}
		}
		
		// キャンペーン情報が表示できるかチェック
		$shop['campaign_view'] = false;
		$shop['campaign_summary_view'] = false;
		$shop['campaign_body_view'] = false;
		
		if($shop['op1'] && $shop['campaign'])
		{
			if($shop['campaign_date'] > time())
			{
				$shop['campaign_view'] = true;
				$shop['campaign_summary_view'] = true;
			}
		}
		if($shop['op2'] && $shop['campaign_body'])
		{
			if($shop['campaign_date'] > time())
			{
				$shop['campaign_view'] = true;
				$shop['campaign_body_view'] = true;
			}
		}
		
		$this->load->view('sp/detail/index', $shop);
	}
	
	
	/**
	 * レビューページ
	 */
	public function review($id = '', $page = 1)
	{
		// idが無くcodeがある場合はアプリからアクセス
		if(!$id)
		{
			$id = $this->input->get('code');
			
			if(!$id) show_404();
			
			$this->appli = true;
		}
		
		// アプリからのアクセスかチェック
		$type = $this->input->get('type');
		
		if($type == 'app')
		{
			$this->appli = true;
		}
		
		if(is_int0($page) === FALSE) show_404();

		$shop = $this->Shop->read($id, 'id, name, op7, review_flag, status_flag', TRUE);
		
		if(!$shop)
		{
			show_404();
		}

		// レビューを表示しない設定かチェック
		if($shop['op7'] && $shop['review_flag'] == 0)
		{
			show_404();
		}
		
		$this->load->model('Review_Model', 'Review');

		$offset = ($page - 1) * REVIEW_LIMIT;
			
		$review = $this->Review->counting($id);

		$review['reviews'] = array();
		
		$review['next'] = false;
		$review['page'] = $page;
		
		if($review['cnt'])
		{
			// レビュー一覧を取得
			$review['reviews'] = $this->Review->lists($id, REVIEW_LIMIT, $offset);
			$review['review_score'] = round($review['score_sum'] / $review['cnt'], 1);
		}
		
		if($review['cnt'] > (REVIEW_LIMIT + $offset))
		{
			$review['next'] = $page+1;
		}
		
		$this->load->view('sp/detail/review', $shop + $review);
	}
	
	
	/**
	 * map
	 */
	public function map($id = '')
	{
		if(!$id) show_404();

		$shop = $this->Shop->read($id, '*', TRUE);
		
		if(!$shop)
		{
			show_404();
		}
		
		if($shop['lat'] && $shop['lng'])
		{
			$this->load->view('sp/detail/map', $shop);
		}
		else
		{
			show_404();
		}
	}
}
