    var zongye=0;
    var p=1;
    var search = $('#suo').val();
    var typeid = $('#typeid').val();
    var arr = {'search':search,'type_id':typeid,'pageone':10,'page':p};
//  数据展示
	function getkcx(){
	    arr.page = p;
	    $.ajax({
	        "url": "http://www.s2.com/index/JxcStock/index",
	        "type": "post",
	        "dataType": "json",
	        "data": {map:arr},
	        success: function (e) {
	            var str='';
	            var str2='';
	            var str10="";
				var str20="";
	            if(!e.data){
	                str = "<tr><td colspan='11'>没有查找到数据!</td></tr>";
	                str2='<option value="0">0</option>'
	                $('#nums').html("");
	            }else{
	                zongye=e.map.pagetotal;
	                $('#nums').html("共有"+e.map.count+"条记录&nbsp;&nbsp;当前"+e.map.page+"页/共"+e.map.pagetotal+"页");
	                for(var i=0;i<e.data.length;i++){
	                    str+='<tr ondblclick="doshow(44)" onmouseover="trx(this)" onmouseout="tre(this)">'+
		                        '<td>'+(i+1)+'</td>'+
		                        '<td>'+e.data[i].good_num+'</td>'+
		                        '<td class="center">'+e.data[i].good_name+'</td>'+
		                        '<td class="center">'+
		                            '<div style="position: relative;">'+
		                                '<img src="'+e.data[i].mini_imgpath+'" onmouseover="img_x('+e.data[i].id+',this)" onmouseout="img_y('+e.data[i].id+',this)" src="'+e.data[i].mini_imgpath+'" onerror=this.src="img/default2.jpg" alt="商品图片"  height="26px">'+
		                                '<div style="left:70px;top:-70px;z-index: 2;display:none;position: absolute;border: 1px solid #AAAAAA;border-radius:3px;">'+
		                                	'<img src="'+e.data[i].imgpath+'" onerror=this.src="img/default2.jpg" alt="商品图片" style="width: 120px;height: 120px;border-radius:3px;">'+
		                                '</div>'+
		                            '</div>'+
		                        '</td>'+
		                        '<td class="center">'+e.data[i].type_name+'</td>'+
		                        '<td class="center">'+e.data[i].spec+'</td>'+
		                        '<td>'+e.data[i].unit+'</td>'+
		                        '<td>'+e.data[i].number+'</td>'+
		                        '<td>'+e.data[i].stock_sum_price+'</td>'+
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
	        "url": "http://www.s2.com/index/JxcStock/index",
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



$(function () {
	getsu();
    getkcx();
    //回车事件
	$(".suo").keyup(function(event){
	  if(event.keyCode ==13){
	    $("#xmjs").trigger("click");
	  }
	}); 	
//	分页跳页
    $.ajax({
        "url": "http://www.s2.com/index/JxcStock/index",
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
		        	getkcx();
		        }
		    });
	    }
    })

	$("#shouyes").on("click",function(){
	    p=1;
	    getkcx();
	})
	$("#shangyes").click(function () {	
	    if(p>1){
	        p--;
	        getkcx()
	    }else {
	        alert("已经到第一页了");
	        return false;
	    }
	});	
	$('#xiayes').click(function () {
        if(p<zongye){
            p++;
            getkcx();
        }else {
            alert("已经到最后一页了");
            return false;
        }
    });
	$('#tiaoyes').click(function () {
	    status=$('#jkl').val();
	    p=$('#ye').val();
	    getkcx()
	})
	$("#weiyes").click(function () {    
		p=zongye;
	    getkcx()
	});
//  查询商品   
    $("#xmjs").click(function () {
        arr.type_id = $('#typeid').val();
        arr.search = $('#suo').val();       
        getkcx();
    });
    
 
	 $("#button-name").on("click",function(){
	   	var name=$("#button-a").val()
	    var result=confirm("确认要添加么吗");
		    if(result){    	 
		      $.ajax({
		        url:"http://www.s2.com/index/JxcGoodsType/store",
		        type:"post",
		        async:false,
		        data:{type_name:name},
		        success:function(e){
		        	console.log(e.status);       	
		        }
		      })
		    }
	});      
	    
});


//弹出图片
function img_x(id,jop){
	$(jop).next().css('display','')
}
function img_y(id,jop){
	$(jop).next().css('display','none')
}
	function showupdate(obj,tag){
	    if (tag == 1) { //判断是双击还是点击修改
	    var val = $(obj).next().css('display');
	    if (val == 'none'){ //判断当前是否为打开状态
	        $(obj).next().css('display','');
	    }else{
	        $(obj).next().css('display','none');
	    }
	}else{
	    var val = $(obj).parents('tr').next().css('display');
	    if (val == 'none'){ //判断当前是否为打开状态
	        $(obj).parents('tr').next().css('display','');
	    }else{
	        $(obj).parents('tr').next().css('display','none');
	            }
	        }
	    }



   function del(id,obj){
    var result=confirm("确认要删除吗？");
	if(result){
	  $.ajax({
	    url:"http://www.s2.com/index/JxcGoodsType/delete",
	        type:"post",
	                async:false,
	                data:{id:id},
	                success:function(e){
	                	console.log(e);
	                  if(e.status == 2){
	                    obj.parentNode.parentNode.remove();
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