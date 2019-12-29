
$(function(){
	//日期选择
	laydate.render({
	  elem: '#out_date',
	  trigger: 'click',
	  theme: '#7C71BF'	  
	});
//  添加盘点单窗口展示
	    $.ajax({
	        "url": "http://www.s2.com/index/JxcOrder/addpd",
	        "type": "post",
	        "dataType": "json",
	        "data": {pageone:4},
	        success: function (e) {
	            $("#out_date").val(e.date);
	            $("#user_name").text(e.user_name);
	        },
	        error:function(e){
//	            console.log(e)
	        }
	    })
})

    function cancle() {
        $('#good_listdiv').css('display','none');
    }
    function showgoodlist() {
        $('#good_listdiv').css('display','');
    }
    function getData(info){
        var str = '';
        $.each(info,function(idx,obj){
            var good_id = obj.good_id;
            var good_num = obj.good_num;
            var good_name= obj.good_name;
            var mini_imgpath= obj.mini_imgpath;
            var imgpath= obj.imgpath;
            var spec= obj.spec;
            var unit= obj.unit;
            var purchase_price= obj.purchase_price;
            var stock = obj.stock;
            var h = false; //商品是否存在标志,默认不存在
            //获取当前已添加的商品ID 如果已经添加不允许再次添加
            $('.good_id').each(function(){
                var gid = $(this).val();
                if(gid == good_id){
                    h = true;   //存在商品
                    return false;   //中止当前判断循环
                }
            });
            //通过标志判断是否存在商品
            if (h){ //存在商品 跳出本次循环
                return;
            }
            str +="<tr onmouseover='trx(this)' onmouseout='tre(this)'>" +
                "<td>" +
                "<input type='hidden' name='goods_id[]' class='good_id' value='"+good_id+"'><span>"+good_num+"</span>" +
                "</td>" +
                "<td><span>"+good_name+"</span></td>" +
                "<td>" +
                "<div style='position: relative;'>" +
                "<img  height='26px' class='mini_imgpath' src='"+mini_imgpath+"' alt='商品图片' onmouseover='showimg(this)' onmouseout='closeimg(this)'>" +
                "<div style='left:50px;top:-80px;z-index: 2;display:none;position:absolute;border: 1px solid #AAAAAA;border-radius:3px; '><img style='width:120px;height:120px;border-radius:3px;'class='imgpath' src='"+imgpath+"' alt='商品图片'></div>" +
                "</div>" +
                "</td>" +
                "<td><span>"+spec+"</span></td>" +
                "<td><span>"+unit+"</span></td>" +
                "<td width='8%'><input type='number' onchange='getsum1(this)' style='width:100%;border-radius:10px;border: 1px solid #ccc;' value='"+purchase_price+"' name='price[]'></td>" +
                "<td width='8%'><span>"+stock+"</span><input type='hidden' name='pd_stock[]' value='"+stock+"'></td>"+
                "<td width='8%'><input type='number' onchange='getsum2(this)' style='width:100%;border-radius:10px;border: 1px solid #ccc; ' class='number' value='"+stock+"' name='number[]'></td>" +
                "<td width='8%'><span class='sunyi'>"+(stock-stock)+"</span></td>" +
                "<td><input type='text' name='good_notes[]' class='form-control' style='border-radius:10px;border: 1px solid #ccc;'></td>" +
                "<td><span class='bt' href='javascript:void(0)' onclick='removetr(this)'>删除</span></td>" +
                "</tr>";
        })
        $('#goodstable').find('tbody').append(str);
    }
    function closeself(){
        parent.cancleadd();
    }

    function showimg(obj){
        $(obj).next().css('display','');
    }
    function closeimg(obj){
        $(obj).next().css('display','none');
    }



    //提交时检查字段信息//确认添加盘点单
    function checkinfo(){

        var out_date = $('#out_date').val();
        var goods = $('#goodstable tbody tr');
        var out_prices = $('.price');
        var numbers = $('.number');
        var flag = true;
        if (out_date.length == 0) {
            alert('请输入盘点日期');
            flag = false;
            return false;

        }
        if (!flag) {
            return false;
        }


        if (goods.length == 0){
            alert('请添加商品');
            flag = false;
            return false;
        }
        if (!flag) {
            return false;
        }
        out_prices.each(function (k,v) {
            var price = $(v).val();
            if (price.length == 0) {
                alert("请输入价格");
                flag = false;
                return false;
            }
        })
        if (!flag) {
            return false;
        }
        numbers.each(function (k,v) {
            var num = $(v).val();
            if (num.length == 0 || num < 0) {
                alert("请输入实盘数量");
                flag = false;
                return false;
            }
        })
        if (!flag) {
            return false;
        }
// 执行添加盘点单数据提交*****************************
	    var result=confirm("确认要添加吗？");
	    if(result){	
	        var goods_id = [];
	        var price = [];
	        var number = [];
	        var good_notes = [];
	        var pd_stock = [];
			var trList = $("#goodstable tbody").children("tr")
			  for (var i=0;i<trList.length;i++) {
			    var td = trList.eq(i).find("td");		    
			    var td_goods_id = td.eq(0).find("input").val();//商品ID
			    var td_price = td.eq(5).find("input").val();//盘点价格
			    var td_number = td.eq(7).find("input").val();//盘点数量
			    var td_good_notes = td.eq(9).find("input").val();//商品备注
			    var td_pd_stock = td.eq(6).find("input").val();//盘点存数
				goods_id[i] = td_goods_id;
				price[i] = td_price;
				number[i] = td_number;
				good_notes[i] = td_good_notes;
				pd_stock[i] = td_pd_stock;
			  }
		  	var add_date = $("#out_date").val();
		    var add_notes= $("#notes").val();
			arr = {'date':add_date,'notes':add_notes,
				   'goods_id':goods_id,'price':price,'number':number,'good_notes':good_notes,'pd_stock':pd_stock};
	
			$.ajax({
		        "url": "http://www.s2.com/index/JxcOrder/storepd",
		        "type": "post",
		        "dataType": "json",
		        "data": {data:arr},
		        success: function (e) {
		            if(e.status==2){
		            	alert("添加成功")
		            	parent.getpd();
		            	parent.fenye();
			            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
			            parent.layer.close(index);			            	
		            }else if(e.status==1){
		            	alert(e.msg);
		            }
		        },
		        error:function(e){
				// console.log(e)
		        }
		    })
		}
    }

    //计算总价
    function getsum1(obj) {
        var price = parseFloat($(obj).val());
        var regx = /^(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*))$/;
        if(!regx.test(price)){
            alert("请输出正确的价格");
            console.log(stock);
        }
    }
    function getsum2(obj) {
        var num = parseFloat($(obj).val());
        var regx = /^\d+$/;
        if(!regx.test(num) || num < 0){
            alert("请输出正确的实盘数量");
            $(obj).val("1");
            num = 1;
        }
        var stock = $(obj).parent().prev().children("span").html();
        var sunyi = num - stock;
        $(obj).parent().next().children("span").html(sunyi);
    }

    //删除当前商品行
    function removetr(obj){
        $(obj).parents("tr").remove();
    }

 //tr鼠标事件  
function trx(jpo){
	td1=$(jpo).find("td").eq(0);
	td10=$(jpo).find("td").eq(10);
	$(jpo).css('box-shadow','5px 5px 4px #E0E0E0')
	$(jpo).css('background','#F0F4F5')
	$(td1).css('border-radius','10px 0 0 10px') 	
	$(td10).css('border-radius','0 10px 10px 0') 	
	 	
}
function tre(jpo){
	td1=$(jpo).find("td").eq(0);
	td10=$(jpo).find("td").eq(10);
	$(jpo).css('box-shadow','');
	$(jpo).css('background','');
	$(td1).css('border-radius',''); 
	$(td10).css('border-radius',''); 

}