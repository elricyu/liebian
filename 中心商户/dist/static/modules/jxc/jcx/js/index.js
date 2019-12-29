	var zongye="";
	var p=1;
	var str='';
	var search = $('#suo').val();
	//数据展示
	function tiaoye() {
	    $.ajax({
	        "type": "post",
	        "url": "http://www.s2.com/index/JxcGoodsType/index",
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
	                str+='<tr ondblclick="showupdate(this,1)" onmouseover="trx(this)" onmouseout="tre(this)"> ' +
	                    '<td>'+(i+1)+'</td>' +
	                    '<td>'+e.data[i].type_num+'</td>' +
	                    '<td>'+e.data[i].type_name+'</td>' +
	                    '<td style="display:none;position: relative;">'+	                    	
	                    	'<input type="text" value="'+e.data[i].type_name+'"  class="inp fl"  required>'+
	                        '<span  class="fl bcxg" onclick="taj('+e.data[i].id+',this)">保存 </span>'+
	                    '</td>' +
	                    '<td>'+e.data[i].create_time+'</td>' +
	                    '<td><div class="span"><span class="xiugai fl" onclick="showupdate(this,2)">修改 </span> '+
	                    '<span href="javascript:void(0);" class="sanchu fr" onclick="del('+e.data[i].id+',this)">删除</span></div>'+
	                    '</td>' +

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
	function fenye() {
	    $.ajax({
	        "type": "post",
	        "url": "http://www.s2.com/index/JxcGoodsType/index",
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
			        	tiaoye();
			        }
			    });
		    }
	    })
	}
	$(function  () {
		tiaoye();
		fenye()
	//	回车事件
	$(".suo").keyup(function(event){
	  if(event.keyCode ==13){
	    $(".xmjs").trigger("click");
	  }
	});
	$("#button-a").keyup(function(event){
	  if(event.keyCode ==13){
	    $("#button-name").trigger("click");
	  }
	});
	$(".inp").keyup(function(event){
	  if(event.keyCode ==13){
	    $(".bcxg").trigger("click");
	  }
	}); 

		$("#shouyes").on("click",function(){
		    p=1;
		    tiaoye();
		})
		$("#shangyes").click(function () {	
		    if(p>1){
		        p--;
		        tiaoye()
		    }else {
		        alert("已经到第一页了");
		        return false;
		    }
		});
		
		$('#xiayes').click(function () {
		    if(p<zongye){
		        p++;
		        tiaoye()
		    }else {
		        alert("已经到最后一页了");
		        return false;
		    }
		    });
		$('#tiaoyes').click(function () {
		    status=$('#jkl').val();
		    p=$('#ye').val();
		    tiaoye()
		})
		$("#weiyes").click(function () {    
			p=zongye;
		    tiaoye()
		});
	
		//查询类别
		    $("#xmjs").click(function () {
		    search = $('#suo').val();	
	        var flag = true;
	        if (search.length == 0) {
	            tiaoye()
	            flag = false;
	            return false;
	        }
	        if (!flag) {
	            return false;
	        }	        
	        tiaoye();
		    });
		//添加数据
		 $("#button-name").click(function () { 
		   	var name=$("#button-a").val()
	        var flag = true;
	        if (name.length == 0) {
	            alert('类别名称不能为空');
	            flag = false;
	            return false;
	        }
	        if (!flag) {
	            return false;
	        }			   	
		    var result=confirm("确认要添加么吗");
		    if(result){    	 
		      $.ajax({
		        url:"http://www.s2.com/index/JxcGoodsType/store",
		        type:"post",
		        async:false,
		        data:{type_name:name},
		        success:function(e){
		        	console.log(e)
		        	if(e.status==2){
		        		alert("添加成功");
		        		$("#button-a").val("");
		        		tiaoye();
		        		fenye();
		        	}else if(e.status==1){
		        		alert(e.msg);
		        		$("#button-a").val("");
		        	}		        	
		        }
		      })
		    }
		});  
	
	});

//修改类别名称
	var a="";
	function taj(id,obj) {
		var td = $(obj).parents("tr").children("td");
		var a  =$(td.eq(3)).find("input").val();
		var flag = true;
        if (a.length == 0) {
            alert('类别名称不能为空');
            tiaoye();
            flag = false;
            return false;
        }
        if (!flag) {
            return false;
        }
	    var result=confirm("确认要修改吗？");
		if(result){
		  $.ajax({
		    url:"http://www.s2.com/index/JxcGoodsType/update",
		    type:"post",
	        async:false,
	        data:{id:id,type_name:a},
	        success:function(e){
	          	if(e.status==2){
	          		alert("修改成功")
					tiaoye();
					fenye();
	          	}
	          	if(e.status==1){
	          		alert(e.msg)
	          		tiaoye();
	          	}					
		      }
		    })
		} 		 		
	}
		
//展开修改类别名称
	function showupdate(obj,tag){
		var td_content = $(obj).parents("tr").children("td"); 
		var content = td_content.eq(2).css('display') 
		var content2 = td_content.eq(3).css('display')   
		if (tag == 1) { //判断是双击还是点击修改
			var content2 = td_content.eq(3).css('display')   
            if (content2 == 'none'){ //判断当前是否为打开状态
                td_content.eq(3).css('display','');
                td_content.eq(2).css('display','none');
            }else{
                td_content.eq(2).css('display','');
                td_content.eq(3).css('display','none');
            }
        }else{
			var content2 = td_content.eq(3).css('display') 
            if (content2 == 'none'){ //判断当前是否为打开状态
                td_content.eq(3).css('display','');
                td_content.eq(2).css('display','none');
            }else{
                td_content.eq(2).css('display','');
                td_content.eq(3).css('display','none');
            }
        }		
		
		
		

	}

//删除数据
   function del(id,obj){
    var result=confirm("确认要删除吗");
    if(result){
      $.ajax({
        url:"http://www.s2.com/index/JxcGoodsType/delete",
        type:"post",
        async:false,
        data:{"id":id},
        success:function(e){
          if(e.status == 2){
          	alert("删除成功")
            obj.parentNode.parentNode.remove();
            tiaoye()
            fenye();
          }else if (e.status == 1){
              alert(e.msg);
              tiaoye()
          }
        }
      })
    }
   }
//tr鼠标事件  
function trx(jpo){
	td1=$(jpo).find("td").eq(0);
	td4=$(jpo).find("td").eq(4);
	$(jpo).css('box-shadow','5px 5px 4px #E0E0E0')
	$(jpo).css('background','#F0F4F5')
	$(jpo).css('border-radius','10px') 	
	$(td1).css('border-radius','10px 0 0 10px') 	
	$(td4).css('border-radius','0 10px 10px 0') 	
	 	
}
function tre(jpo){
	td1=$(jpo).find("td").eq(0);
	td4=$(jpo).find("td").eq(4);
	$(jpo).css('box-shadow','');
	$(jpo).css('background','');
	$(jpo).css('border-radius',''); 
	$(td1).css('border-radius',''); 
	$(td4).css('border-radius',''); 

}