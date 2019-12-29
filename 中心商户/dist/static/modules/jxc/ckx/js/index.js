var zongye=0;
var p=1;
var times = $('#times').val();
var timee = $('#timee').val();
var person = $('#person').val();
var arr = {'time_s':times,'time_e':timee,'person':person,'pageone':10,'page':p}
//  数据展示**********************
	function getrkx(){

	    arr.page = p;
	    $.ajax({
	        "url": "http://www.s2.com/index/JxcOrder/out",
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
	                    str+='<tr onmouseover="trx(this)" onmouseout="tre(this)">'+
                            '<td>'+(i+1)+'</td>'+
                            '<td>'+e.data[i].order_num+'</td>'+
                            '<td>'+e.data[i].date+'</td>'+
                            '<td class="center">'+e.data[i].create_time+'</td>'+
                            '<td class="center">'+e.data[i].sum_price+'</td>'+
                            '<td class="center">'+e.data[i].storage_name+'</td>'+
                            '<td>'+e.data[i].person+'</td>'+
                            '<td class="center"><div class="xan">'+
                                '<span class="btn-ck" onclick="doshow('+e.data[i].id+',this)">查看 </span> '+
                                '<span class="btn-zf" href="javascript:void(0)" onclick="del('+e.data[i].id+',this)">作废</span>'+
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
	function getsu(){
	    $.ajax({
	        "url": "http://www.s2.com/index/JxcOrder/out",
	        "type": "post",
	        "dataType": "json",
	        "data": {map:arr},
	        success: function (e) {	
	        	var str10="";
				var str20="";
				if(e.users){
					for(var i=0;i<e.users.length;i++){
			    		str10+= "<option value='"+e.users[i].user_id+"'>"+e.users[i].username+"</option>";
			    }
					$('#person').append(str10);
				}
	        }
	      })
	}
	function fenye(){
	//	分页跳页
	    $.ajax({
	        "url": "http://www.s2.com/index/JxcOrder/out",
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
			        	getrkx();
			        }
			    });
		    }
	    })	
	}


	$(function () {
		getsu();
	    getrkx();
		fenye();	
		$("#shouyes").on("click",function(){
		    p=1;
		    getrkx();
		})
		$("#shangyes").click(function () {	
		    if(p>1){
		        p--;
		        getrkx()
		    }else {
		        alert("已经到第一页了");
		        return false;
		    }
		});	
		$('#xiayes').click(function () {
	        if(p<zongye){
	            p++;
	            getrkx();
	        }else {
	            alert("已经到最后一页了");
	            return false;
	        }
	    });
		$('#tiaoyes').click(function () {
		    status=$('#jkl').val();
		    p=$('#ye').val();
		    getrkx()
		})
		$("#weiyes").click(function () {    
			p=zongye;
		    getrkx()
		});
	//  查询商品   
	    $(".form-e").click(function () {
		    arr.time_s = $('#times').val();
		    arr.time_e = $('#timee').val();
		    arr.person = $('#person').val();
	        getrkx();
	    });
	//弹出添加入库单
	  $('.button-i').on('click', function(){
	    layer.open({
	      type: 2,
	      anim: -1,
	      isOutAnim: false,
	      title: ['添加出库单', 'font-size:18px;font-weight:bold;color:#FFFFFF;text-align:center;background:#7D72C1;'],
	      area: ['85%', '500px'],
	      shadeClose: false, //点击遮罩关闭
	      content: ['addout.html'],
	      isOutAnim: false
	    });
	  }); 
	//日期选择
		lay('.laydate').each(function(){
		  laydate.render({
		    elem: this,
		    trigger: 'click',
			theme: '#7C71BF'
		  });
		}); 	  
	  
	  
	})
		
  //弹出查看入库单
   function doshow(id,jbo){
     layer.open({
       type: 2,
       title: ['查看出库订单', 'font-size:18px;font-weight:bold;color:#FFFFFF;text-align:center;background:#7D72C1;'],
       area: ['75%', '450px'],
       shadeClose: false, //点击遮罩关闭
       content: ['showout.html'],
       isOutAnim: false
     });
     $("#chaid").val(id);
   }
   function del(id,obj){
    var result=confirm("确认要作废吗");
    if(result){
      $.ajax({
        "url":"http://www.s2.com/index/JxcOrder/deleteout",
        "type":"post",
        "dataType": "json",
        "data":{"id":id},
        success:function(e){
          if(e.status == 2){
          	alert("订单作废成功")
            obj.parentNode.parentNode.remove();
            getrkx();
            fenye();
          }else if (e.status == 1){
              alert(e.msg)
          }
        },
        error:function(d){
//      	console.log(d);
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
	$(td1).css('border-radius','15px 0 0 15px') 	
	$(td8).css('border-radius','0 15px 15px 0') 	
	 	
}
function tre(jpo){
	td1=$(jpo).find("td").eq(0);
	td8=$(jpo).find("td").eq(8);
	$(jpo).css('box-shadow','');
	$(jpo).css('background','');
	$(td1).css('border-radius',''); 
	$(td8).css('border-radius',''); 

}