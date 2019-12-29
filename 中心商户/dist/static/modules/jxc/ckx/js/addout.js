	$(function(){		
	    $.ajax({
	        "url": "http://www.s2.com/index/JxcOrder/addout",
	        "type": "post",
	        "dataType": "json",
	        "data": {'pageone':4},
	        success: function (e) {
//	        	console.log(e);
	        	var str1="";
	        	var str2="";
	        	var str3="";
	        	var str4="";
				$('#out_date').val(e.date);//采购时间
				$('#user_name').text(e.user_name);//采购时间
		    	for(var i=0;i<e.users.length;i++){
		    		str2 += "<option value='"+e.users[i].user_id+"'>"+e.users[i].username+"</option>";
		    	}
		    	$('#person').append(str2);//采购人
		    	for(var i=0;i<e.storages.length;i++){
		    		str3 += "<option value='"+e.storages[i].id+"'>"+e.storages[i].name+"</option>";
		    	}
		    	$('#order_status').append(str3);//业务类别
		    	for(var i=0;i<e.express.length;i++){
		    		str4 += "<option value='"+e.express[i].id+"'>"+e.express[i].express_name+"</option>";
		    	}
		    	$('#express_id').append(str4);//货运公司			    	
	        },
	        error:function(e){
//	            console.log(e)
	        }
	    })		
		//日期选择
		laydate.render({
		  elem: '#out_date',
		  trigger: 'click',
		  theme: '#7C71BF'	  
		});	
		
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
            var price= obj.price;
            var stock = obj.stock;
            str +="<tr>" +
                    "<td>" +
                        "<input type='hidden' name='goods_id[]' value='"+good_id+"'><span>"+good_num+"</span>" +
                    "</td>" +
                    "<td><span>"+good_name+"</span></td>" +
                    "<td>" +
                        "<div style='position: relative;'>" +
                            "<img  height='26px' class='mini_imgpath' src='"+mini_imgpath+"' alt='商品图片' onmouseover='showimg(this)' onmouseout='closeimg(this)'>" +
                            "<div style='left:60px;top:-80px;z-index: 2;display:none;position:absolute;border: 1px solid #AAAAAA;border-radius:3px;'><img style='width:120px;height:120px;border-radius:3px;' class='imgpath' src='"+imgpath+"' alt='商品图片'></div>" +
                        "</div>" +
                    "</td>" +
                    "<td><span>"+spec+"</span></td>" +
                    "<td><span>"+unit+"</span></td>" +
                    "<td><input type='number' style='width=60px'  onchange='getsum2(this)' class='number' value='1' name='number[]'></td>" +
                    "<td><span class='stock'>"+stock+"</span></td>" +
                    "<td><input type='text'  style='width=60px'   onchange='getsum1(this)'  class='price' value='"+price+"' name='price[]'></td>" +
                    "<td><span class='sum'>"+price+"</span>元</td><td><input type='text' name='good_notes[]' ></td>" +
                    "<td><a class='bst' href='javascript:void(0)' onclick='removetr(this)'>删除</a></td>" +
                "</tr>";
        })
        $('#goodstable').find('tbody').append(str);
        getallsum();
        getallnum();
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
    

    //提交时检查字段信息
    function checkinfo(){

        var out_date = $('#out_date').val();
        var person = $('#person').val();
        var order_status = $('#order_status').val();
        var goods = $('#goodstable tbody tr');
        var out_prices = $('.price');
        var numbers = $('.number');
        var flag = true;
        if (out_date.length == 0) {
            alert('请输入采购日期');
            flag = false;
            return false;

        }
        if (!flag) {
            return false;
        }
        if (person.length == 0) {
            alert('请选择出库人');
            flag = false;
            return false;

        }
        if (!flag) {
            return false;
        }
        if (order_status.length == 0) {
            alert('请选择业务类别');
            flag = false;
            return false;

        }
        if (!flag) {
            return false;
        }
        if (goods.length == 0){
            alert('请选择商品');
            flag = false;
            return false;
        }
        if (!flag) {
            return false;
        }
        out_prices.each(function (k,v) {
            var price = $(v).val();
            if (price.length == 0) {
                alert("请输入出库价格");
                flag = false;
                return false;
            }
        })
        if (!flag) {
            return false;
        }
        numbers.each(function (k,v) {
            var num = parseFloat($(v).val());
            if (num.length == 0 || num < 1) {
                alert("请输入商品数量");
                flag = false;
                return false;
            }
            var stock = parseFloat($(v).parent().next().children('.stock').html());
            if (num > stock) {
                alert("商品数量大于库存");
                flag = false;
                return false;
            }
        })
        if (!flag) {
            return false;
        }
        getallsum();
//return flag;  
//  提交数据    **************************************************
        var result=confirm("确认要添加吗？");
        if(result){
	        var goods_id = [];
	        var price = [];
	        var number = [];
	        var good_notes = [];
			var trList = $("#goodstable tbody").children("tr")
			  for (var i=0;i<trList.length;i++) {
			    var td = trList.eq(i).find("td");		    
			    var td_goods_id = td.eq(0).find("input").val();//商品ID
			    var td_price = td.eq(7).children('.price').val();//采购价格
			    var td_number = td.eq(5).find("input").val();//采购数量
			    var td_good_notes = td.eq(9).find("input").val();//商品备注
				goods_id[i] = td_goods_id;
				price[i] = td_price;
				number[i] = td_number;
				good_notes[i] = td_good_notes;
	
			  }
	
	        var express_id = $("#express_id").val();
	        var express_price = $("#express_price").val();
	        var express_num = $("#express_num").val();
	        var notess = $("#notes").val();
	        var sum_price = $("#sum_price").text();
			var arr = {'date':out_date,'express_id':express_id,'express_price':express_price,'express_num':express_num,
						'person':person,'order_status':order_status,'notes':notess,'sumprice':sum_price,
						'goods_id':goods_id,'price':price,'number':number,'good_notes':good_notes};
	
	      	    $.ajax({
			        "url": "http://www.s2.com/index/JxcOrder/storeout",
			        "type": "post",
			        "dataType": "json",
			        "data": {data:arr},
			        success: function (e) {
			          if(e.status == 2){
			            alert("添加出库单成功！");
			            parent.getrkx();
			            parent.fenye();
			            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
			            parent.layer.close(index);				            			            
			          }else if (e.status == 1){
			              alert(e.msg)
			          }	 				
			        },
			        error:function(e){
		//	            console.log(e)
			        }
			    })	  
     	}
    }



    //计算总价
    function getsum1(obj) {
        var price = parseFloat($(obj).val());
        var num = parseFloat($(obj).parent().prev().prev().children("input:first-child").val());
        var regx = /^(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*))$/;
        if(regx.test(price)){
            if (num < 0){
                num = 0;
            }
            var sum = num * price;
            $(obj).parent().next().children("span").html(sum.toFixed(3));
            getallsum();
        }else {
            alert("请输出正确的价格");
            $(obj).val("1.00");
            var sum =  num;
            $(obj).parent().next().children("span").html(sum.toFixed(3));
            getallsum();
        }
    }
    function getsum2(obj) {
        var num = parseFloat($(obj).val());
        var price = parseFloat($(obj).parent().next().next().children("input:first-child").val());
        var regx = /^\d+$/;
        if(regx.test(num)){
            if (num < 0){
                num = 0;
            }
            var sum = num * price;
            $(obj).parent().next().next().next().children("span").html(sum.toFixed(3));
            getallsum();
            getallnum();
        }else {
            alert("请输出正确的数量");
            $(obj).val("1");
            getallsum();
            getallnum();
        }
    }
    //获取全部总价
    function getallsum(){
        var sum = 0;
        $('.sum').each(function () {
            sum += parseFloat($(this).html());
//            console.log($(this).html());
        });
        $('#sum_price').html(sum.toFixed(3));
        $('#sum_price').next().val(sum.toFixed(3));
    }
    //获取全部数量
    function getallnum(){
        var sum = 0;
        $('.number').each(function () {
            sum += parseFloat($(this).val());
//            console.log($(this).html());
        });
        $('#allnum').html(sum);
    }
    //删除当前商品行
    function removetr(obj){
        $(obj).parents("tr").remove();
        getallsum();
        getallnum();
    }
