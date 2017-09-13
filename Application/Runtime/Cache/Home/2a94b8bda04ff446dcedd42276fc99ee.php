<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>太阳城娱乐城</title>
	<script type="text/javascript" src="http://cdn.webfont.youziku.com/wwwroot/js/wf/youziku.api.min.js?"></script>
	<!-- <script src="/Public/assets/js/wav.js"></script> -->
	<link rel="stylesheet" type="text/css" href="/Public/assets/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="/Public/content/css/font-awesome.min.css" />
	<link rel="stylesheet" media="screen and (min-width:1900px)" href="/Public/assets/css/style.css"/>
<!--  	<link rel="stylesheet" type="text/css" media="screen and (max-width:1800px)" href="/Public/assets/css/style4.css" /> -->
 	<link rel="stylesheet" media="screen and (max-width:1800px)" type="text/css" href="/Public/assets/css/style5.css" />
    <script src="/Public/assets/js/jquery.min.js"></script>
    <script src="/Public/assets/js/bootstrap.min.js"></script>
	

</head>
<body  onselectstart="return false;" style="-moz-user-select:none;">
<input type="hidden" id="PMSX" value="<?php echo ($PMSX); ?>" >
<input type="hidden" id="TMHM" value="<?php echo ($TMHM); ?>" >
<input type="hidden" id="TMBS" value="<?php echo ($TMBS); ?>" >
<input type="hidden" id="TMDX" value="<?php echo ($TMDX); ?>" >
<input type="hidden" id="TMDS" value="<?php echo ($TMDS); ?>" >
<input type="hidden" id="JQYS" value="<?php echo ($JQYS); ?>" >

<input type="hidden" id="M_PMSX" value="<?php echo ($M_PMSX); ?>" >
<input type="hidden" id="M_TMHM" value="<?php echo ($M_TMHM); ?>" >
<input type="hidden" id="M_TMBS" value="<?php echo ($M_TMBS); ?>" >
<input type="hidden" id="M_TMDX" value="<?php echo ($M_TMDX); ?>" >
<input type="hidden" id="M_TMDS" value="<?php echo ($M_TMDS); ?>" >
<input type="hidden" id="M_JQYS" value="<?php echo ($M_JQYS); ?>" >

	<div class="center">
		<div class="top">
			<div class="top_l pull-left">
			</div>
			<div class="top_r pull-right">
				<div class="ewm pull-right text-right">
					<img class="" src="/Public/assets/img/ewm.jpg" alt="二维码">
				</div>
			</div><!-- top_r结束 -->
		</div><!-- top结束 -->

		<div class="subject">
			<div class="subject_l pull-left">
				<div class="bulleyin">
					<div class="bulleyin_l pull-left colfaf">
						公告
					</div>
					<div id="parent" class="bulleyin_r pull-right">
						<span id="child" class="bulleyin_text"><?php echo ($Message); ?>
						</span>
					</div>
				</div><!-- bulleyin结束 -->

				<div class="user_info">
					<div class="user_l pull-left">
						<img src="/Public/assets/img/level.jpg" alt="img">
					</div><!-- user_l结束 -->
					<div class="user_c pull-left">
						<div class="user_c_u">
							<span>欢迎回来，</span><span><?php echo ($username); ?></span>
						</div>
						<div class="user_c_d">
							<span>您的金额：</span><span class="colfaf money_span" id="money_span"><?php echo ($money); ?></span>
						</div>
					</div><!-- user_c结束 -->
					<div class="user_r pull-right">
						<div class="user_r_u">
							<img src="/Public/assets/img/email.jpg" alt="img">
						</div>
						<div class="user_r_d">
							<img src="/Public/assets/img/money.jpg" alt="img">
						</div>
					</div><!-- user_r结束 -->
				</div><!-- user_info结束 -->

				<div class="history">
					<div class="history_top text-center">
						<span class="history_top_span colfaf">
							近期走势
						</span>
					</div><!-- history_top结束 -->
					<div class="history_content" id="dataview2">
                              <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "暂时没有数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><table class="history_tab" cellspacing="cellspacing">
            					<tr>
            						<td class="td_action">
            							<div class="colfaf"><?php echo ($vo["issue1"]); ?></div>
            						</td>
            						<td>
            							<div class="n_<?php echo ($vo["hm1"]); ?>"><?php echo ($vo["hm1"]); ?></div>
            						</td>
            						<td>
            							<div class="n_<?php echo ($vo["hm2"]); ?>"><?php echo ($vo["hm2"]); ?></div>
            						</td>
            						<td>
            							<div class="n_<?php echo ($vo["hm3"]); ?>"><?php echo ($vo["hm3"]); ?></div>
            						</td>
            						<td>
            							<div class="n_<?php echo ($vo["hm4"]); ?>"><?php echo ($vo["hm4"]); ?></div>
            						</td>
            						<td>
            							<div class="n_<?php echo ($vo["hm5"]); ?>"><?php echo ($vo["hm5"]); ?></div>
            						</td>
            						<td>
            							<div class="n_<?php echo ($vo["hm6"]); ?>"><?php echo ($vo["hm6"]); ?></div>
            						</td>
            						
            						<td>
            							<div class="colyellow plus_bg"></div>
            						</td>
            						<td>
            							<div class="n_<?php echo ($vo["hm7"]); ?>"><?php echo ($vo["hm7"]); ?></div>
            						</td>
            						<td>
            							<div class="cla_big"><?php echo ($vo["dx"]); ?></div>
            						</td>
            						<td>
            							<div class="cla_odd"><?php echo ($vo["ds"]); ?></div>
            						</td>
            					</tr>
            					<tr>
            						<td>
            							<div class="colyellow"><?php echo ($vo["issue2"]); ?></div>
            						</td>
            						<td>
            							<div class="col999"><?php echo ($vo["sx1"]); ?></div>
            						</td>
            						<td>
            							<div class="col999"><?php echo ($vo["sx2"]); ?></div>
            						</td>
            						<td>
            							<div class="col999"><?php echo ($vo["sx3"]); ?></div>
            						</td>
            						<td>
            							<div class="col999"><?php echo ($vo["sx4"]); ?></div>
            						</td>
            						<td>
            							<div class="col999"><?php echo ($vo["sx5"]); ?></div>
            						</td>
            						<td>
            							<div class="col999"><?php echo ($vo["sx6"]); ?></div>
            						</td>
            						
            						<td>
            							<div class="col999">--</div>
            						</td>
            						<td>
            							<div class="col999"><?php echo ($vo["sx7"]); ?></div>
            						</td>
            						<td colspan="2">
            							<div class="n_jq">
										<?php echo ($vo["jqys"]); ?>
										</div>
            						</td>
            					</tr>
        				</table><?php endforeach; endif; else: echo "暂时没有数据" ;endif; ?>	
					</div><!-- history_content结束 -->
				</div><!-- history结束 -->

			</div><!-- subject_l结束 -->


			<div class="subject_r pull-right">

			<div class="subject_r_title">
				<ul class="nav nav-justified">
	                <li class="subject_r_t_item  active"><a >幸运彩票</a></li>
	                <li class="subject_r_t_item "><a data-toggle="modal" data-target="#state" id="state_link">下注状况</a></li>
	                <li class="subject_r_t_item "><a data-toggle="modal" data-target="#record" id="record_link">投注记录</a></li>
	                <li class="subject_r_t_item "><a data-toggle="modal" data-target="#bill" id="bill_link">账单报表</a></li>
	                <li class="subject_r_t_item "><a data-toggle="modal" data-target="#historical" id="historical_link">历史开奖</a></li>
	                <li class="subject_r_t_item "><a data-toggle="modal" data-target="#rule">规则说明</a></li>
	                <!-- <li class="subject_r_t_item "><a data-toggle="modal" data-target="" id="shuaxin">数据刷新</a></li> -->
	            </ul>
			</div><!-- historical_data_title结束 -->
			<div class="tab-content">
            	<div id="tab1" class="tab-pane fade in active">
            		<div class="result">
            			<div class="result_l pull-left">
            				<div class="result_l_u">
            					<span>当前期号：</span><span class="colfaf DQQH"><?php echo ($nextissue); ?></span>
            					<input type="hidden" id="DQQH" value="<?php echo ($nextissue); ?>" >
            					<input type="hidden" id="XQQH" value="" >
            				</div>
            				<div class="result_l_d">
            					<span>距离开奖：</span><span class="colyellow" id="kaijiang"></span>
            					<input type="hidden" id="JLKJ" value="<?php echo ($lottertime); ?>" >
            					<input type="hidden" id="firsttime" value="">
            					<!-- <?php echo ($lottertime); ?> -->
            				</div>
            			</div><!-- result_l结束 -->
            			<div class="result_c pull-left">
            				<div class="result_c_u">
            					<span>第</span>
            					<span class="colfaf SQQH"><?php echo ($issue); ?></span>
            					<span>期开奖结果</span>
            				</div>
            				<div class="result_c_d">
            					<span>距离封盘</span>
            					<span class="colyellow" id="fengpan">0</span>
            					<input type="hidden" id="JLFP" value="<?php echo ($fptime); ?>" >
            				</div>
            			</div><!-- result_c结束 -->
            			<div class="result_r pull-left">
                              <!-- 最新一期开奖结果 -->
                              <input type="hidden" id="kjjg" value="<?php echo ($num); ?>" >
                              <input type="hidden" id="kjhm" value="" ><!-- 开奖号码 -->
            				<table class="result_r_tab" id="result_r_tab">
            					<tr>
            						<td>
            							<div class="n_<?php echo ($hm1); ?> n_<?php echo ($hm1); ?>_bg"><?php echo ($hm1); ?></div>
            						</td>
            						<td>
            							<div class="n_<?php echo ($hm2); ?> n_<?php echo ($hm2); ?>_bg"><?php echo ($hm2); ?></div>
            						</td>
            						<td>
            							<div class="n_<?php echo ($hm3); ?> n_<?php echo ($hm3); ?>_bg"><?php echo ($hm3); ?></div>
            						</td>
            						<td>
            							<div class="n_<?php echo ($hm4); ?> n_<?php echo ($hm4); ?>_bg"><?php echo ($hm4); ?></div>
            						</td>
            						<td>
            							<div class="n_<?php echo ($hm5); ?> n_<?php echo ($hm5); ?>_bg"><?php echo ($hm5); ?></div>
            						</td>
            						<td>
            							<div class="n_<?php echo ($hm6); ?> n_<?php echo ($hm6); ?>_bg"><?php echo ($hm6); ?></div>
            						</td>
            						
            						<td>
            							<div class="colyellow plus_bg"></div>
            						</td>
            						<td>
            							<div class="n_<?php echo ($hm7); ?> n_<?php echo ($hm7); ?>_bg"><?php echo ($hm7); ?></div>
            						</td>
            						<td>
            							<div class="cla_big cla_big_bg"><?php echo ($dx); ?></div>
            						</td>
            						<td>
            							<div class="cla_odd_bg cla_odd"><?php echo ($ds); ?></div>
            						</td>
            					</tr>
            					<tr>
            						<td>
            							<div class="colfaf"><?php echo ($sx1); ?></div>
            						</td>
            						<td>
            							<div class="colfaf"><?php echo ($sx2); ?></div>
            						</td>
            						<td>
            							<div class="colfaf"><?php echo ($sx3); ?></div>
            						</td>
            						<td>
            							<div class="colfaf"><?php echo ($sx4); ?></div>
            						</td>
            						<td>
            							<div class="colfaf"><?php echo ($sx5); ?></div>
            						</td>
            						<td>
            							<div class="colfaf"><?php echo ($sx6); ?></div>
            						</td>
            						
            						<td>
            							<div class="colfaf">--</div>
            						</td>
            						<td>
            							<div class="colfaf"><?php echo ($sx7); ?></div>
            						</td>
            						<td colspan="2">
            							<div class="n_jq cla_jq_bg">
										<?php echo ($jqys); ?>
										</div>
            						</td>
            					</tr>
            				</table>
            				<input type="hidden" id="trace_t" value="<?php echo ($num); ?>" >
            			</div><!-- result_r结束 -->
            		</div><!-- result结束 -->
						
					<div class="bet">
						<table class="bet_tab">
							<tr>
								<td class="att_item" name="att_item">
									<div class="att_10 bet_item"></div>
								</td>
								<td class="att_item" name="att_item">
									<div class="att_9 bet_item"></div>
								</td>
								<td class="att_item" name="att_item">
									<div class="att_8 bet_item"></div>
								</td>
								<td class="att_item" name="att_item">
									<div class="att_7 bet_item"></div>
								</td>
								<td class="att_item" name="att_item">
									<div class="att_6 bet_item"></div>
								</td>
								<td class="att_item" name="att_item">
									<div class="att_5 bet_item"></div>
								</td>
								<td class="att_item" name="att_item">
									<div class="att_4 bet_item"></div>
								</td>
								<td class="att_item" name="att_item">
									<div class="att_3 bet_item"></div>
								</td>
								<td class="att_item" name="att_item">
									<div class="att_2 bet_item"></div>
								</td>
								<td class="att_item" name="att_item">
									<div class="att_1 bet_item"></div>
								</td>
								<td class="att_item" name="att_item">
									<div class="att_12 bet_item"></div>
								</td>
								<td class="att_item" name="att_item">
									<div class="att_11 bet_item"></div>
								</td>
							</tr>
							<tr>
								<td class="num_item" name=num_item>
									<div class="n_01 n_01_bg bet_item">01</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_02 n_02_bg bet_item">02</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_03 n_03_bg bet_item">03</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_04 n_04_bg bet_item">04</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_05 n_05_bg bet_item">05</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_06 n_06_bg bet_item">06</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_07 n_07_bg bet_item">07</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_08 n_08_bg bet_item">08</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_09 n_09_bg bet_item">09</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_10 n_10_bg bet_item">10</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_11 n_11_bg bet_item">11</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_12 n_12_bg bet_item">12</div>
								</td>
							</tr>
							<tr>
								<td class="num_item" name=num_item>
									<div class="n_13 n_13_bg bet_item">13</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_14 n_14_bg bet_item">14</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_15 n_15_bg bet_item">15</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_16 n_16_bg bet_item">16</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_17 n_17_bg bet_item">17</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_18 n_18_bg bet_item">18</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_19 n_19_bg bet_item">19</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_20 n_20_bg bet_item">20</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_21 n_21_bg bet_item">21</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_22 n_22_bg bet_item">22</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_23 n_23_bg bet_item">23</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_24 n_24_bg bet_item">24</div>
								</td>
							</tr>
							<tr>
								<td class="num_item" name=num_item>
									<div class="n_25 n_25_bg bet_item">25</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_26 n_26_bg bet_item">26</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_27 n_27_bg bet_item">27</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_28 n_28_bg bet_item">28</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_29 n_29_bg bet_item">29</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_30 n_30_bg bet_item">30</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_31 n_31_bg bet_item">31</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_32 n_32_bg bet_item">32</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_33 n_33_bg bet_item">33</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_34 n_34_bg bet_item">34</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_35 n_35_bg bet_item">35</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_36 n_36_bg bet_item">36</div>
								</td>
							</tr>
							<tr>
								<td class="num_item" name=num_item>
									<div class="n_37 n_37_bg bet_item">37</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_38 n_38_bg bet_item">38</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_39 n_39_bg bet_item">39</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_40 n_40_bg bet_item">40</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_41 n_41_bg bet_item">41</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_42 n_42_bg bet_item">42</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_43 n_43_bg bet_item">43</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_44 n_44_bg bet_item">44</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_45 n_45_bg bet_item">45</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_46 n_46_bg bet_item">46</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_47 n_47_bg bet_item">47</div>
								</td>
								<td class="num_item" name=num_item>
									<div class="n_48 n_48_bg bet_item">48</div>
								</td>
							</tr>
							<tr>
								<td class="num_item" name=num_item>
									<div class="n_49 n_49_bg bet_item">49</div>
								</td>
								<td class="col_item" name="col_item">
									<div class="cla_red cla_red_bg bet_item">红</div>
								</td>
								<td class="col_item" name="col_item">
									<div class="cla_blue cla_blu_bg bet_item">蓝</div>
								</td>
								<td class="col_item" name="col_item">
									<div class="cla_green cla_green_bg bet_item">绿</div>
								</td>
								<td class="siz_item" name="siz_item">
									<div class="cla_big cla_big_bg bet_item">大</div>
								</td>
								<td class="siz_item" name="siz_item">
									<div class="cla_small cla_small_bg bet_item">小</div>
								</td>
								<td class="oen_item" name="oen_item">
									<div class="cla_odd cla_odd_bg bet_item">单</div>
								</td>
								<td class="oen_item" name="oen_item">
									<div class="cla_even cla_even_bg bet_item">双</div>
								</td>
								<td colspan="2" class="ani_item" name="ani_item">
									<div class="cla_jq cla_jq_bg bet_item">家 禽</div>
								
								</td>
								<td colspan="2" class="ani_item" name="ani_item">
									<div class="cla_ys cla_ys_bg bet_item">野 兽</div>
								
								</td>
							</tr>
						</table><!-- bet_tab结束 -->
						<input type="hidden" id="bet_num" value="">

						<div class="bet_info">
							<input type="hidden" id="lastname" >
							<ul class="bet_info_ul">
								<li>
									<div class="hint">
										<div class="hint_u">号码赔率</div>
										<div class="hint_d" id="hint_rate"></div>
									</div>
								</li>
								<li>
									<div class="hint">
										<div class="hint_u">下注类型</div>
										<div class="hint_d"
										id="hint_class"></div>
										<input type="hidden" id="LX" >
									</div>
								</li>
								<li>
									<div class="hint">
										<div class="hint_u" >下注金额</div>
										
										<input class="hint_number" type="text" id="Money" >
									</div>
								</li>

								<li onclick="betnumber(5)">
									<img src="/Public/assets/img/bet_1.jpg" alt="下注金额" class="bet_amo bet_amo_1">
								</li>
								<li onclick="betnumber(10)">
									<img src="/Public/assets/img/bet_2.jpg" alt="下注金额" class="bet_amo bet_amo_2">
								</li>
								<li onclick="betnumber(50)">
									<img src="/Public/assets/img/bet_3.jpg" alt="下注金额" class="bet_amo bet_amo_3">
								</li>
								<li onclick="betnumber(100)">
									<img src="/Public/assets/img/bet_4.jpg" alt="下注金额" class="bet_amo bet_amo_4">
								</li>
								<li onclick="betnumber(1000)">
									<img src="/Public/assets/img/bet_5.jpg" alt="下注金额" class="bet_amo bet_amo_5">
								</li>
								<li onclick="betnumber(10000)">
									<img src="/Public/assets/img/bet_6.jpg" alt="下注金额" class="bet_amo bet_amo_6">
								</li>
								<li>
									<input type="button" value="投 注" id="sub_btn" class="sub_btn">
								</li>
								<li>
									<input type="reset" value="重 置" class="sub_btn" id="reset_btn" onclick="reset()">
								</li>
							</ul><!-- bet_info_ul结束 -->
						</div><!-- bet_info结束 -->
					</div><!-- bet结束 -->

            	</div><!-- tab-pane结束 -->
            	
            </div><!-- tab-content结束 -->

			</div><!-- subject_r结束 -->
		</div><!-- subject结束 -->

	</div><!-- center结束 -->
	<div class="modal" id="state" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header text-center">
					<button type="button" class="close close_btn" data-dismiss="modal" aria-hidden="true">
						
					</button>
					<span class="modal-title " id="myModalLabel">
						下注情况
					</span>
				</div>
				<div class="modal_search">
					<table class="state_tab" id="">
						<tr class="state_tit">
							<td style="width: 120px">投注期号</td>
							<td style="width: 170px">投注时间</td>
							<td style="width: 110px">投注类型</td>
							<td style="width: 110px">投注号码</td>
							<td style="width: 130px">投注金额</td>
							<td style="width: 57px">赔率</td>
							<td style="width: 145px">结算</td>
						</tr>
					</table>
				</div>
				<div class="modal-body" style="height: 436px;">
					<table class="state_tab" id="state_tab">
						<!-- <tr class="state_tit">
							<td>投注期号</td>
							<td>投注时间</td>
							<td>投注类型</td>
							<td>投注号码</td>
							<td>投注金额</td>
							<td>赔率</td>
							<td>结算</td>
						</tr> -->
					</table>
				</div>
				<div class="modal-footer">
					<table class="table_state_footer" id="state_footer_tab">
						<tr>
							<td id="state_footer_count">总共：0 条</td>
							<td id="state_footer_money">总投注额：0</td>
						</tr>
					</table>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal -->
	</div><!-- state结束 -->

	<div class="modal" id="record" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header text-center">
					<button type="button" class="close close_btn" data-dismiss="modal" aria-hidden="true">
						
					</button>
					<span class="modal-title " id="myModalLabel">
						投注记录
					</span>
				</div>
				<div class="modal_serach">
					<table class="search_tab" id="record_search_tab">
						<tr>
							<td class="text-right">开始日期：</td>
							<td class="text-left">
								<!-- <input id="record_start_time1" class="search_inp" type="hidden"> -->
								<input id="record_start_time" class="search_inp form-control "  name="stime" type="text" onClick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd'})" value="">
							</td>
							<td class="text-right">结束日期：</td>
							<td class="text-left">
								<!-- <input id="record_end_time" class="search_inp" type="date"> -->
								<input id="record_end_time" class="search_inp form-control "  name="stime" type="text" onClick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd'})" value="">
							</td>
							<td class="text-right">期号查询：</td>
							<td class="text-left">
								<input id="record_issue" type="text" class="search_inp">
							</td>
							<td class="text-left"><input type="button" class="search_btn" id="record_search_btn"></td>
						</tr>
					</table>
					<table class="state_tab" id="">
						<tr class="state_tit">
							<td style="width: 120px">投注期号</td>
							<td style="width: 170px">投注时间</td>
							<td style="width: 110px">投注类型</td>
							<td style="width: 110px">投注号码</td>
							<td style="width: 130px">投注金额</td>
							<td style="width: 57px">赔率</td>
							<td style="width: 145px">结算</td>
						</tr>
					</table>
				</div>
				<div class="modal-body">
					
					<table class="state_tab" id="record_tab">
						<!-- <tr class="state_tit">
							<td>投注期号</td>
							<td>投注时间</td>
							<td>投注类型</td>
							<td>投注号码</td>
							<td>投注金额</td>
							<td>赔率</td>
							<td>结算</td>
						</tr> -->
					</table>
				</div>
				<div class="modal-footer">
					<table class="table_record_footer" id="record_footer_tab">
						<tr>
							<td id="record_footer_count">总共：0 条</td>
							<td>
								<div class="page">
									<a class="page_a" onclick="page_link(1,0)">首页</a>
									<a class="page_a" onclick="page_link(1,1)">上一页</a>
									<span class="page_span">
										<span id="page_span_now_1">1</span>/<span id="page_span_all_1">1</span>
									</span>
									<a class="page_a" onclick="page_link(1,2)">下一页</a>
									<a class="page_a" onclick="page_link(1,3)">尾页</a>
								</div>
							</td>
							<td></td>
						</tr>
					</table>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal -->
	</div><!-- record结束 -->

	<div class="modal" id="bill" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header text-center">
					<button type="button" class="close close_btn" data-dismiss="modal" aria-hidden="true">
						
					</button>
					<span class="modal-title " id="myModalLabel">
						账单报表
					</span>
				</div>
				<div class="modal_search">
					<table class="search_tab" id="bill_search_tab">
						<tr>
							<td class="text-right">开始日期：</td>
							<td class="text-left">
								<!-- <input id="bill_start_time" class="search_inp" type="date"> -->
								<input id="bill_start_time" class="search_inp form-control "  name="stime" type="text" onClick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd'})" value="">
							</td>
							<td class="text-right">结束日期：</td>
							<td class="text-left">
								<!-- <input id="bill_end_time" class="search_inp" type="date"> -->
								<input id="bill_end_time" class="search_inp form-control "  name="stime" type="text" onClick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd'})" value="">
							</td>
							<td class="text-right">显示类型：</td>
							<td class="text-left" style="position: relative;">
								<input type="button" class="search_inp" id="type_select_btn" value="按投注期号">
								<input type="hidden"  id="bill_type" value="">
								<div class="type_select">
									<div class="type_item" name="">按投注期号</div>
									<div class="type_item" name="date">按投注日期</div>
									<div class="type_item" name="type">按投注类型</div>
								</div>
								<!-- <select id="bill_type" class="search_inp">
								  <option value ="">按投注期号</option>
								  <option value="date">按投注日期</option>
								  <option value="type">按投注类型</option>
								</select>	 -->
							</td>
							<td class="text-left"><input id="bill_search_btn" type="button" class="search_btn"></td>
						</tr>
					</table>
					<table class="state_tab" id="bill_tit_tab">
						<tr class="state_tit">
							<td style="width: 210px">日期</td>
							<td style="width: 162px">注数</td>
							<td style="width: 230px">投注额</td>
							<td style="width: 240px">盈亏</td>
						</tr>
					</table>
				</div>
				<div class="modal-body" style="height: 352px;">

					<table class="state_tab" id="bill_tab">
						<!-- <tr class="state_tit">
							<td>日期</td>
							<td>注数</td>
							<td>投注额</td>
							<td>盈亏</td>
							
						</tr> -->
						<tr class="state_tit">
							<td>总计</td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</table>
				</div>
				<div class="modal-footer">
					<table class="table_record_footer" id="bill_footer_tab">
						<tr>
							<td id="bill_footer_count">总共：0 条</td>
							<td>
								<div class="page">
									<a class="page_a" onclick="page_link(2,0)">首页</a>
									<a class="page_a" onclick="page_link(2,1)">上一页</a>
									<span class="page_span">
										<span id="page_span_now_2">1</span>/<span id="page_span_all_2">1</span>
									</span>
									<a class="page_a" onclick="page_link(2,2)">下一页</a>
									<a class="page_a" onclick="page_link(2,3)">尾页</a>
								</div>
							</td>
							<td></td>
						</tr>
					</table>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal -->
	</div><!-- bill结束 -->

	<div class="modal" id="historical" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header text-center">
					<button type="button" class="close close_btn" data-dismiss="modal" aria-hidden="true">
						
					</button>
					<span class="modal-title " id="myModalLabel">
						历史开奖
					</span>
				</div>
				<div class="modal_search">
					<table class="search_tab" id="historical_search_tab">
						<tr>
							<td class="text-right">开始日期：</td>
							<td class="text-left">
								<!-- <input id="historical_start_time" class="search_inp" type="date"> -->
								<input id="historical_start_time" class="search_inp form-control "  name="stime" type="text" onClick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd'})" value="">
							</td>
							<td class="text-right">结束日期：</td>
							<td class="text-left">
							<!-- 	<input id="historical_end_time" class="search_inp" type="date"> -->
								<input id="historical_end_time" class="search_inp form-control "  name="stime" type="text" onClick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd'})" value="">
							</td>
							<td class="text-right">期号查询：</td>
							<td class="text-left">
								<input id="historical_issue" type="text" class="search_inp">
							</td>
							<td class="text-left"><input id="historical_search_btn" type="button" class="search_btn"></td>
						</tr>
					</table>
					<table class="state_tab" id="" width="842px">
						<tr class="state_tit">
							<td style="width: 133px">开奖期号</td>
							<td style="width: 206px">开奖时间</td>
							<td style="width: 250px">开奖号码</td>
							<td style="width: 42px">特码</td>
							<td style="width: 42px">特肖</td>
							<td style="width: 42px">大小</td>
							<td style="width: 42px">单双</td>
							<td style="width: 85px">家禽野兽</td>
						</tr>
					</table>
				</div>
				<div class="modal-body">
					
					<table class="state_tab" id="historical_tab">
						<!-- <tr class="state_tit">
							<td>开奖期号</td>
							<td>开奖时间</td>
							<td>开奖号码</td>
							<td>特码</td>
							<td>特肖</td>
							<td>大小</td>
							<td>单双</td>
							<td>家禽野兽</td>
						</tr> -->
					</table>
				</div>
				<div class="modal-footer">
					<table class="table_record_footer" id="historical_footer_tab">
						<tr>
							<td id="historical_footer_count">总共：0 条</td>
							<td>
								<div class="page">
									<a class="page_a" onclick="page_link(3,0)">首页</a>
									<a class="page_a" onclick="page_link(3,1)">上一页</a>
									<span class="page_span">
										<span id="page_span_now_3">1</span>/<span id="page_span_all_3">1</span>
									</span>
									<a class="page_a" onclick="page_link(3,2)">下一页</a>
									<a class="page_a" onclick="page_link(3,3)">尾页</a>
								</div>
							</td>
							<td></td>
						</tr>
					</table>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal -->
	</div><!-- historical结束 -->

	<div class="modal" id="rule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header text-center">
					<button type="button" class="close close_btn" data-dismiss="modal" aria-hidden="true">
						
					</button>
					<span class="modal-title " id="myModalLabel">
						规则说明
					</span>
				</div>
				<div class="modal-body" style="height: 490px;">
					<div class="rule_tit">
						开奖说明：
					</div>
					<div class="rule_text">
						<p>六合时时彩是从1至49个号码中选出七个为中奖号码的奖券，由香港赛马会的附属公司[香港马会奖券有限公司]经办。</p>
						<p>
							六和时时彩是以六合彩为基础的高频彩种。每天10:00-03:00开奖，每十分钟一期。官网开奖结果<a href="#" class="off_web" id="web_link">http://kj.kafeifu.com/</a>
							<input type="text" id='spreadUrl' value="">
						</p>
					</div>
					<div class="rule_tit">
						投注说明：
					</div>
					<div class="rule_text">
						<p>
							一旦投注被接受，则不得取消或修改，所有投注都必须在封盘时间内进行否则投注无效，所有投注派彩彩金皆含本金。
						</p>
						<p>
							每次登录时客户都应该核对自己的账户余额，如对余额有任何疑问，请在第一时间通知代理。
						</p>
					</div>

					<div class="rule_tit">
						规则介绍：
					</div>
					<div class="rule_text">
						<p>
							1.特码:当期开出的最后一个开奖号码为特码
						</p>
						<p>
							2.特码大小:特码小于或等于24(01~24)为小，特码小于或等于48(25~48)为大，特码为49时为和(开和返还下注，无输赢)
						</p>
						<p>
							3.特码单双:特码为双数，如18、20‘34、42，特码为单数，如01、11、35、47，特码为49时为和(开和返还下注，无输赢)
						</p>
						<p>
							4.特码波色:六合时时彩49个号码球分别有红、蓝、绿三种波色，以特码开出的波色和投注的波色相同即为中奖
						</p>
						<p>
							5.平码生肖:七位开奖号码(包含特码)所对应生肖为平码生肖，投注的平码生肖开在七位开奖号码中视为中奖(如投注平码:鸡 开奖号码开出01,13,25,37,49即为中奖)
						</p>
					</div>
				</div>
				<div class="modal-footer">
					
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal -->
	</div><!-- rule结束 -->
<script src="/Public/assets/js/bet_ajax2.js"></script>
<script src="/Public/assets/js/index3.js"></script>
<!-- <script src="/Public/assets/js/bet_ajax.js"></script>
<script src="/Public/assets/js/index.js"></script> -->
<script language="javascript" type="text/javascript" src="/Public/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
   $youziku.load("body", "3e246cd13db24c3bbd603cd5afd759f6", "Microsoft_YaHei");
   /*$youziku.load("#id1,.class1,h1", "3e246cd13db24c3bbd603cd5afd759f6", "Microsoft_YaHei");*/
   /*．．．*/
   $youziku.draw();
</script>

<script type="text/javascript">
// $(function(){
// 	$("#audioPlay").bind('ended', function() {
// 		var num = ""
// 		var str = $("#audionum").val();
// 		arrs = str.split('-');
// 		console.log(arrs);
// 		strsrc = "../../../../Public/assets/wav/Clip"+arrs[0]+".wav";

// 		num = num + arrs[1] + '-';
//         $("#audionum").val(num);
//         $("#audioPlay").attr('src', strsrc);
//         var audio = document.getElementById( "audioPlay" );
//         audio.play();
//     })
// })

</script>
</body>
</html>