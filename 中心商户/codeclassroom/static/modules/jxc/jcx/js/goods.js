    var zongye=0;
    var p=1;
    var search = $('#suo').val();
    var typeid = $('#typeid').val();
    var supplier_id = $('#supplierid').val();
	var arr = {'search':search,'typeid':typeid,'supplierid':supplier_id,'pageone':10,'page':p};	
//  数据展示
	function getGoods(){
	    arr.page = p;
	    $.ajax({
	        "url": "http://www.s2.com/index/JxcGoods/index",
	        "type": "post",
	        "dataType": "json",
	        "async":"false",
	        "data": {map:arr},
	        success: function (e) {
	            var str='';
	            var str2='';
	            if(!e.data){	            	
	                str = "<tr><td colspan='11'>没有查找到数据!</td></tr>";
	                str2='<option value="0">0</option>'
	                $('#nums').html("");
	            }else{
	                zongye=e.map.pagetotal;
	                $('#nums').html("共有"+e.map.count+"条记录&nbsp;&nbsp;当前"+e.map.page+"页/共"+e.map.pagetotal+"页");
	                for(var i=0;i<e.data.length;i++){
	                    str+='<tr onmouseover="trx(this)" onmouseout="tre(this)">' +
	                        '<td>'+(i+1)+'</td>'+
	                        '<td>'+e.data[i].good_num+'</td>'+
	                        '<td class="center">'+e.data[i].good_name+'</td>'+
	                        '<td>'+e.data[i].type_name+'</td>'+
	                        '<td class="center">'+e.data[i].spec+'</td>'+
	                        '<td class="center">'+e.data[i].unit+'</td>'+
	                        '<td >'+
	                        '<div style="position: relative;">'+
		                        '<img  height="26px" onmouseover="img_x('+e.data[i].id+',this)" onmouseout="img_y('+e.data[i].id+',this)" src="'+e.data[i].mini_imgpath+'" onerror=this.src="img/default2.jpg" alt="商品图片">'+
		                        '<div style="left:80px;top:-80px;z-index: 2;display:none;position: absolute;border: 1px solid #AAAAAA;border-radius:3px;">'+
		                        	'<img style="width: 120px;height: 120px;border-radius:3px;" src="'+e.data[i].imgpath+'" onerror=this.src="img/default2.jpg" alt="商品图片"></div>'+
	                        '</div>'+
	                        '</td>'+
	                        '<td class="center">'+e.data[i].cost_price+'</td>'+
	                        '<td class="center">'+e.data[i].purchase_price+'</td>'+
	                        '<td class="center">'+e.data[i].real_price+'</td>'+
	                        '<td class="center">'+
                               '<div class="xan">'+
                               '<span class="bune ck" onclick="ck('+e.data[i].id+',this)">查看   </span> '+
                               '<span class="bune xg" onclick="xg('+e.data[i].id+',this)">修改   </span> '+
                               '<span class="bune sc" href="javascript:void(0)" onclick="sc('+e.data[i].id+',this)">删除</span>'+
							   '</div>'+
	                        '</td>'+
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
	        }
	    })
	}
	function getsu(){
	    $.ajax({
	        "url": "http://www.s2.com/index/JxcGoods/index",
	        "type": "post",
	        "dataType": "json",
	        "async":"false",
	        "data": {map:arr},
	        success: function (e) {	
	        	var str10="";
				var str20="";
		    	for(var i=0;i<e.good_types.length;i++){
		    		str10 += "<option value='"+e.good_types[i].id+"'>"+e.good_types[i].type_name+"</option>";
		    	 
		    	}
		    	$('#typeid').append(str10);
		    	for(var i=0;i<e.suppliers.length;i++){
		    		str20 += "<option value='"+e.suppliers[i].id+"'>"+e.suppliers[i].supplier_name+"</option>";
		    	}						    	
		    	$('#supplierid').append(str20);					
	        }
	      })
	}
	//	分页跳页
	function fenye(){
	   $.ajax({
	        "url": "http://www.s2.com/index/JxcGoods/index",
	        "type": "post",
	        "dataType": "json",
	        "async":"false",
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
			        	getGoods();
			        }
			    });
		    }
	    })	
	}



$(function () {
    getsu();	
    getGoods();
    fenye();
	//回车事件
	$("#suo").keyup(function(event){
	  if(event.keyCode ==13){
	    $("#xmjs").trigger("click");
	  }
	});    
    
	$("#shouyes").on("click",function(){
	    p=1;
	    getGoods();
	})
	$("#shangyes").click(function () {	
	    if(p>1){
	        p--;
	        getGoods()
	    }else {
	        alert("已经到第一页了");
	        return false;
	    }
	});	
	$('#xiayes').click(function () {
        if(p<zongye){
            p++;
            getGoods();
        }else {
            alert("已经到最后一页了");
            return false;
        }
    });
	$('#tiaoyes').click(function () {
	    status=$('#jkl').val();
	    p=$('#ye').val();
	    getGoods()
	})
	$("#weiyes").click(function () {    
		p=zongye;
	    getGoods()
	});
//  查询商品   
    $("#xmjs").click(function () {
        arr.typeid = $('#typeid').val();
        arr.supplierid = $('#supplierid').val();
        arr.search = $('#suo').val();      
        getGoods();
    });   
  
});

//弹出图片
function img_x(id,jop){
	$(jop).next().css('display','')
}
function img_y(id,jop){
	$(jop).next().css('display','none')
}
//弹出查看层
  function ck(id,obj){
    layer.open({
      type: 2,
      title: ['查看商品', 'font-size:18px;font-weight:bold;color:#FFFFFF;text-align:center;background:#7D72C1;'],
      area: ['70%', '480px'],
      shadeClose: false, //点击遮罩关闭
      content: ['goods/show.html', 'no'],
    });
     $(".in").val(id);
          
  }
//弹出修改层  
  function xg(id,obj){
    layer.open({
      type: 2,
      title: ['修改商品', 'font-size:18px;font-weight:bold;color:#FFFFFF;text-align:center;background:#7D72C1;'],
      area: ['70%', '480px'],
      shadeClose: false, //点击遮罩关闭
      content:['goods/add.html', 'no']
    }); 
    $(".in").val(id);
  } 
//弹出添加商品层  
  function button(){
    layer.open({
      type: 2,
      title: ['添加商品', 'font-size:18px;font-weight:bold;color:#FFFFFF;text-align:center;background:#7D72C1;'],
      area: ['70%', '480px'],
      shadeClose: false, //点击遮罩关闭
      content:['goods/app.html']
    });
    
  }	
//删除数据
   function sc(id,obj){
    var result=confirm("确认要删除吗");
    if(result){
      $.ajax({
        url:"http://www.s2.com/index/JxcGoods/delete",
        type:"post",
        async:false,
        data:{"id":id},
        success:function(e){
          if(e.status == 2){
          	alert("删除成功")
            obj.parentNode.parentNode.remove();
            getGoods();
            fenye();
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