//
   
//模态框列表单行点击修改样式
function change_state() {
	//模态框列表单行点击修改样式
	$(".state_td1>td,.state_td2>td").click(function(){
		var index= $(this).index();//获取当前点击tr的位子
		//var ps= $(this).parent().siblings(".state_tit");
		// ps.each(function(){
		// 	$(this).children().eq(index).addClass("state_td_active").siblings().removeClass("state_td_active");
		// })//表单标题指定列文字加粗
		$(this).parent().toggleClass("state_tr_active").siblings().removeClass("state_tr_active");
		//当前行改变样式
	})
}


//禁止鼠标左右键菜单
function on() {
	//document.onselectstart = new Function ("return false") 
	//直接上干货：
	document.onselectstart = function(event){
	
		if(event.target.nodeName == "TD" && $(event.target).attr('name') == "state_tds") {
			return true;
		} else {
			return false;
		}
	}; 
	//劫持开始选择事件和（或）鼠标按下、抬起事件。
	document.oncontextmenu = function(event){

		return false;
	}; 
}
//on();

//
$("#web_link").click(function(){
	document.title="太阳城娱乐城"+Date.parse(new Date());
 	// var Url2 = document.getElementById("spreadUrl");
	// Url2.select(); // 选择对象
	// document.execCommand("Copy"); // 执行浏览器复制命令
})

//选择下拉框
$("#type_select_btn").click(function(){
	if ($(".type_select").is(":hidden")) {
		$(".type_select").show();
	}
	else{
		$(".type_select").hide();
	}
	
})
$(".type_item").each(function(){
	$(this).click(function(){
		var vals = $(this).attr('name');
		var names =$(this).text();
		$("#bill_type").val(vals);
		$("#type_select_btn").val(names);
		$(this).parent().hide();
	})
})




//公告轮播
var p=0;//偏移量默认为0
var speed=50; //移动速度
var parent = $("#parent");
var parentW = parent.width();
var child = $("#child");
child.text("六合时时彩正式上线，太阳城娱乐城提供全新客户端，享受更直观的下注体验");
child.css({'padding-left':parentW,'padding-right':parentW});
var childW= child.width();
var childAllW= childW+parentW;
	//轮播循环
function Marquee(){
	if (p>=childAllW) {
		p=0;
		$("#parent").scrollLeft(0);
	}
	if(p<childAllW){
		p++;
		$("#parent").scrollLeft(p);
	}
	window.setTimeout(Marquee,speed); 
}



//倒计时
var KJcirculation=parseInt($("#JLKJ").val());//开奖间隔
var QScirculation=parseInt($("#DQQH").val());//上期期数
var tip = 0;

setInterval(function(){

		var time1 = 3;//每天开盘3点结束
		var time2 = 10;//每天开盘10点开始
		var time3 = 180;//开奖与封盘时间间隔
	    time = getTime();//获取数据库返回的时间
		var h = time.getHours();
		var m = time.getMinutes();
		var s = time.getSeconds();
		var sum,sum2;//开奖、封盘倒计时间
		
		if (h >= 0 && h < time1) {//0~3点倒计时
			
			sum=h*3600+m*60+s;//超出总秒数
			var residue=KJcirculation-(sum%KJcirculation);//距离下一期剩余秒数
			if ((sum%KJcirculation)==0) {
				//开奖倒计时为0时，准备新一期倒计时
				if(h ==0 && s == 0 && m == 0){
					clearResult();//清空上期开奖结果
					update();
					QScirculation=parseInt($("#DQQH").val());
				}
				else{
					// $(".SQQH").text(QScirculation);
					// QScirculation++;//倒计时为0时，当前期数+1
					// $(".DQQH").text(QScirculation);
					// $("#DQQH").val(QScirculation);
					clearResult();//清空上期开奖结果
					update();
				}
				// console.log(QScirculation);
			}
			sum=residue;
			sum2=sum-time3;//当前封盘时间
			
			if (sum2<0) {
				sum2=0;
			}
			
		}
		if (h == time1 ) {//3点整
			if(m==0&&s==0){
				// $(".SQQH").text(QScirculation);
				// QScirculation++;//倒计时为0时，当前期数+1
				// $(".DQQH").text(QScirculation);
				// $("#DQQH").val(QScirculation);
				clearResult();//清空上期开奖结果
				update();
			}
			var all_h=time2-h-1;
			sum = all_h*3600;
			var all_m=60-m-1;
			sum += all_m*60;
			sum += (60-s)*1;//开奖倒计时
			sum2 = sum-time3;//当前封盘时间
			if (sum2<0) {//封盘时间最小为0
				sum2=0;
			}
			
		}
		if (h > time1 && h < time2) {//3~10点倒计时
			//console.log("3~10");
			var all_h=time2-h-1;
			sum = all_h*3600;
			var all_m=60-m-1;
			sum += all_m*60;
			sum += (60-s)*1;//开奖倒计时
			sum2 = sum-time3;//当前封盘时间
			
			if (sum2<0) {//封盘时间最小为0
				sum2=0;
			}
			
		}
		if(h >= time2 && h <= 23) {
			//console.log("10~23");
			//10点后倒计时
			sum=(h-time2)*3600+m*60+s;//超出总秒数
			var periods= parseInt(sum/KJcirculation);//超出当前期数
			var residue=KJcirculation-(sum%KJcirculation);//距离下一期剩余秒数
			if ((sum%KJcirculation)==0) {
				//开奖倒计时为0时，准备新一期倒计时
				// $(".SQQH").text(QScirculation);
				// QScirculation++;//倒计时为0时，当前期数+1
				// $(".DQQH").text(QScirculation);
				// $("#DQQH").val(QScirculation);
				clearResult();//清空上期开奖结果
				update();
			}
			sum=residue;
			sum2=sum-time3;//当前封盘时间
			
			if (sum2<0) {
				sum2=0;
			}
		}

		setResult(h,m,s);
		// console.log(h,m,s);
		$("#JLFP").val(parseInt(sum2));
		$("#kaijiang").text(parseInt(sum));
		$("#fengpan").text(parseInt(sum2));

	},1000)

//清除上一期的开奖结果
function clearResult(){
	var r=$(".result_r_tab tr");
	$(".result_r_tab tr").each(function(index,item){
		if(index==0){
			$(item).children().each(function(index2,item2){
				if(index2<6 || index2==7){
					$(item2).html("<div class='yellow_bg'></div>");
				}
				if (index2==6) {
					$(item2).html("<div class='colyellow plus_bg'></div>");
				}
				if (index2==8) {
					$(item2).html("<div class='cla_big cla_big_bg'></div>");
				}
				if (index2==9) {
					$(item2).html("<div class='cla_odd_bg cla_odd'></div>");
				}
			})
		}
		if(index==1){
			$(item).children().each(function(index2,item2){
				if(index2<8){
					$(item2).html("<div class='col999'>--</div>");
				}
				if (index2==8) {
					$(item2).html("<div class='n_jq cla_jq_bg'></div>");
				}
			})
		}
	})
}


//选择查询类型

//计算下注总金额
var betnum=0;
function betnumber(num){
	betnum+=num;
	var val=$("#Money").val(betnum);
	$("#hint_number").text(betnum);
	
}
//重置
function reset(){
	$("#Money,#hint_rate,#bet_num,#LX").val('');
	$("#hint_number,#hint_class,#hint_rate").text("");
	$(".bet_tab tr>td").each(function(){
	 		$(this).removeClass("td_action");
		})
	betnum=0;
}

function popupalert(num,content){
	var alert_panel =$("<div class='alert_position'></div>").appendTo('body');
	//在弹框面板内加入成功信息
	var success = $("<div class='alert_center'><div class='alert_panel'><div class='alert  alert-dismissable' style='display: inline-block;'><button type='button' class='close closealert' data-dismiss='alert' aria-hidden='true'>&times;</button>提示</div><div class='alert_body'>"+content+"</div><div class='alert_footer'><input class='alert_close_btn closealert' type='button' value='確定' /></div></div></div>").appendTo(alert_panel);
	$(".closealert").click(function(){
		$(this).parents(".alert_position").remove();
		if(num==1){
			reset();//投注成功重置
		}
	})
}


$(function(){
	 var items =[["att_item",$("#PMSX").val(),"平码生肖","PMSX"],
				["num_item",$("#TMHM").val(),"特码号码","TMHM"],
				["col_item",$("#TMBS").val(),"特码波色","TMBS"],
				["siz_item",$("#TMDX").val(),"特码大小","TMDX"],
				["oen_item",$("#TMDS").val(),"特码单双","TMDS"],
				["ani_item",$("#JQYS").val(),"家禽野兽","JQYS"]];
	//选码
	$(".bet_tab tr>td").click(function(){
		var $this=$(this);

		var names=$this.attr('name');//获取该td的name
		if ($("#lastname").val()!=names) {
			//如果name不同，则清空类型，赔率，投注额
			$("#lastname").val(names);//赋值号码类型
			$("#hint_number").text("");//清空投注额
			betnum=0;//重置初始下注额
			$("#Money").val("");//清空隐藏的投注额
			$("#bet_num").val("");//清空下注号码
			$(".bet_tab tr>td").removeClass("td_action");//清除所有选择样式

		}
		var x=$this.children().attr("class");//获取td下div的class
		var arr=x.split(' ');
		var first=arr[0];//获取第一个样式名
		var arr2=first.split('_')[1];//截取样式名都后缀，获得号数
		//判断是否已有选择的号码
		var strnum=$("#bet_num").val();//获取选中的号码，","隔开
		var arrnum=$("#bet_num").val().split(',');
		var count=0;
		var length=arrnum.length;
		for(var i=0;i<length;i++){
			if(arr2==arrnum[i]){
				//删除重复号码
				var index=strnum.indexOf(arr2);
				var str1=strnum.substring(0,index-1);
				var str2=strnum.substring(index+arr2.length,strnum.length);
				$("#bet_num").val(str1+str2);
			}
			else{
				count++;//没有重复就累加
			}
		}
		if(count==length){
			//选码没有重复即添加该选码
			$("#bet_num").val($("#bet_num").val()+","+arr2);
		}
		
		//如果此次选码与上次选码是同类型，则填写类型，赔率，
		items.forEach(function(item){
			if (names==item[0]) {
				$("#hint_rate").text(item[1]);//赋值赔率
				$("#hint_class").text(item[2]);//赋值类型
				$("#LX").val(item[3]);//赋值隐藏类型
			}

		})
		
		 $this.toggleClass("td_action");//添加选中样式
	})
	
	
	Marquee();//公告轮播初始化

	//startTime();//倒计时

})





