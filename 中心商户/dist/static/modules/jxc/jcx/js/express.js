	var zongye="";
	var p=1;
	var str='';
	var search = $('#suo').val();
  //数据展示
	function express() {
	    $.ajax({
	        "type": "post",
	        "url": "http://www.s2.com/index/JxcExpress/index",
	        "dataType": "json",
	        "data": {page:p,pageone:10,search:search},
	        success: function (e) {
	        	var str='';
	        	var str2='';
	            if(e.data==null){
	                str = "<tr><td colspan='11'>没有查找到数据!</td></tr>";
	                str2='<option value="0">0</option>'
	                $('#nums').html("");
	            }else{        	
	        	zongye=e.map.pagetotal;
	            $('#nums').html("共有"+e.map.count+"条记录&nbsp;&nbsp;当前"+e.map.page+"页/共"+e.map.pagetotal+"页");			
	            for(var i=0;i<e.data.length;i++){
	                str+='<tr ondblclick="showupdate(this,1)" onmouseover="trx(this)" onmouseout="tre(this)">' +
	                    '<td>'+(i+1)+'</td>' +
	                    '<td>'+e.data[i].express_name+'</td>' +
	                    '<td style="position: relative;	display:none;">'+
		                    '<input value="'+e.data[i].express_name+'" name="express_name" class="inp fl" required="" type="text">'+	                         	                    
	                    	'<span class="bcxg fl" onclick="taj('+e.data[i].id+',this)">保存 </span>'+                        	
                         '</td>'+
	                    '<td>'+e.data[i].create_time+'</td>' +
	                    '<td><div class="span"><span class="xiugai fl" onclick="showupdate(this,2)">修改 </span> '+
	                    '<span href="javascript:void(0);" class="sanchu fr" onclick="del('+e.data[i].id+',this)">删除</span></div>'+
	                    '</td>' +	                    	                    
	                    '</tr>'+
                        '<tr style="display:none;box-shadow:5px 5px 4px #E0E0E0">'+
                            '<td style="text-align: right;vertical-align: middle;"><span style="color:red;margin: 4px;">*</span>'+
                            '</td>'+
                            '<td><input value="'+e.data[i].express_name+'" name="express_name" class="form-control inp" required="" type="text">'+
                            '</td>'+
                            '<td><span class="bcxg" onclick="taj('+e.data[i].id+',this)">保存 </span>'+
                            '</td>'+
                            '<td>'+' '+'</td>'+
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
	        "url": "http://www.s2.com/index/JxcExpress/index",
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
			        	express();
			        }
			    });
		    }
	    })	
	}



$(function  () {
	express();
	fenye();
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

	$("#shouyes").on("click",function(){
	    p=1;
	    express();
	})
	$("#shangyes").click(function () {	
	    if(p>1){
	        p--;
	        express()
	    }else {
	        alert("已经到第一页了");
	        return false;
	    }
	});
	
	$('#xiayes').click(function () {
	    if(p<zongye){
	        p++;
	        express()
	    }else {
	        alert("已经到最后一页了");
	        return false;
	    }
	    });
	$('#tiaoyes').click(function () {
	    status=$('#jkl').val();
	    p=$('#ye').val();
	    express()
	})
	$("#weiyes").click(function () {    
		p=zongye;
	    express()
	}); 
	//查询物流
	    $("#xmjs").click(function () {
	        search = $('#suo').val();

	        express();
	    });
	//添加数据
	 $("#button-name").click(function () { 
	   	var name=$("#button-a").val()
	   	var flag = true;
       	if (name.length == 0) {
            alert('物流公司不能为空');
            flag = false;
            return false;
        }
        if (!flag) {
            return false;
        }
	    var result=confirm("确认要添加么吗");
	    if(result){    	 
	      $.ajax({
	        url:"http://www.s2.com/index/JxcExpress/store",
	        type:"post",
	        async:false,
	        data:{express_name:name},
	        success:function(e){
	        	if(e.status==2){
	        		alert("添加成功");
	        		$("#button-a").val("");
	        		express();
	        		fenye();
	        	}
	          	if(e.status==1){
					alert(e.msg);
					$("#button-a").val("");
	          	}	        	
	        	
	        }
	      })
	    }
	});  

});

//修改物流公司名称
	var a="";
	function taj(id,obj) {
		var td = $(obj).parents("tr").children("td");
		var a  =$(td.eq(2)).find("input").val();
		var flag = true;
       	if (a.length == 0) {
            alert('物流公司名称不能为空');
            express();
            flag = false;
            return false;
        }
        if (!flag) {
            return false;
        }
	    var result=confirm("确认要修改吗？");
		if(result){
		  $.ajax({
		    url:"http://www.s2.com/index/JxcExpress/update",
		      type:"post",
	          async:false,
	          data:{id:id,express_name:a},
	          success:function(e){
	          	if(e.status==2){
	          		alert("修改成功")
					express()
	          	}else if(e.status==1){
	          		alert(e.msg)
	          		express()
	          	}
					
		      }
		    })
		} 		 		
	}
 //删除数据
   function del(id,obj){
    var result=confirm("确认要删除吗？");
	if(result){
	  $.ajax({
	    url:"http://www.s2.com/index/JxcExpress/delete",
	        type:"post",
            async:false,
            data:{id:id},
            success:function(e){
              if(e.status == 2){
              	alert("删除成功")
                obj.parentNode.parentNode.remove();
                express();
                fenye();
              }else if(e.status==1){
              	alert(e.msg)
              }
	        }
	      })
	    }
	}
   //tr鼠标事件  
function trx(jpo){
	td1=$(jpo).find("td").eq(0);
	td3=$(jpo).find("td").eq(3);
	$(jpo).css('box-shadow','5px 5px 4px #E0E0E0')
	$(jpo).css('background','#F0F4F5')
	$(td1).css('border-radius','10px 0 0 10px') 	
	$(td3).css('border-radius','0 10px 10px 0') 	
	 	
}
function tre(jpo){
	td1=$(jpo).find("td").eq(0);
	td3=$(jpo).find("td").eq(3);
	$(jpo).css('box-shadow','');
	$(jpo).css('background','');
	$(td1).css('border-radius',''); 
	$(td3).css('border-radius',''); 

}
		
//展开修改类别名称
	function showupdate(obj,tag){
		var td_content = $(obj).parents("tr").children("td"); 
		var content = td_content.eq(1).css('display') 
		var content2 = td_content.eq(2).css('display')
		if (tag == 1) { //判断是双击还是点击修改
			var content2 = td_content.eq(2).css('display')   
            if (content2 == 'none'){ //判断当前是否为打开状态
                td_content.eq(2).css('display','');
                td_content.eq(1).css('display','none');
            }else{
                td_content.eq(1).css('display','');
                td_content.eq(2).css('display','none');
            }
        }else{
			var content2 = td_content.eq(2).css('display') 
            if (content2 == 'none'){ //判断当前是否为打开状态
                td_content.eq(2).css('display','');
                td_content.eq(1).css('display','none');
            }else{
                td_content.eq(1).css('display','');
                td_content.eq(2).css('display','none');
            }
        }
    }