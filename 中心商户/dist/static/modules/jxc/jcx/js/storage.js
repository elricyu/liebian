    var zongye=0;
    var types="";
    var use="";
    var bu="";
    var p=1;
    var search = $('#suo').val();
    var typeid = $('#typeid').val();
    var isuse = $('#isuse').val();
    var arr = {'search':search,'type':typeid,'is_use':isuse,'pageone':10,'page':p};
	//  数据展示
	function getstorage(){
		arr.page = p;
	    $.ajax({
	        "url": "http://www.s2.com/index/JxcOutStorage/index",
	        "type": "post",
	        "dataType": "json",
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
	                	if(e.data[i].type==1){ types="出库"};
	                	if(e.data[i].type==2){ types="入库"};
	                	if(e.data[i].is_use==1){use="禁用",bu="启用" };
	                	if(e.data[i].is_use==2){use="启用",bu="禁用"};
	                    str+='<tr onmouseover="trx(this)" onmouseout="tre(this)">'+
	                            '<td>'+(i+1)+'</td>'+
	                            '<td>'+e.data[i].name+'</td>'+
	                            '<td>'+types+'</td>'+
	                            '<td>'+use+'</td>'+
	                            '<td class="center">'+e.data[i].create_time+'</td>'+
	                            '<td class="center"><div class="xan">'+
                                    '<span class="bune ck" value="'+e.data[i].is_use+'" onclick="ck1('+e.data[i].id+',this)">'+bu+' </span>'+
	                                '<span class="bune xg" onclick="xg('+e.data[i].id+',this)">修改   </span>'+
	                                '<span class="bune sc" href="javascript:void(0)" onclick="sc('+e.data[i].id+',this)">删除</span>'+
	                            '</div></td>'+
		                    '</tr> '
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
    function fenye(){
	//	分页跳页
	    $.ajax({
	        "url": "http://www.s2.com/index/JxcOutStorage/index",
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
			        	getstorage();
			        }
			    });
		    }
	    })    	
    }
	
	

	$(function () {
	    getstorage();
		fenye();
		//	回车事件
		$(".suo").keyup(function(event){
		  if(event.keyCode ==13){
		    $("#xmjs").trigger("click");
		  }
		});
		$("#shouyes").on("click",function(){
		    p=1;
		    getstorage();
		})
		$("#shangyes").click(function () {	
		    if(p>1){
		        p--;
		        getstorage()
		    }else {
		        alert("已经到第一页了");
		        return false;
		    }
		});	
		$('#xiayes').click(function () {
	        if(p<zongye){
	            p++;
	            getstorage()
	        }else {
	            alert("已经到最后一页了");
	            return false;
	        }
	    });
		$('#tiaoyes').click(function () {
		    status=$('#jkl').val();
		    p=$('#ye').val();
		    getstorage()
		})
		$("#weiyes").click(function () {    
			p=zongye;
		    getstorage()
		});
	//  查询商品   
	    $("#xmjs").click(function () {
		    arr.search = $('#suo').val();
		    arr.type = $('#typeid').val();
		    arr.is_use = $('#isuse').val();
		    var search = $('#suo').val();
		    var typeid = $('#typeid').val();
		    var isuse = $('#isuse').val();		    
//		    var flag = true;
//	        if (isuse.length == 0) {
//	            alert('请选择状态');
//	            flag = false;
//	            return false;
//	        }
//	        if (!flag) {
//	            return false;
//	        }		    
//	        if (typeid.length == 0) {
//	            alert('请选择类型');
//	            flag = false;
//	            return false;
//	        }
//	        if (!flag) {
//	            return false;
//	        }		    
//	        if (search.length == 0) {
//	            alert('商品类别不能为空');
//	            flag = false;
//	            return false;
//	        }
//	        if (!flag) {
//	            return false;
//	        }		     
	       	 getstorage()
	    });
	  //弹出添加出入库类别窗口
	  $('.button-i').on('click', function(){
	    layer.open({
	      type: 2,
	      title: ['添加出入库类别', 'font-size:18px;font-weight:bold;color:#FFFFFF;text-align:center;background:#7D72C1;'],
	      area: ['30%', '300px'],
	      shadeClose: false, 
	      content: ['storage/app.html', 'no']
	    });
	  });
	  
	});
	
  //弹出修改出入库窗口
  	function xg(id,job){
    	layer.open({
      		type: 2,
      		title: ['修改出入库类别', 'font-size:18px;font-weight:bold;color:#FFFFFF;text-align:center;background:#7D72C1;'],
      		area: ['30%', '300px'],
      		shadeClose: false, //点击遮罩关闭
      		content: ['storage/add.html', 'no']
    	});
    	$("#stoid").val(id)
  	}	
	
//执行启用禁用	
	function ck1(id,obj){
		var abc = $(obj).attr("value");
		if(abc == 1){
			var result=confirm("确认要启用吗");
    		if(result){
			    $.ajax({
			      url:"http://www.s2.com/index/JxcOutStorage/allow",
			      type:"post",
			      async:false,
			      data:{"id":id},
			      success:function(e){
			        if(e.status == 2){
			          alert("启用成功")	
			          getstorage();
			        }else if (e.status == 1){
			            alert('启用失败');
			        }
			      }
			    }) 
			}
		}else if(abc == 2){
			var result=confirm("确认要禁用吗");
    		if(result){			
			    $.ajax({
			      url:"http://www.s2.com/index/JxcOutStorage/notallow",
			      type:"post",
			      async:false,
			      data:{"id":id},
			      success:function(e){  	
			        if(e.status == 2){
			        	alert("禁用成功")
			            getstorage();
			        }else if (e.status == 1){
			            alert('禁用失败');
			            getstorage();
			        }
			      }
			    })
			 }   
		}
      
	}
	
//删除数据
   function sc(id,obj){
    var result=confirm("确认要删除吗");
    if(result){
      $.ajax({
        url:"http://www.s2.com/index/JxcOutStorage/delete",
        type:"post",
        async:false,
        data:{"id":id},
        success:function(e){
          if(e.status == 2){
          	alert('删除成功');
            obj.parentNode.parentNode.remove();
            getstorage();
            fenye();
          }else if (e.status == 1){
              alert(e.msg)
              getstorage();
          }
        }
      })
    }
   }
//tr鼠标事件  
function trx(jpo){
	td1=$(jpo).find("td").eq(0);
	td5=$(jpo).find("td").eq(5);
	$(jpo).css('box-shadow','5px 5px 4px #E0E0E0')
	$(jpo).css('background','#F0F4F5')
	$(td1).css('border-radius','10px 0 0 10px') 	
	$(td5).css('border-radius','0 10px 10px 0') 	
	 	
}
function tre(jpo){
	td1=$(jpo).find("td").eq(0);
	td5=$(jpo).find("td").eq(5);
	$(jpo).css('box-shadow','');
	$(jpo).css('background','');
	$(td1).css('border-radius',''); 
	$(td5).css('border-radius',''); 

}