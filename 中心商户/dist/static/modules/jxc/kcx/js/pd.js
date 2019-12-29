    var zongye=0;
    var p=1;
    var pd ;
    var search = $('#suo').val();
    var timee = $('#timee').val();
    var times = $('#times').val();
    var typeid = $('#typeid').val();
    var arr = {'time_e':timee,'time_s':times,'person':typeid,'type':pd,'pageone':10,'page':p};
	//  数据展示
		function getpd(){
		    arr.page = p;
		    $.ajax({
		        "url": "http://www.s2.com/index/JxcOrder/pd",
		        "type": "post",
		        "dataType": "json",
		        "data": {map:arr},
		        success: function (e) {
		            var str='';
		            var str2='';
		            var str10="";
		            if(!e.data){
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
	                            '<td class="center">'+e.data[i].date+'</td>'+
	                            '<td class="center">'+e.data[i].create_time+'</td>'+
	                            '<td>'+e.data[i].person+'</td>'+
	                            '<td class="center"> <div class="xan">'+
	                                '<span class="btn-ck" onclick="doshow('+e.data[i].id+',this)">查看    </span> '+
	                                '<span class="btn-zf" href="javascript:void(0)" onclick="del('+e.data[i].id+',this)">   作废</span>'+
	                            '</div></td>'+
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
		        "url":"http://www.s2.com/index/JxcOrder/pd",
		        "type":"post",
		        "dataType":"json",
		        "data": {map:arr},
		        success: function (e) {	
		            var str10="";	        	
			    	for(var i=0;i<e.users.length;i++){
			    		str10 += "<option value='"+e.users[i].user_id+"'>"+e.users[i].username+"</option>";
			    	}
			    	$('#typeid').append(str10);			
		        }
		      })
		} 
		function fenye(){
		//	分页跳页
		    $.ajax({
		        "url": "http://www.s2.com/index/JxcOrder/pd",
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
		}
				
		
	$(function(){
		getsu();
	    getpd();
		fenye()	
		//回车事件
		$("#typeid").keyup(function(event){
		  if(event.keyCode ==13){
		    $("#xmjs").trigger("click");
		  }
		});			
		
		
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
		    arr.time_e = $('#timee').val();
		    arr.time_s = $('#times').val();
		    arr.person = $('#typeid').val();
//		    var typeid = $('#typeid').val();
//		    var timee = $('#timee').val();
//		    var times = $('#times').val();		    
//	        var flag = true;
//	        if (timee.length == 0) {
//	            alert('请输入开始日期');
//	            flag = false;
//	            return false;	
//	        }
//	        if (!flag) {
//	            return false;
//	        }
//	        if (times.length == 0) {
//	            alert('请输入结束日期');
//	            flag = false;
//	            return false;	
//	        }
//	        if (!flag) {
//	            return false;
//	        }
//	        if (typeid.length == 0) {
//	            alert('请选择盘点人');
//	            flag = false;
//	            return false;	
//	        }
//	        if (!flag) {
//	            return false;
//	        }		    
	        getpd();
	    });	  
	//日期选择
		lay('.laydate').each(function(){
		  laydate.render({
		    elem: this,	    
		    trigger: 'click',
			theme: '#7C71BF'
		  });
		});
		
	//弹出添加盘点单 
	  $("#button").click(function () {
	    layer.open({
	      type: 2,
	      title: ['添加盘点单', 'font-size:18px;font-weight:bold;color:#FFFFFF;text-align:center;background:#7D72C1;'],
	      area: ['90%', '480px'],
	      shadeClose: false, //点击遮罩关闭
	      content:['addpd.html']
	    });    
	  })
		
	})

	//弹出查看盘点单 
  	function doshow(id,jop){  
    	layer.open({
	      	type: 2,
	      	title: ['查看盘点单', 'font-size:18px;font-weight:bold;color:#FFFFFF;text-align:center;background:#7D72C1;'],
	      	area: ['75%', '480px'],
	      	shadeClose: false, //点击遮罩关闭
	      	content:['chakanpd.html']
    	});
    	$("#chakanpd").val(id)
  	}
  	function del(id,jog){
        var result=confirm("确认要作废吗");
        if(result){
            $.ajax({
                url:"http://www.s2.com/index/JxcOrder/deletepd",
                type:"post",
                async:false,
                data:{"id":id},
                success:function(e){
		          if(e.status == 2){
		          	alert("订单作废成功");
		            getpd();
		            fenye();
		          }else if (e.status == 1){
		            alert(e.msg);
		          }
                }
            })
        }  		
  	}
//tr鼠标事件  
function trx(jpo){
	td1=$(jpo).find("td").eq(0);
	td4=$(jpo).find("td").eq(5);
	$(jpo).css('box-shadow','5px 5px 4px #E0E0E0')
	$(jpo).css('background','#F0F4F5')
	$(td1).css('border-radius','10px 0 0 10px') 	
	$(td4).css('border-radius','0 10px 10px 0') 	
	 	
}
function tre(jpo){
	td1=$(jpo).find("td").eq(0);
	td4=$(jpo).find("td").eq(5);
	$(jpo).css('box-shadow','');
	$(jpo).css('background','');
	$(td1).css('border-radius',''); 
	$(td4).css('border-radius',''); 

}