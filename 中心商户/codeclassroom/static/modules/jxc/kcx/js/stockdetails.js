$(function(){
    var zongye=0;
    var p=1;
    var search = $('#suo').val();
    var timee = $('#timee').val();
    var times = $('#times').val();
    var typeid = $('#typeid').val();
    var order_type = $('#order_type').val();
    var arr = {'search':search,'time_e':timee,'time_s':times,'type_id':typeid,'order_type':order_type,'pageone':10,'page':p};
	getsu();
	getpd();
	//回车事件
	$(".suo").keyup(function(event){
	  if(event.keyCode ==13){
	    $("#xmjs").trigger("click");
	  }
	});	

	//	分页跳页
    $.ajax({
        "url": "http://www.s2.com/index/JxcStock/stockdetails",
        "type": "post",
        "dataType": "json",
        "data": {map:arr},
	    success:function(e){		    	
	    	pps=e.map.pagetotal;
		    $.jqPaginator('#pagination2', {
		        totalPages: pps,
		        visiblePages: 5,
		        currentPage: 1,
		        first: '<li class="homepage"> << </li>',
		        last: '<li class="last"> >> </li>',
		        prev: '<li class="previouspage"> < </li>',
		        next: '<li class="nextpage"> > </li>',
		        page: '<li class="pagein">{{page}}</li>',
		        onPageChange: function (num, type) {
		        	p=num;
		        	getpd();
		        }
		    });
	    }
    })

	$("#shouyes").on("click",function(){
	    p=1;
	    getpd();
	})
	$("#shangyes").click(function () {	
	    if(p>1){
	        p--;
	        getpd()
	    }else {
	        alert("已经到第一页了");
	        return false;
	    }
	});	
	$('#xiayes').click(function () {
        if(p<zongye){
            p++;
            getpd();
        }else {
            alert("已经到最后一页了");
            return false;
        }
    });
	$('#tiaoyes').click(function () {
	    status=$('#jkl').val();
	    p=$('#ye').val();
	    getpd()
	})
	$("#weiyes").click(function () {    
		p=zongye;
	    getpd()
	});
//  查询商品   
    $("#xmjs").click(function () {
	    arr.search = $('#suo').val();
		arr.time_e = $('#timee').val();
	    arr.time_s = $('#times').val();
	    arr.type_id = $('#typeid').val();
	    arr.order_type = $("#order_type").val();       
        getpd();
    });
  
//  数据展示
	function getpd(){
	    arr.page = p;
	    $.ajax({
	        "url": "http://www.s2.com/index/JxcStock/stockdetails",
	        "type": "post",
	        "dataType": "json",
	        "data": {map:arr},
	        success: function (e) {
	            var str='';
	            var str2='';
	            var str10="";
	            if(e.data.length==0){
	                str = "<tr><td colspan='11'>没有查找到数据!</td></tr>";
	                str2='<option value="0">0</option>'
	                $('#nums').html("");
	            }else{
	                zongye=e.map.pagetotal;
	                $('#nums').html("共有"+e.map.count+"条记录&nbsp;&nbsp;当前"+e.map.page+"页/共"+e.map.pagetotal+"页");
	                for(var i=0;i<e.data.length;i++){
	                    str+='<tr ondblclick="doshow(72)" onmouseover="trx(this)" onmouseout="tre(this)">'+
	                            '<td>'+(i+1)+'</td>'+
	                            '<td>'+e.data[i].order_num+'</td>'+
	                            '<td>'+e.data[i].good_num+'</td>'+
	                            '<td class="center">'+e.data[i].good_name+'</td>'+
	                            '<td class="center">'+
	                                '<div style="position: relative;">'+
	                                    '<img src="'+e.data[i].mini_imgpath+'" onmouseover="img_x('+e.data[i].id+',this)" onmouseout="img_y('+e.data[i].id+',this)" src="'+e.data[i].mini_imgpath+'" onerror=this.src="img/default2.jpg" alt="商品图片" height="26px">'+
	                                    '<div style="left:60px;top:-80px;z-index: 2;display:none;position:absolute;border: 1px solid #AAAAAA;border-radius:3px;">'+
	                                    	'<img src="'+e.data[i].imgpath+'" onerror=this.src="img/default2.jpg" alt="商品图片" style="width:120px;height:120px;border-radius:3px;">'+
	                                    '</div>'+
	                                '</div>'+
	                            '</td>'+
	                            '<td class="center">'+e.data[i].type_name+'</td>'+
	                            '<td class="center">'+e.data[i].spec+'</td>'+
	                            '<td>'+e.data[i].unit+'</td>'+
	                            '<td>'+e.data[i].price+'</td>'+
	                            '<td>'+e.data[i].storage_name+'</td>'+
	                            '<td>'+e.data[i].enter_number+'</td>'+
	                            '<td>'+e.data[i].out_number+'</td>'+
	                            '<td>'+e.data[i].enter_price+'</td>'+
	                            '<td>'+e.data[i].out_price+'</td>'+
		                      '</tr>' 
	                }		    	
	                for(var i=0;i<e.map.pagetotal;i++){
	                    var aa=Number(i)+1;
	                    str2+='<option value="'+aa+'">'+aa+'</option>'
	                }
	            }
			$('#ye').html(str2);
            $('.table tbody').html(str)
	        },
	        	            
	        error:function(e){
//	            console.log(e)
	        }
	    })
	}	
	
	function getsu(){
	    $.ajax({
	        "url": "http://www.s2.com/index/JxcStock/stockdetails",
	        "type": "post",
	        "dataType": "json",
	        "data": {map:arr},
	        success: function (e) {	
	            var str10="";
		    	for(var i=0;i<e.types.length;i++){
		    		str10 += "<option value='"+e.types[i].id+"'>"+e.types[i].type_name+"</option>";
		    	}
		    	$('#typeid').append(str10);    	
	        }
	      })
	} 
	//日期选择
	lay('.laydate').each(function(){
	  laydate.render({
	    elem: this,
	    trigger: 'click',
		theme: '#7C71BF'
	  });
	});
      	

})
//弹出图片
function img_x(id,jop){
	$(jop).next().css('display','')
}
function img_y(id,jop){
	$(jop).next().css('display','none')
}
//tr鼠标事件  
function trx(jpo){
	td1=$(jpo).find("td").eq(0);
	td4=$(jpo).find("td").eq(13);
	$(jpo).css('box-shadow','5px 5px 4px #E0E0E0')
	$(jpo).css('background','#F0F4F5')
	$(jpo).css('border-radius','10px') 	
	$(td1).css('border-radius','10px 0 0 10px') 	
	$(td4).css('border-radius','0 10px 10px 0') 	
	 	
}
function tre(jpo){
	td1=$(jpo).find("td").eq(0);
	td4=$(jpo).find("td").eq(13);
	$(jpo).css('box-shadow','');
	$(jpo).css('background','');
	$(jpo).css('border-radius',''); 
	$(td1).css('border-radius',''); 
	$(td4).css('border-radius',''); 

}