//下注按钮
$("#sub_btn").click(function(){
	var peilv=parseFloat($("#"+$("#LX").val()).val());//获得赔率
	var tze=parseFloat($("#M_"+$("#LX").val()).val());//获得最小下注额
	var nums=$("#bet_num").val().substring(1,this.length).split(',').length;//下注数量
	var xze = parseFloat($("#Money").val());
	var summoney =nums*xze;//下注总金额
	var umoney=parseFloat($("#money_span").text());//用户当前金额
	if(parseInt($("#JLFP").val())==0 ||parseInt($("#JLFP").val())>600){
		popupalert(-1,"已到封盘时间，不能下注");
	}
	else{
		
		if(umoney<summoney){
			popupalert(-1,"当前金额不足，不能下注");
		}
		else{
			
			if($("#bet_num").val()=="" || $("#Money").val()==""){
			popupalert(-1,"请选择下注号码和下注金额");

			}

			else{
				if (xze<tze) {
					popupalert(-1,"最小下注额为"+tze);
				}
				else{
					var datas = {};
			        datas = { "issue": $("#DQQH").val(),
			        "bettype": $("#LX").val(),
			        "betnumber": $("#bet_num").val().substring(1,this.length
			        	),
			        "betmoney": $("#Money").val() ,
			        "peilv":$("#"+$("#LX").val()).val(),
			        "tze":$("#M_"+$("#LX").val()).val()
			    	};
			        $.ajax({
			            type: 'post',
			            url: '/UserBet/bet',
			            data: datas,
			            async: false,
			            success: function (data) {
			            	var d=data.split('|');
			            	popupalert(d[0],d[1]);
			            	if(d[2]) {
			            		$("#money_span").text(d[2]);
			            	}

			            },
			            error: function (data, status) {
			            	popupalert(-1,"操作异常1");
			            	//location.href='/Login/weblogin?state=other'; 
			            }
			        });
		        }
			}
		}
	}
})


		//下注状况开始//
//下注状况
$("#state_link").click(function(){
	getbethistory('','','','','',1);
})
		//下注状况结束//

		//投注记录开始//
//投注记录
var page1=1;
$("#record_link").click(function(){
	page1=1;
	getbethistory(1,'','','','',2);
})
//投注记录查询按钮
$("#record_search_btn").click(function(){
	var ST="";
	var FT="";
	var ISSUE="";
	var rst=$("#record_start_time").val();
	var ret=$("#record_end_time").val();
	var ri=$("#record_issue").val();
	ST=rst;FT=ret;ISSUE=ri;
	if(ST==""&& FT==""&&ISSUE==""){
		popupalert(-1,"请输入查询条件");
		
	}
	else{
		page1=1;
		$("#page_span_now_1").html(page1);
		
		getbethistory(1,ST,FT,ISSUE,page1,2);
	}
	
})
//获取投注记录
function getbethistory(state,starttime,finishtime,issue,page,tabnum){
	var posturl='/Select/bethistory';
	if(page!=''){
		posturl+="?page="+page;
	}
	var jsonstr='{"state":"'+state+'"';
	if(starttime!='')
	{
		jsonstr +=",\"starttime\":\""+starttime+"\",\"finishtime\":\""+finishtime+"\"";
	}
	if(issue!=''){
		jsonstr +=",\"issue\":\""+issue+"\"";
	}
	jsonstr+='}';
	var datas =jQuery.parseJSON(jsonstr);

    $.ajax({
        type: 'post',
        url: posturl,
        data: datas,
        //async: false,
        success: function (data) {
        	if (data.length>0) {
	        	var num =0;
	        	var html="";
	        	for (var i = 0; i < data.length; i++) {
	        		switch(data[i]["iswin"]) {
	        			case "1":
	        				var classname = "state_green";break;
        				case "0":
	        				var classname = "";break;
        				case "-1":
	        				var classname = "state_red";break;
	        		}

	        		if (i%2==0) {
	        			html += "<tr class=\"state_td1\"><td style=\"width:120px\" name=\"state_tds\">"+data[i]["issue"]+"</td>";   
	        		}
	        		else{

	        			html += "<tr class=\"state_td2\"><td style=\"width:120px\" name=\"state_tds\" name=\"state_tds\">"+data[i]["issue"]+"</td>";   
	        		}
	        		html += "<td style=\"width:170px\" name=\"state_tds\">"+data[i]["bettime"]+"</td><td style=\"width:110px\" name=\"state_tds\">"+data[i]["bettype"]+"</td>";
	                html += "<td style=\"width:110px\" name=\"state_tds\">"+data[i]["betnumber"]+"</td><td style=\"width:130px\" name=\"state_tds\">"+data[i]["betmoney"]+"</td>";
	                html += "<td style=\"width:57px\" name=\"state_tds\">"+data[i]["betodds"]+"</td><td  style=\"width:145px\" class='" + classname + " ' name=\"state_tds\">"+data[i]["profitorloss"]+"</td></tr>"; 
	                num+=parseInt(data[i]["betmoney"]);
	            }
	             if(tabnum==1){
	             $("#state_tab").html(html);
	             $("#state_footer_count").html("总共："+data[0]["Count"]+" 条");
	             $("#state_footer_money").html("总投注额："+data[0]["sumbetmoney"]);
				}
	            if(tabnum==2){
	             $("#record_tab").html(html);
	             $("#record_footer_count").html("总共："+data[0]["Count"]+" 条");
	             $("#page_span_all_1").text(data[0]["PageCount"]);

	             $("#record_start_time").val(data[0]["starttime"].substring(0,10));
				 $("#record_end_time").val(data[0]["finishtime"].substring(0,10));
				}
				change_state();
	        }
	        else{
	        	if(tabnum==1){
	        	 	$("#state_tab").html("");
	        	 }
	        	
	        }
	    },
        error: function (data, status) {
            popupalert(-1,"操作异常2");
            //location.href='/Login/weblogin?state=other';
        }
    });
}

		//下注状况结束//

		//账单报表开始//

//账单报表
$("#bill_link").click(function(){
	page1=1;
	getrecordfinance('','','','');
})
//账单报表查询按钮
$("#bill_search_btn").click(function(){
	var ST="";
	var FT="";
	var TYPE="";
	var bst=$("#bill_start_time").val();
	var bet=$("#bill_end_time").val();
	var bt=$("#bill_type").val();

	ST=bst;FT=bet;TYPE=bt;
	// console.log(ST,FT,TYPE);
	if(ST==""&& FT==""&&TYPE==""){
		popupalert(-1,"请输入查询条件");
	}
	else{
		page1=1;
		$("#page_span_now_2").html(page1);
		getrecordfinance(ST,FT,TYPE,page1);
	}
	
})
//获取账单报表
function getrecordfinance(starttime,finishtime,type,page){
	var posturl='/Select/recordfinance';
	if(page!=''){
		posturl+="?page="+page;
	}
	var jsonstr='{';
	if(starttime!='')
	{
		jsonstr +="\"starttime\":\""+starttime+"\",\"finishtime\":\""+finishtime+"\"";
	}
	if(type!=''){
		jsonstr +=",\"type\":\""+type+"\"";
	}
	jsonstr+='}';
	var datas =jQuery.parseJSON(jsonstr);

    $.ajax({
        type: 'post',
        url: posturl,
        data: datas,
        async: false,
        success: function (data) {
        	if (data.length>0) {
	        	var html1="<tr class=\"state_tit\"><td style=\"width:210px;\">日期</td><td style=\"width:162px;\">注数</td><td style=\"width:230px;\">投注额</td><td style=\"width:240px;\">盈亏</td></tr><tr class=\"state_tit\"><td style=\"width:210px;\">总计</td><td style=\"width:162px;\">"+data[data.length-1]["count"]+"</td><td style=\"width:230px;\">"+data[data.length-1]["summoney"]+"</td><td style=\"width:240px;\">"+data[data.length-1]["profitorloss"]+"</td></tr>";
	        	$("#bill_tit_tab").html(html1);
	        	var html2="";
	        	for (var i = 0; i < data.length-1; i++) {
	        		if (parseInt(data[i]["profitorloss"]) > 0) {
	        			var classname = "state_green";
	        		}
	        		if (parseInt(data[i]["profitorloss"]) < 0) {
	        			var classname = "state_red";
	        		}
	        		if (i%2==0) {
	    				html2 += "<tr class=\"state_td1\"><td style=\"width:210px\" name=\"state_tds\">"+data[i]["issue"]+"</td><td style=\"width:162px\" name=\"state_tds\">"+data[i]["count"]+"</td><td style=\"width:230px\" name=\"state_tds\">"+data[i]["summoney"]+"</td><td style=\"width:240px\" class=" + classname + " name=\"state_tds\">"+data[i]["profitorloss"]+"</td></tr>";   
	 				}
	 				else{
	 					html2 += "<tr class=\"state_td2\"><td style=\"width:210px\" name=\"state_tds\">"+data[i]["issue"]+"</td><td style=\"width:162px\" name=\"state_tds\">"+data[i]["count"]+"</td><td style=\"width:230px\" name=\"state_tds\">"+data[i]["summoney"]+"</td><td style=\"width:240px\" class=" + classname + " name=\"state_tds\">"+data[i]["profitorloss"]+"</td></tr>";  
	 				}
	        	}
	        	$("#bill_tab").html(html2);
	             $("#bill_footer_count").html("总共："+data[0]["Count"]+" 条");
	             $("#page_span_all_2").html(data[0]["PageCount"]);
	             $("#bill_start_time").val(data[0]["starttime"].substring(0,10));
				 $("#bill_end_time").val(data[0]["finishtime"].substring(0,10));
	        }
	        change_state();
	    },
        error: function (data, status) {
            popupalert(-1,"操作异常");
            //location.href='/Login/weblogin?state=other';
        }
    });
}

		//账单报表结束//

		//历史开奖开始//

//历史开奖
var page1=1;
$("#historical_link").click(function(){
	page1=1;
	getrecordlottery('','','','');
})
//历史开奖查询按钮
$("#historical_search_btn").click(function(){
	var ST="";
	var FT="";
	var ISSUE="";
	var hst=$("#historical_start_time").val();
	var het=$("#historical_end_time").val();
	var hi=$("#historical_issue").val();
	ST=hst;FT=het;ISSUE=hi;
	if(ST==""&& FT==""&&ISSUE==""){
		popupalert(-1,"请输入查询条件");
	}
	else{
		page1=1;
		$("#page_span_now_3").html(page1);
		getrecordlottery(ST,FT,ISSUE,page1);
	}
	
})

//获取历史开奖
function getrecordlottery(starttime,finishtime,issue,page){
	var posturl='/Select/recordlottery';
	if(page!=''){
		posturl+="?page="+page;
	}
	var jsonstr='{';
	if(starttime!='')
	{
		jsonstr +="\"starttime\":\""+starttime+"\",\"finishtime\":\""+finishtime+"\"";
	}
	if(issue!=''){
		jsonstr +=",\"issue\":\""+issue+"\"";
	}
	jsonstr+='}';
	var datas =jQuery.parseJSON(jsonstr);

    $.ajax({
        type: 'post',
        url: posturl,
        data: datas,
        async: false,
        success: function (data) {
        	if (data.length>0) {
        		console.log(data);
	        	var html="";
	        	for (var i = 0; i < data.length; i++) {
	        		if (i%2==0) {
	        			html += "<tr class=\"state_td1\"><td style=\"width:133px\" name=\"state_tds\">"+data[i]["issue"]+"</td><td style=\"width:206px\" name=\"state_tds\">"+data[i]["time"]+"</td><td style=\"width:250px\" name=\"state_tds\"><p>"+data[i]["num"].split(':')[1]+"</p><p>"+data[i]["num"].split(':')[0]+"</p></td><td style=\"width:42px\" name=\"state_tds\"><span class=\"n_"+data[i]["tnumurl"]+"_bd\">"+data[i]["tnumurl"]+"</span></td><td style=\"width:42px\" name=\"state_tds\"><span class=\"att_01_bd\">"+data[i]["tanimalurl"]+"</span></td><td style=\"width:42px\" name=\"state_tds\"><span class=\"cla_big_bd\">"+data[i]["tbigurl"]+"</span></td><td style=\"width:42px\" name=\"state_tds\"><span class=\"cla_odd_bd\">"+data[i]["tdanshuangurl"]+"</span></td><td style=\"width:85px\" name=\"state_tds\"><span class=\"cla_jq_bd\">"+data[i]["tanimaltypeurl"]+"</span></td></tr>";   
	        		}
	        		else{
	        			html += "<tr class=\"state_td2\"><td style=\"width:133px\" name=\"state_tds\">"+data[i]["issue"]+"</td><td style=\"width:206px\" name=\"state_tds\">"+data[i]["time"]+"</td><td style=\"width:250px\" name=\"state_tds\"><p>"+data[i]["num"].split(':')[1]+"</p><p>"+data[i]["num"].split(':')[0]+"</p></td><td style=\"width:42px\" name=\"state_tds\"><span class=\"n_"+data[i]["tnumurl"]+"_bd\">"+data[i]["tnumurl"]+"</span></td><td style=\"width:42px\" name=\"state_tds\"><span class=\"att_01_bd\">"+data[i]["tanimalurl"]+"</span></td><td style=\"width:42px\" name=\"state_tds\"><span class=\"cla_big_bd\">"+data[i]["tbigurl"]+"</span></td><td style=\"width:42px\" name=\"state_tds\"><span class=\"cla_odd_bd\">"+data[i]["tdanshuangurl"]+"</span></td><td style=\"width:85px\" name=\"state_tds\"><span class=\"cla_jq_bd\">"+data[i]["tanimaltypeurl"]+"</span></td></tr>";  
	        		}
	        	}
	        	 $("#historical_tab").html(html);
	             $("#historical_footer_count").html("总共："+data[0]["Count"]+" 条");
	             $("#page_span_all_3").text(data[0]["PageCount"]);
	             $("#historical_start_time").val(data[0]["starttime"].substring(0,10));
				 $("#historical_end_time").val(data[0]["finishtime"].substring(0,10));
	        }
	        change_state();
	    },
        error: function (data, status) {
            popupalert(-1,"操作异常3");
            //location.href='/Login/weblogin?state=other';
        }
    });
}

		//历史开奖结束//

//获取数据库时间
function getTime(){
	var datas = {};
	var time=null;
    $.ajax({
        type: 'post',
        url: '/Select/getservertime',
        data: datas,
        async: false,
        success: function (data) {
		    data1 = data["server_time"].replace(/-/g, "/"); //火狐内核转换时间戳格式
        	time=new Date(Date.parse(data1));
        	if(data["user_money"]) {
        		$("#money_span").text(data["user_money"]);
        	}
        	console.log(time);
        	if (data["issue"]) {
        		setnewissue(data["issue"]);
        		// console.log(data["issue"]);
        	}
        },
        error: function (data, status) {
            popupalert(-1,"操作异常4");
            //location.href='/Login/weblogin?state=other';  
        }
    });
    return time;
}

function setnewissue(issue){
	$(".DQQH").text(issue);
	$("#DQQH").val(issue);
}

var idata = "";
//获取开奖历史记录
function update(){
	var datas = {};
    $.ajax({
        type: 'post',
        url: '/Select/getlasthistory',
        data: datas,
        async: false,
        success: function (data) {
        	idata = data;
        	//console.log(idata);
        	input_num(idata);//导入号码
        },
        error: function (data, status) {
            popupalert(-1,"操作异常5");
            //location.href='/Login/weblogin?state=other'; 
        }
    });
}

function input_num(idata){
	var number='';
	for(var i=1;i<=7;i++){
		number+=idata[0]["hm"+i]+",";
	}
	$("#kjhm").val(number);
}

// var temp = 1;
function setResult(hour,Minute,second){
	if(idata){
		if((Minute%10)==0){
			if(second >= 0 && second <= 5){
				if(hour == 0 && Minute == 0){
					var lastissue=parseInt(idata[0]["issue"]);
					$(".SQQH").text(lastissue);
				}
				else{
					var lastissue = parseInt(idata[0]["issue"]);
					$(".SQQH").text(lastissue);
					lastissue = lastissue+1;
					$(".DQQH").text(lastissue);
					$("#DQQH").val(lastissue);
				}
			}
			if (second==12) {
				$("#result_r_tab").find("tr").eq(0).find("td").eq(0).html("<div class=\"n_"+idata[0]["hm1"]+ " n_"+idata[0]["hm1"]+ "_bg\">"+idata[0]["hm1"]+ "</div>");
				$("#result_r_tab").find("tr").eq(1).find("td").eq(0).html("<div class=\"colfaf\">"+idata[0]["sx1"]+ "</div>");
			}
			if (second==19) {
				$("#result_r_tab").find("tr").eq(0).find("td").eq(1).html("<div class=\"n_"+idata[0]["hm2"]+ " n_"+idata[0]["hm2"]+ "_bg\">"+idata[0]["hm2"]+ "</div>");
				$("#result_r_tab").find("tr").eq(1).find("td").eq(1).html("<div class=\"colfaf\">"+idata[0]["sx2"]+ "</div>");
			}
			if (second==26) {
				$("#result_r_tab").find("tr").eq(0).find("td").eq(2).html("<div class=\"n_"+idata[0]["hm3"]+ " n_"+idata[0]["hm3"]+ "_bg\">"+idata[0]["hm3"]+ "</div>");
				$("#result_r_tab").find("tr").eq(1).find("td").eq(2).html("<div class=\"colfaf\">"+idata[0]["sx3"]+ "</div>");
			}
			if (second==33) {
				$("#result_r_tab").find("tr").eq(0).find("td").eq(3).html("<div class=\"n_"+idata[0]["hm4"]+ " n_"+idata[0]["hm4"]+ "_bg\">"+idata[0]["hm4"]+ "</div>");
				$("#result_r_tab").find("tr").eq(1).find("td").eq(3).html("<div class=\"colfaf\">"+idata[0]["sx4"]+ "</div>");
			}
			if (second==40) {
				$("#result_r_tab").find("tr").eq(0).find("td").eq(4).html("<div class=\"n_"+idata[0]["hm5"]+ " n_"+idata[0]["hm5"]+ "_bg\">"+idata[0]["hm5"]+ "</div>");
				$("#result_r_tab").find("tr").eq(1).find("td").eq(4).html("<div class=\"colfaf\">"+idata[0]["sx5"]+ "</div>");
			}
			if (second==47) {
				$("#result_r_tab").find("tr").eq(0).find("td").eq(5).html("<div class=\"n_"+idata[0]["hm6"]+ " n_"+idata[0]["hm6"]+ "_bg\">"+idata[0]["hm6"]+ "</div>");
				$("#result_r_tab").find("tr").eq(1).find("td").eq(5).html("<div class=\"colfaf\">"+idata[0]["sx6"]+ "</div>");
			}
		}
		if ((Minute%10)==1){
			if (second==3) {
				$("#result_r_tab").find("tr").eq(0).find("td").eq(7).html("<div class=\"n_"+idata[0]["hm7"]+ " n_"+idata[0]["hm7"]+ "_bg\">"+idata[0]["hm7"]+ "</div>");
				$("#result_r_tab").find("tr").eq(1).find("td").eq(7).html("<div class=\"colfaf\">"+idata[0]["sx7"]+ "</div>");
				$("#result_r_tab").find("tr").eq(0).find("td").eq(8).html("<div class=\"cla_small cla_small_bg\">"+idata[0]["dx"]+ "</div>");
				
				$("#result_r_tab").find("tr").eq(0).find("td").eq(9).html("<div class=\"cla_odd cla_odd_bg\">"+idata[0]["ds"]+ "</div>");
				$("#result_r_tab").find("tr").eq(1).find("td").eq(8).html("<div class=\"n_jq cla_jq_bg\">"+idata[0]["jqys"].substring(0,1)+" "+idata[0]["jqys"].substring(1,2)+"</div>");
			}
			if (second==6) {
				history_tab(idata);
			}
		}
	}
}

//更新开奖历史记录
function history_tab(data){
	if (data.length>0) {
		// console.log(data);
		var html = "";
	    for (var i = 0; i < data.length; i++) {
	        html += "<table class=\"history_tab\" cellspacing=\"cellspacing\"><tr>";
	        html += "<td class=\"td_action\"><div class=\"colfaf\">"+data[i]["issue1"]+ "</div></td>";
	        html += "<td><div class=\"n_"+data[i]["hm1"]+ "\">"+data[i]["hm1"]+ "</div></td>";
	        html += "<td><div class=\"n_"+data[i]["hm2"]+ "\">"+data[i]["hm2"]+ "</div></td>";
	        html += "<td><div class=\"n_"+data[i]["hm3"]+ "\">"+data[i]["hm3"]+ "</div></td>";
	        html += "<td><div class=\"n_"+data[i]["hm4"]+ "\">"+data[i]["hm4"]+ "</div></td>";
	        html += "<td><div class=\"n_"+data[i]["hm5"]+ "\">"+data[i]["hm5"]+ "</div></td>";
	        html += "<td><div class=\"n_"+data[i]["hm6"]+ "\">"+data[i]["hm6"]+ "</div></td>";
	        html += "<td><div class=\"colyellow plus_bg\"></div></td>";
	        html += "<td><div class=\"n_"+data[i]["hm7"]+ "\">"+data[i]["hm7"]+ "</div></td>";
	        html += "<td><div class=\"cla_small\">"+data[i]["dx"]+ "</div></td>";
	        html += "<td><div class=\"cla_odd\">"+data[i]["ds"]+ "</div></td>";
	        html += "</tr><tr>";
	        html += "<td class=\"td_action\"><div class=\"colyellow\">"+data[i]["issue2"]+ "</div></td>";
	        html += "<td><div class=\"col999\">"+data[i]["sx1"]+ "</div></td>";
	        html += "<td><div class=\"col999\">"+data[i]["sx2"]+ "</div></td>";
	        html += "<td><div class=\"col999\">"+data[i]["sx3"]+ "</div></td>";
	        html += "<td><div class=\"col999\">"+data[i]["sx4"]+ "</div></td>";
	        html += "<td><div class=\"col999\">"+data[i]["sx5"]+ "</div></td>";
	        html += "<td><div class=\"col999\">"+data[i]["sx6"]+ "</div></td>";
	        html += "<td><div class=\"col999\">--</div></td>";
	        html += "<td><div class=\"col999\">"+data[i]["sx7"]+ "</div></td>";
	        html += "<td colspan=\"2\"><div class=\"n_jq\">"+data[i]["jqys"].substring(0,1)+" "+data[i]["jqys"].substring(1,2)+"</div></td>";
	        html += "</tr></table>";
	    }
	    $("#dataview2").html(html);
	}
}
//分页按钮
function page_link(tab,num){

	switch(tab){
		case 1:
		var ST="";
		var FT="";
		var ISSUE="";
		var rst=$("#record_start_time").val();
		var ret=$("#record_end_time").val();
		var ri=$("#record_issue").val();
		ST=rst;FT=ret;ISSUE=ri;
		if (ST!=""&&FT!="") {
			switch(num){
			case 0:page1=1;$("#page_span_now_1").text(page1);getbethistory(1,ST,FT,ISSUE,page1,2);break;
			case 1:if (page1-1>0) {page1--;$("#page_span_now_1").text(page1);getbethistory(1,ST,FT,ISSUE,page1,2)};break;
			case 2:if (page1+1<=parseInt($("#page_span_all_1").text())) {page1++;$("#page_span_now_1").text(page1);getbethistory(1,ST,FT,ISSUE,page1,2)};break;
			case 3:page1=parseInt($("#page_span_all_1").text());$("#page_span_now_1").text(page1);getbethistory(1,ST,FT,ISSUE,page1,2);break;
			}
		}
		break;
		case 2:
		var ST="";
		var FT="";
		var TYPE="";
		var bst=$("#bill_start_time").val();
		var bet=$("#bill_end_time").val();
		var bt=$("#bill_type").val();
		ST=bst;FT=bet;TYPE=bt;
		if (ST!=""&&FT!="") {
			switch(num){
			case 0:page1=1;$("#page_span_now_2").text(page1);getrecordfinance(ST,FT,TYPE,page1);break;
			case 1:if (page1-1>0) {page1--;$("#page_span_now_2").text(page1);getrecordfinance(ST,FT,TYPE,page1)};break;
			case 2:if (page1+1<=parseInt($("#page_span_all_2").text())) {
				page1++;
				$("#page_span_now_2").text(page1);
				getrecordfinance(ST,FT,TYPE,page1)};
				break;
			case 3:page1=parseInt($("#page_span_all_2").text());$("#page_span_now_2").text(page1);getrecordfinance(ST,FT,TYPE,page1);break;
			}
		}
		break;
		case 3:
		var ST="";
		var FT="";
		var ISSUE="";
		var hst=$("#historical_start_time").val();
		var het=$("#historical_end_time").val();
		var hi=$("#historical_issue").val();
		ST=hst;FT=het;ISSUE=hi;
		if (ST!=""&&FT!="") {
			switch(num){
			case 0:page1=1;$("#page_span_now_3").text(page1);getrecordlottery(ST,FT,ISSUE,page1);break;
			case 1:if (page1-1>0) {page1--;$("#page_span_now_3").text(page1);getrecordlottery(ST,FT,ISSUE,page1)};break;
			case 2:if (page1+1<=parseInt($("#page_span_all_3").text())) {page1++;$("#page_span_now_3").text(page1);getrecordlottery(ST,FT,ISSUE,page1)};break;
			case 3:page1=parseInt($("#page_span_all_3").text());$("#page_span_now_3").text(page1);getrecordlottery(ST,FT,ISSUE,page1);break;
			}
		}
		break;
	}
	
	
}