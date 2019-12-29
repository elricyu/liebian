 var pd_id = parent.$('#chakanpd').val();
 $(function() {
    $.ajax({
	    url:"http://www.s2.com/index/JxcOrder/showpd",
	    type:"post",
	    async:false,
	    data:{id:pd_id},
	    success:function(e){
	    	$("#out_date").val(e.order_info.date)
	    	$("#user_name").text(e.order_info.person)
	    	$("#notes").text(e.order_info.notes)
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
	    url:"http://www.s2.com/index/JxcOrder/showpd",
	    type:"post",
	    async:false,
	    data:{id:pd_id},
	    success:function(e){
	        var str = '';
	        for(var i=0;i<e.order_goods.length;i++){
	            str +="<tr onmouseover='trx(this)' onmouseout='tre(this)'>" +
	                    "<td>" +
	                        "<input type='hidden' name='goods_id[]' value='"+e.order_goods[i].id+"'><span>"+e.order_goods[i].good_num+"</span>" +
	                    "</td>" +
	                    "<td><span>"+e.order_goods[i].good_name+"</span></td>" +
	                    "<td>" +
	                        "<div style='position: relative;'>" +
	                            "<img  height='26px' class='mini_imgpath' src='"+e.order_goods[i].mini_imgpath+"' onerror=this.src='img/default2.jpg'  onmouseover='img_x("+e.order_goods[i].id+",this)' onmouseout='img_y("+e.order_goods[i].id+",this)' alt='商品图片'>" +
	                            "<div style='left:60px;top:-70px;z-index: 2;display:none;position:absolute;border: 1px solid #AAAAAA;border-radius:3px;'>"+
	                            "<img style='width:120px;height:120px;border-radius:3px;' class='imgpath' src='"+e.order_goods[i].imgpath+"' onerror=this.src='img/default2.jpg' alt='商品图片'></div>" +
	                        "</div>" +
	                    "</td>" +
	                    "<td><span>"+e.order_goods[i].spec+"</span></td>" +
	                    "<td><span>"+e.order_goods[i].unit+"</span></td>" +
	                    "<td><input type='number'style='width:62px' onchange='getsum2(this)' class='number' value="+e.order_goods[i].price+" disabled></td>" +
	                    "<td><input type='number'  style='width:62px' onchange='getsum1(this)'  class='price' value='"+e.order_goods[i].pd_stock+"' disabled></td>" +
	                    "<td><span class='sum'>"+e.order_goods[i].pd_number+"</span></td>"+
	                    "<td><span class='sum'>"+(e.order_goods[i].pd_number-e.order_goods[i].pd_stock)+"</span></td>"+
	                    "<td><input type='text' style='width:62px' class='form-control' value='"+e.order_goods[i].good_notes+"' disabled></td>" +
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
    function checkinfo(){
        var result=confirm("确认要作废吗");
        if(result){
            $.ajax({
                url:"http://www.s2.com/index/JxcOrder/deleteenter",
                type:"post",
                async:false,
                data:{"id":pd_id},
                success:function(e){
		          if(e.status == 2){
		          	alert("作废入库单成功");
	            	parent.getpd();
		            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
		            parent.layer.close(index);		            
		          }else if (e.status == 1){
		            alert(e.msg)
		          }
                }
            })
        }
    }
    
 //tr鼠标事件  
function trx(jpo){
	td1=$(jpo).find("td").eq(0);
	td8=$(jpo).find("td").eq(8);
	$(jpo).css('box-shadow','5px 5px 4px #E0E0E0')
	$(jpo).css('background','#F0F4F5')
	$(td1).css('border-radius','10px 0 0 10px') 	
	$(td8).css('border-radius','0 10px 10px 0') 	
	 	
}
function tre(jpo){
	td1=$(jpo).find("td").eq(0);
	td8=$(jpo).find("td").eq(8);
	$(jpo).css('box-shadow','');
	$(jpo).css('background','');
	$(td1).css('border-radius',''); 
	$(td8).css('border-radius',''); 

}