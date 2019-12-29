	
	var zongye="";
	var p=1;
	var str='';
	var search = $('#suo').val();
  //数据展示
	function supplier() {
	    $.ajax({
	        "type": "post",
	        "url": "http://www.s2.com/index/JxcSupplier/index",
	        "dataType": "json",
	        "data": {page:p,pageone:10,search:search},
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
	                str+='<tr ondblclick="doshow(11)" onmouseover="trx(this)" onmouseout="tre(this)">'+
                            '<td>'+(i+1)+'</td>'+
                            '<td>'+e.data[i].supplier_num+'</td>'+
                            '<td>'+e.data[i].supplier_name+ '</td>'+
                            '<td>'+e.data[i].tel+'</td>'+
                            '<td>'+e.data[i].linkman+'</td>'+
                            '<td>'+e.data[i].phone+'</td>'+
                            '<td class="center">'+e.data[i].create_time+'</td>'+
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
	            $('#ye').html(str2);
	            for(var i=0;i<$('#ye option').length;i++){
	                if($('#ye option').eq(i).val()==p){
	                    $('#ye option').eq(i).attr("selected",true);
	                }
	            }
	            
	            }
				$('.table tbody').html(str)
	        }
	    })
	}
	//	分页跳页
	function fenye(){
	    $.ajax({
	        "type": "post",
	        "url": "http://www.s2.com/index/JxcSupplier/index",
	        "dataType": "json",
	        "data": {page:p,pageone:10,search:search},
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
			        	supplier();
			        }
			    });
		    }
	    })			
	}



	$(function  () {
		supplier();
		fenye();
		//回车事件
		$("#suo").keyup(function(event){
		  if(event.keyCode ==13){
		    $("#xmjs").trigger("click");
		  }
		});
		
		$("#shouyes").on("click",function(){
		    p=1;
		    supplier();
		})
		$("#shangyes").click(function () {	
		    if(p>1){
		        p--;
		        supplier()
		    }else {
		        alert("已经到第一页了");
		        return false;
		    }
		});		
		$('#xiayes').click(function () {
		    if(p<zongye){
		        p++;
		        supplier()
		    }else {
		        alert("已经到最后一页了");
		        return false;
		    }
		    });
		$('#tiaoyes').click(function () {
		    status=$('#jkl').val();
		    p=$('#ye').val();
		    supplier()
		})
		$("#weiyes").click(function () {    
			p=zongye;
		    supplier()
		});
		//查询供应商
	    $("#xmjs").click(function () {
	        search = $('#suo').val();
//	        var flag = true;
//	       if (search.length == 0) {
//	            alert('供应商不能为空');
//	            flag = false;
//	            return false;
//	        }
//	        if (!flag) {
//	            return false;
//	        }
	        supplier();
	    });
	    //当前库存小于0 标红
	        $('.stock').each(function(){
	            var stock = $(this).html();
	            if (stock<0){
	                $(this).css('color','red');
	            }
	        })	
	        
		  $('.button-i').on('click', function(){
			layer.open({
			  type: 2,
			  title: ['添加供应商', 'font-size:18px;font-weight:bold;color:#FFFFFF;text-align:center;background:#7D72C1;'],
			  area: ['55%', '470px'],
			  shadeClose: false, //点击遮罩关闭
			  content:['supplier/app.html', 'no']
			   });		    
	  	  });	        
	
	});
	  //弹出一个页面层
	 	 function ck(id,ojb){
			layer.open({
			  type: 2,
			  title: ['查看供应商信息', 'font-size:18px;font-weight:bold;color:#FFFFFF;text-align:center;background:#7D72C1;'],
			  area: ['55%', '470px'],
			  shadeClose: false, //点击遮罩关闭
			  content: ['supplier/show.html', 'no']
			});
			$(".sup-id").val(id)    
		  }
		  
		function xg(id,ojb){
			layer.open({
			  type: 2,
			  title: ['修改供应商', 'font-size:18px;font-weight:bold;color:#FFFFFF;text-align:center;background:#7D72C1;'],
			  area: ['55%', '470px'],
			  shadeClose: false, //点击遮罩关闭
			  content:['supplier/add.html', 'no']
			});
			$(".sup-id").val(id)    
		  }  
	//删除数据
   function sc(id,obj){
    var result=confirm("确认要删除吗");
    if(result){
      $.ajax({
        url:"http://www.s2.com/index/JxcSupplier/delete",
        type:"post",
        async:false,
        data:{"id":id},
        success:function(e){
          if(e.status == 2){
          	alert('删除成功');
            obj.parentNode.parentNode.remove();
            supplier();
            fenye();
          }else if (e.status == 1){
              alert('删除失败');
          }
        }
      })
    }
   }	

//tr鼠标事件  
function trx(jpo){
	td1=$(jpo).find("td").eq(0);
	td7=$(jpo).find("td").eq(7);
	$(jpo).css('box-shadow','5px 5px 4px #E0E0E0')
	$(jpo).css('background','#F0F4F5')
	$(td1).css('border-radius','10px 0 0 10px') 	
	$(td7).css('border-radius','0 10px 10px 0') 	
	 	
}
function tre(jpo){
	td1=$(jpo).find("td").eq(0);
	td7=$(jpo).find("td").eq(7);
	$(jpo).css('box-shadow','');
	$(jpo).css('background','');
	$(td1).css('border-radius',''); 
	$(td7).css('border-radius',''); 

}
