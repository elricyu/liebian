$(function () {
    var zongye=0;
    var p=1;
    var search = $('#suo').val();
    var times = $('#times').val();
    var timee = $('#timee').val();
    var arr = {'search':search,'time_s':times,'time_e':timee,'page':p,'pageone':10};
    systemlog();
	//  回车事件
	$(".suo").keyup(function(event){
	  if(event.keyCode ==13){
	    $("#xmjs").trigger("click");
	  }
	});    
    
	//	分页跳页
	    $.ajax({
	        "url": "http://www.s2.com/index/JxcSupplier/findLog",
	        "type": "post",
	        "dataType": "json",
	        "data": {map:arr},
		    success:function(e){		    	
		    	pps=e.map.pagetotal;
			    $.jqPaginator('#pagination2', {
			        totalPages: pps,
			        visiblePages: 5,
			        currentPage: p,
			        first: '<li class="homepage"> << </li>',
			        last: '<li class="last"> >> </li>',
			        prev: '<li class="previouspage"> < </li>',
			        next: '<li class="nextpage"> > </li>',
			        page: '<li class="pagein">{{page}}</li>',
			        onPageChange: function (num, type) {
			        	p=num;
			        	systemlog();
			        }
			    });
		    }
	    })
	$("#shouyes").on("click",function(){
	    p=1;
	    systemlog();
	})
	$("#shangyes").click(function () {	
	    if(p>1){
	        p--;
	        systemlog()
	    }else {
	        alert("已经到第一页了");
	        return false;
	    }
	});	
	$('#xiayes').click(function () {
        if(p<zongye){
            p++;
            systemlog();
        }else {
            alert("已经到最后一页了");
            return false;
        }
    });
	$('#tiaoyes').click(function () {
	    status=$('#jkl').val();
	    p=$('#ye').val();
	    systemlog()
	})
	$("#weiyes").click(function () {    
		p=zongye;
	    systemlog()
	});
//  查询日志
    $("#xmjs").click(function () {  	
	    arr.search = $('#suo').val();
	    arr.time_s = $('#times').val();
	    arr.time_e = $('#timee').val();
	    console.log(times);
        systemlog();
    });
    
//  数据展示
	function systemlog(){
	    arr.page = p;
	    $.ajax({
	        "url": "http://www.s2.com/index/JxcSupplier/findLog",
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
	                    str+='<tr onmouseover="trx(this)" onmouseout="tre(this)">' +
	                        '<td>'+(i+1)+'</td>'+
	                        '<td>'+e.data[i].create_time+'</td>'+
	                        '<td class="center">'+e.data[i].user_name+'</td>'+
	                        '<td class="center">'+e.data[i].ip+'</td>'+
	                        '<td class="center">'+e.data[i].content+'</td>'+
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
//日期选择
	lay('.laydate').each(function(){
	  laydate.render({
	    elem: this,
	    trigger: 'click',
		theme: '#7C71BF'
	  });
	}); 


});


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
            systemlog();
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
	td4=$(jpo).find("td").eq(4);
	$(jpo).css('box-shadow','5px 5px 4px #E0E0E0')
	$(jpo).css('background','#F0F4F5')
	$(td1).css('border-radius','10px 0 0 10px') 	
	$(td4).css('border-radius','0 10px 10px 0') 	
	 	
}
function tre(jpo){
	td1=$(jpo).find("td").eq(0);
	td4=$(jpo).find("td").eq(4);
	$(jpo).css('box-shadow','');
	$(jpo).css('background','');
	$(td1).css('border-radius',''); 
	$(td4).css('border-radius',''); 

}