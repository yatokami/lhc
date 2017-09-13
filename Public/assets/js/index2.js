function getDte(){//获取记录
	var datas = {};
    $.ajax({
        type: 'post',
        url: '/Select/getlasthistory',
        data: datas,
        async: false,
        success: function (data) {
        	var html ="<tr><td>開獎結果 : </td>"
             html+="<td align=\"center\" style=\"padding:0px 5px 0px 5px;\"><img src=\"../../../../Public/assets/img/no_"+data[0]["hm1"]+".gif\" width=\"37\" height=\"41\"></td>";
             html+="<td align=\"center\" style=\"padding:0px 5px 0px 5px;\"><img src=\"../../../../Public/assets/img/no_"+data[0]["hm2"]+".gif\" width=\"37\" height=\"41\"></td>";
             html+="<td align=\"center\" style=\"padding:0px 5px 0px 5px;\"><img src=\"../../../../Public/assets/img/no_"+data[0]["hm3"]+".gif\" width=\"37\" height=\"41\"></td>";
             html+="<td align=\"center\" style=\"padding:0px 5px 0px 5px;\"><img src=\"../../../../Public/assets/img/no_"+data[0]["hm4"]+".gif\" width=\"37\" height=\"41\"></td>";
             html+="<td align=\"center\" style=\"padding:0px 5px 0px 5px;\"><img src=\"../../../../Public/assets/img/no_"+data[0]["hm5"]+".gif\" width=\"37\" height=\"41\"></td>";
             html+="<td align=\"center\" style=\"padding:0px 5px 0px 5px;\"><img src=\"../../../../Public/assets/img/no_"+data[0]["hm6"]+".gif\" width=\"37\" height=\"41\"></td>";
             html+="<td align=\"center\" style=\"padding:5px 5px 5px 5px;\"><img src=\"../../../../Public/assets/img/icon_special_no.gif\" width=\"6\" height=\"6\"></td>";
             html+="<td align=\"center\" style=\"padding:0px 5px 0px 5px;\"><img src=\"../../../../Public/assets/img/no_"+data[0]["hm7"]+".gif\" width=\"37\" height=\"41\"></td>";
        	 html+="</tr>";
        	 $("#new_result").html(html);
        	 $("#next_issue").text(parseInt(data[0]["issue"])+1);//下期期数
        	 $("#next_time").text(data[0]["nexttime"]);//下期开奖时间
        	 $("#sell_time").text(data[0]["shoupiaotime"]);//售票截止时间
        	 $("#last_issue").text("開獎期數 :"+data[0]["issue"]);//上一期期数
        	 $("#last_time").text("開獎时间 :"+data[0]["lotterytime"]);//上期开奖时间
        	 $("#last_money").text("總投注額：$"+data[0]["sumbetmoney"]);//上期总投注额
           $("#tj").text(data[0]["tj"]);
           $("#ej").text(data[0]["ej"]);
           $("#sj").text(data[0]["sj"]);
        },
        error: function (data, status) {
        	alert("失败！"); 
        }
    });
}
function updateWeb(){
	getDte();
	setTimeout("updateWeb()",10000);
}

//变动生成预估头奖基金
function setmoney(){
var t= new Date().getTime().toString();
t=parseInt(t.substring(1,7));
t+=8000000;
var price=commafy(t);
$("#bet_money").text("$"+price);
}

function commafy(num){
//千位符转换
  num = num+"";
  if(/^.*\..*$/.test(num)){
   varpointIndex =num.lastIndexOf(".");
   varintPart = num.substring(0,pointIndex);
   varpointPart =num.substring(pointIndex+1,num.length);
   intPart = intPart +"";
    var re =/(-?\d+)(\d{3})/
    while(re.test(intPart)){
     intPart =intPart.replace(re,"$1,$2")
    }
   num = intPart+"."+pointPart;
  }else{
   num = num +"";
    var re =/(-?\d+)(\d{3})/
    while(re.test(num)){
     num =num.replace(re,"$1,$2")
    }
  }
  return num;
} 

$(function(){
	updateWeb();//初始化最新开奖记录刷新
    setmoney()//初始化变动生成预估头奖基金
    //期号搜索
    $("#search_btn").click(function(){
        if ($("#search_input").val()!="") {
            var datas = {
                "issue":$("#search_input").val()
            };
            $.ajax({
                type: 'post',
                url: '/Select/getlasthistory',
                data: datas,
                async: false,
                success: function (data) {
                    $("#search_input").val("");
                    if (data.length>0) {
                        var html ="<tr><td></td><td width=\"15%\" class=\"tableContentHead\">開獎期數</td><td width=\"31%\" class=\"tableContentHead\">開獎時間</td><td width=\"20%\" class=\"tableContentHead\">金多寶名稱</td><td width=\"35%\" class=\"tableContentHead\">開獎結果</td></tr><tr><td></td>";
                           html+="<td class=\"tableResult2\"><a title=\"開獎結果及派彩\">"+data[0]["issue"]+"&nbsp;</a></td>"
                           html+="<td class=\"tableResult2\">"+data[0]["lotterytime"]+"</td>"
                           html+="<td class=\"tableResult2\">&nbsp;</td><td align=\"left\" class=\"tableResult2\"><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr>"
                           html+="<td align=\"center\" style=\"padding:0px 3px 0px 3px;\"><img src=\"../../../../Public/assets/img/no_"+data[0]["hm1"]+"_s.gif\" width=\"25\" height=\"25\"></td>"
                           html+="<td align=\"center\" style=\"padding:0px 3px 0px 3px;\"><img src=\"../../../../Public/assets/img/no_"+data[0]["hm2"]+"_s.gif\" width=\"25\" height=\"25\"></td>"
                           html+="<td align=\"center\" style=\"padding:0px 3px 0px 3px;\"><img src=\"../../../../Public/assets/img/no_"+data[0]["hm3"]+"_s.gif\" width=\"25\" height=\"25\"></td>"
                           html+="<td align=\"center\" style=\"padding:0px 3px 0px 3px;\"><img src=\"../../../../Public/assets/img/no_"+data[0]["hm4"]+"_s.gif\" width=\"25\" height=\"25\"></td>"
                           html+="<td align=\"center\" style=\"padding:0px 3px 0px 3px;\"><img src=\"../../../../Public/assets/img/no_"+data[0]["hm5"]+"_s.gif\" width=\"25\" height=\"25\"></td>"
                           html+="<td align=\"center\" style=\"padding:0px 3px 0px 3px;\"><img src=\"../../../../Public/assets/img/no_"+data[0]["hm6"]+"_s.gif\" width=\"25\" height=\"25\"></td>"
                           html+="<td align=\"center\" style=\"padding:5px 5px 5px 5px;\"><img src=\"../../../../Public/assets/img/icon_special_no.gif\" width=\"6\" height=\"6\"></td>"
                           html+="<td align=\"center\" style=\"padding:0px 1px 0px 3px;\"><img src=\"../../../../Public/assets/img/no_"+data[0]["hm7"]+"_s.gif\" width=\"25\" height=\"25\"></td>"
                           html+="</tr></table></td></tr>";
                         $("#result").html(html);
                    }
                },
                error: function (data, status) {
                    alert("失败！"); 
                }
            });
        }
        else{
            window.location.reload()//投注完刷新页面
        }
    })
})
