<?php

$config = array(

	'shop_add' => array(
					array(
						'field'	=> 'status_flag',
						'label'	=> 'ステータス',
						'rules'	=> 'required|is_natural',
						'type'	=> 'int'
					),
					array(
						'field'	=> 'name',
						'label'	=> 'ショップ名',
						'rules'	=> 'required|cntrl|space_beautify',
						'type'	=> 'string'
					),
					array(
						'field'	=> 'carrier',
						'label'	=> 'キャリア',
						'rules'	=> 'required',
						'type'	=> 'string'
					),
					array(
						'field' => 'post_code',
						'label' => '郵便番号',
						'rules' => 'required|cntrl|zip_code',
						'type'	=> 'string'
					),
					array(
						'field'	=> 'prefecture',
						'label'	=> '都道府県',
						'rules'	=> 'required',
						'type'	=> 'string'
					),
					array(
						'field'	=> 'address',
						'label'	=> '住所',
						'rules'	=> 'required|cntrl|space_beautify',
						'type'	=> 'string'
					),
					array(
						'field'	=> 'building',
						'label'	=> '建物名等',
						'rules'	=> 'cntrl|space_beautify',
						'type'	=> 'string'
					),
					array(
						'field'	=> 'lat',
						'label'	=> '経度',
						'rules'	=> 'required|numeric|cntrl|space_beautify',
						'type'	=> 'float'
					),
					array(
						'field'	=> 'lng',
						'label'	=> '緯度',
						'rules'	=> 'required|numeric|cntrl|space_beautify',
						'type'	=> 'float'
					),
					array(
						'field'	=> 'tel1',
						'label'	=> '電話番号１',
						'rules'	=> 'required|valid_tel|cntrl|space_beautify',
						'type'	=> 'string'
					),
					array(
						'field'	=> 'tel2',
						'label'	=> '電話番号２',
						'rules'	=> 'valid_tel|cntrl|space_beautify',
						'type'	=> 'string'
					),
					array(
						'field'	=> 'hours',
						'label'	=> '営業時間',
						'rules'	=> 'cntrl_rn|space_beautify',
						'type'	=> 'string'
					),
					array(
						'field'	=> 'holiday',
						'label'	=> '定休日',
						'rules'	=> 'cntrl_rn|space_beautify',
						'type'	=> 'string'
					),
					array(
						'field'	=> 'parking_flag',
						'label'	=> '駐車場',
						'rules'	=> 'required|is_natural',
						'type'	=> 'int'
					),
					array(
						'field'	=> 'barrier_free_flag',
						'label'	=> 'バリアフリー',
						'rules'	=> 'required|is_natural',
						'type'	=> 'int'
					),
					array(
						'field'	=> 'kids_flag',
						'label'	=> 'キッズコーナー',
						'rules'	=> 'required|is_natural',
						'type'	=> 'int'
					),
					array(
						'field'	=> 'classes_flag',
						'label'	=> 'ケータイ教室<',
						'rules'	=> 'required|is_natural',
						'type'	=> 'int'
					),
					array(
						'field'	=> 'payment_flag',
						'label'	=> '料金収納',
						'rules'	=> 'required|is_natural',
						'type'	=> 'int'
					),
					array(
						'field'	=> 'repair_flag',
						'label'	=> '修理受付',
						'rules'	=> 'required|is_natural',
						'type'	=> 'int'
					),
					array(
						'field'	=> 'counter',
						'label'	=> 'カウンター数',
						'rules'	=> 'numeric',
						'type'	=> 'int'
					),
				),

	'member_basic' => array(
					array(
						'field'	=> 'name',
						'label'	=> 'ショップ名',
						'rules'	=> 'required|cntrl|space_beautify',
						'type'	=> 'string'
					),
					array(
						'field'	=> 'carrier',
						'label'	=> 'キャリア',
						'rules'	=> 'required',
						'type'	=> 'string'
					),
					array(
						'field' => 'post_code',
						'label' => '郵便番号',
						'rules' => 'required|cntrl|zip_code',
						'type'	=> 'string'
					),
					array(
						'field'	=> 'prefecture',
						'label'	=> '都道府県',
						'rules'	=> 'required',
						'type'	=> 'string'
					),
					array(
						'field'	=> 'address',
						'label'	=> '住所',
						'rules'	=> 'required|cntrl|space_beautify',
						'type'	=> 'string'
					),
					array(
						'field'	=> 'building',
						'label'	=> '建物名等',
						'rules'	=> 'cntrl|space_beautify',
						'type'	=> 'string'
					),
					array(
						'field'	=> 'lat',
						'label'	=> '経度',
						'rules'	=> 'required|numeric|cntrl|space_beautify',
						'type'	=> 'float'
					),
					array(
						'field'	=> 'lng',
						'label'	=> '緯度',
						'rules'	=> 'required|numeric|cntrl|space_beautify',
						'type'	=> 'float'
					),
					array(
						'field'	=> 'tel1',
						'label'	=> '電話番号１',
						'rules'	=> 'required|valid_tel|cntrl|space_beautify',
						'type'	=> 'string'
					),
					array(
						'field'	=> 'tel2',
						'label'	=> '電話番号２',
						'rules'	=> 'valid_tel|cntrl|space_beautify',
						'type'	=> 'string'
					),
					array(
						'field'	=> 'hours',
						'label'	=> '営業時間',
						'rules'	=> 'cntrl_rn|space_beautify',
						'type'	=> 'string'
					),
					array(
						'field'	=> 'holiday',
						'label'	=> '定休日',
						'rules'	=> 'cntrl_rn|space_beautify',
						'type'	=> 'string'
					),
					array(
						'field'	=> 'parking_flag',
						'label'	=> '駐車場',
						'rules'	=> 'required|is_natural',
						'type'	=> 'int'
					),
					array(
						'field'	=> 'barrier_free_flag',
						'label'	=> 'バリアフリー',
						'rules'	=> 'required|is_natural',
						'type'	=> 'int'
					),
					array(
						'field'	=> 'kids_flag',
						'label'	=> 'キッズコーナー',
						'rules'	=> 'required|is_natural',
						'type'	=> 'int'
					),
					array(
						'field'	=> 'classes_flag',
						'label'	=> 'ケータイ教室<',
						'rules'	=> 'required|is_natural',
						'type'	=> 'int'
					),
					array(
						'field'	=> 'payment_flag',
						'label'	=> '料金収納',
						'rules'	=> 'required|is_natural',
						'type'	=> 'int'
					),
					array(
						'field'	=> 'repair_flag',
						'label'	=> '修理受付',
						'rules'	=> 'required|is_natural',
						'type'	=> 'int'
					),
					array(
						'field'	=> 'counter',
						'label'	=> 'カウンター数',
						'rules'	=> 'numeric',
						'type'	=> 'int'
					),
				),
		
		
	'parents_add' => array(
					array(
						'field'	=> 'name',
						'label'	=> '会社名',
						'rules'	=> 'required|cntrl|space_beautify',
						'type'	=> 'string'
					),
					array(
						'field' => 'post_code',
						'label' => '郵便番号',
						'rules' => 'cntrl|zip_code',
						'type'	=> 'string'
					),
					array(
						'field'	=> 'prefecture',
						'label'	=> '都道府県',
						'rules'	=> '',
						'type'	=> 'string'
					),
					array(
						'field'	=> 'address',
						'label'	=> '住所',
						'rules'	=> 'cntrl|space_beautify',
						'type'	=> 'string'
					),
					array(
						'field'	=> 'building',
						'label'	=> '建物名等',
						'rules'	=> 'cntrl|space_beautify',
						'type'	=> 'string'
					),
					array(
						'field'	=> 'tel1',
						'label'	=> '電話番号',
						'rules'	=> 'required|valid_tel|cntrl|space_beautify',
						'type'	=> 'string'
					),
				)
);
