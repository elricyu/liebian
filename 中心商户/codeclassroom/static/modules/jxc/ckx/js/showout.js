 var ids = parent.$('#chaid').val();
 $(function() {
    $.ajax({
	    url:"http://www.s2.com/index/JxcOrder/showout",
	    type:"post",
	    async:false,
	    data:{id:ids},
	    success:function(e){
	        console.log(e);
	    	$("#date").text(e.order_info.date)
	    	$("#person").text(e.order_info.person)
	    	$("#user_name").text(e.order_info.user_name)
	    	$("#order_status").val(e.order_info.order_status)
	    	$("#express_name").val(e.order_info.express_name)
	    	$("#express_price").val(e.order_info.express_price)
	    	$("#express_num").val(e.order_info.express_num)
	    	$("#notes").val(e.order_info.notes)
	    }
	 }) 
	getData(); 
	 
 })

    function cancle() {
        $('#good_listdiv').css('display','none');
    }
    function showgoodlist() {
        $('#good_listdiv').css('display','');
    }
    function getData(){    	
    $.ajax({
	    url:"http://www.s2.com/index/JxcOrder/showout",
	    type:"post",
	    async:false,
	    data:{id:ids},
	    success:function(e){
	        var str = '';
	        for(var i=0;i<e.order_goods.length;i++){
	            str +="<tr>" +
	                    "<td>" +
	                        "<input type='hidden' name='goods_id[]' value='"+e.order_goods[i].id+"'><span>"+e.order_goods[i].good_num+"</span>" +
	                    "</td>" +
	                    "<td><span>"+e.order_goods[i].good_name+"</span></td>" +
	                    "<td>" +
	                        "<div style='position: relative;'>" +
	                            "<img  height='26px' class='mini_imgpath' src='"+e.order_goods[i].mini_imgpath+"' alt='商品图片' onerror=this.src='img/default2.jpg' onmouseover='img_x("+e.order_goods[i].id+",this)' onmouseout='img_y("+e.order_goods[i].id+",this)'>" +
	                            "<div style='left:50px;top:-80px;z-index: 2;display:none;position:absolute;border: 1px solid #AAAAAA;border-radius:3px;' ><img style='width:120px;height:120px;border-radius:3px;' class='imgpath' src='"+e.order_goods[i].imgpath+"' onerror=this.src='img/default2.jpg' alt='商品图片'></div>" +
	                        "</div>" +
	                    "</td>" +
	                    "<td><span>"+e.order_goods[i].spec+"</span></td>" +
	                    "<td><span>"+e.order_goods[i].unit+"</span></td>" +
	                    "<td><input type='number'style='width:60px' onchange='getsum2(this)' class='number' value="+e.order_goods[i].number+" disabled></td>" +
	                    "<td><input type='text'  style='width:60px' onchange='getsum1(this)'  class='price' value='"+e.order_goods[i].price+"'disabled></td>" +
	                    "<td><span class='sum'>"+e.order_goods[i].sumprice+"</span>元</td>"+
	                    "<td><input type='text' style='width:60px' class='form-control' value='"+e.order_goods[i].good_notes+"' disabled></td>" +
	                "</tr>";
	        }
	        $('#goodstable').find('tbody').append(str);
	    }
	    
	 }) 	        
	        
	        getallsum();
	        getallnum();
	    }
//  function closeself(){
//      parent.cancleadd();
//  }
//弹出图片
function img_x(id,jop){
	$(jop).next().css('display','')
}
function img_y(id,jop){
	$(jop).next().css('display','none')
}
    //计算总价
    function getsum1(obj) {
        var price = parseFloat($(obj).val());
        var num = parseFloat($(obj).parent().prev().children("input:first-child").val());
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
        }
    }
    function getsum2(obj) {
        var num = parseFloat($(obj).val());
        var price = parseFloat($(obj).parent().next().children("input:first-child").val());
        var regx = /^\d+$/;
        if(regx.test(num)){
            if (num < 0){
                num = 0;
            }
            var sum = num * price;
            $(obj).parent().next().next().children("span").html(sum.toFixed(3));
            getallsum();
            getallnum();
        }else {
            alert("请输出正确的数量");
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
    }

    //订单作废 删除订单
    function del(){
        var result=confirm("确认要作废吗");
        if(result){
            $.ajax({
                url:"http://www.s2.com/index/JxcOrder/deleteout",
                type:"post",
                async:false,
                data:{"id":ids},
                success:function(e){
		          if(e.status == 2){
		          	alert("作废出库单成功");
		          	parent.getrkx();
		          	parent.fenye();
		            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
		            parent.layer.close(index);		            		            		            
		          }else if (e.status == 1){
		            alert(e.msg);
		          }
                }
            })
        }
    }