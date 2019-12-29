$(function () {
    var zongye=0;
    var p=1;
    var search = $('#suo').val();
    var typeid = $('#typeid').val();
    var supplierid = $('#supplierid').val();    
    var arr = {'search':search,'typeid':typeid,'supplierid':supplierid,'page':p,'pageone':4}
    getgoodslist();
	getsu()
	fenye();
	//回车事件
	$(".suos").keyup(function(event){
	  if(event.keyCode ==13){
	    $("#xmjs").trigger("click");
	  }
	});	
	
	//	分页跳页
	function fenye(){
	    $.ajax({
	        "url": "http://www.s2.com/index/JxcGoods/goodslist",
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
			        	  getgoodslist();
			        }
			    });
		    }
	    })		
	}


		function getsu(){
		    $.ajax({
		        "url": "http://www.s2.com/index/JxcGoods/goodslist",
		        "type": "post",
		        "dataType": "json",
		        "data": {map:arr},
		        success: function (e) {	
		        	var str10="";
					var str20="";		    	
			    	for(var i=0;i<e.suppliers.length;i++){
				    	str10 += "<option value='"+e.suppliers[i].id+"'>"+e.suppliers[i].supplier_name+"</option>";
				    }
				    $('#supplierid').append(str10);
				    for(var i=0;i<e.good_types.length;i++){
				    	str20 += "<option value='"+e.good_types[i].id+"'>"+e.good_types[i].type_name+"</option>";
				    }						    	
				    $('#typeid').append(str20);						
		        }
		      })
	    }

	$("#shouyes").on("click",function(){
	    p=1;
	    getgoodslist();
	})
	$("#shangyes").click(function () {	
	    if(p>1){
	        p--;
	        getgoodslist()
	    }else {
	        alert("已经到第一页了");
	        return false;
	    }
	});	
	$('#xiayes').click(function () {
        if(p<zongye){
            p++;
            getgoodslist();
        }else {
            alert("已经到最后一页了");
            return false;
        }
    });
	$('#tiaoyes').click(function () {
	    status=$('#jkl').val();
	    p=$('#ye').val();
	    getgoodslist()
	})
	$("#weiyes").click(function () {    
		p=zongye;
	    getgoodslist()
	});
//  查询商品   
    $("#xmjs").click(function () {
	    arr.search = $('#suo').val();
	    arr.typeid = $('#typeid').val();
	    arr.supplierid = $('#supplierid').val(); 	    
        getgoodslist();
    });
  
//  数据展示
	function getgoodslist(){
	    arr.page = p;
	    $("#selall").prop("checked","");
	    $.ajax({
	        "url": "http://www.s2.com/index/JxcGoods/goodslist",
	        "type": "post",
	        "dataType": "json",
	        "data": {map:arr},
	        success: function (e) {
//	            console.log(e);
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
	                    str+='<volist name="goods" id="item" key="k">'+
			                    '<tr class="list_tr">'+
			                        '<td><input type="checkbox" name="selgoods[]" class="good_id" value="'+e.data[i].id+'"></td>'+
			                        '<td class="good_num">'+e.data[i].good_num+'</td>'+
			                        '<td class="good_name">'+e.data[i].good_name+'</td>'+
			                        '<td>'+
			                            '<div style="position: relative;">'+
			                                '<img  height="26px" class="mini_imgpath" onmouseover="img_x('+e.data[i].id+',this)" onmouseout="img_y('+e.data[i].id+',this)" src="'+e.data[i].mini_imgpath+'"  onerror=this.src="img/default2.jpg" alt="商品图片">'+
			                                '<div style="left:70px;top:1px;z-index: 2;display:none;position: absolute;border: 1px solid #AAAAAA;border-radius:3px;">'+
			                                	'<img style="width: 120px;height: 120px;border-radius:3px;" class="imgpath" src="'+e.data[i].imgpath+'"  onerror=this.src="img/default2.jpg" alt="商品图片">'+
			                                '</div>'+
			                            '</div>'+
			                        '</td>'+
			                        '<td class="spec">'+e.data[i].spec+'</td>'+
			                        '<td class="unit">'+e.data[i].unit+'</td>'+
			                        '<td class="real_price">'+e.data[i].real_price+'</td>'+
			                        '<td class="purchase_price">'+e.data[i].purchase_price+'</td>'+
			                        '<td class="center">'+e.data[i].prev_purprice+'</td>'+
			                        '<td class="stock">'+e.data[i].stock+'</td>'+
			                    '</tr>'+
			                '</volist>'	                    
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
 })




//弹出图片
function img_x(id,jop){
	$(jop).next().css('display','')
}
function img_y(id,jop){
	$(jop).next().css('display','none')
}

    function load() {
        // 设置滚动条
        var allDiv = document.getElementById("allDiv");
        allDiv.style.overflow = "auto";
    }
    //商品全选反选功能
    $(function(){
        $('#selall').change(function(){
            var status = $(this).is(':checked');
            var checkboxs = $(":checkbox");
            if (status){
                for(i=0;i<checkboxs.length;i++){
                    checkboxs[i].checked=true;
                };
            }else{
                for(i=0;i<checkboxs.length;i++){
                    checkboxs[i].checked=false;
                };
            }
        });
    })
    function sendgoods(tag){
    	
        var selectedData = [];
        $(":checkbox:checked").each(function(){
            var type = $(this).attr('class');
            if(type == 'all'){
                return true;
            }
            var tablerow = $(this).parents("tr");
            var good_id = tablerow.find('.good_id').val();
            var good_num = tablerow.find(".good_num").html();
            var good_name= tablerow.find(".good_name").html();
            var mini_imgpath= tablerow.find(".mini_imgpath").attr('src');
            var imgpath= tablerow.find(".imgpath").attr('src');
            var spec= tablerow.find(".spec").html();
            var unit= tablerow.find(".unit").html();
            var price= tablerow.find(".purchase_price").html();
            var stock = tablerow.find(".stock").html();
            selectedData.push({good_id:good_id,good_num:good_num,good_name:good_name,mini_imgpath:mini_imgpath,imgpath:imgpath,spec:spec,unit:unit,price:price,stock:stock});

        });
        parent.getData(selectedData);
        if (tag == 1) {
            closelist();
        }
    }
    function closelist() {
        parent.cancle();
        $(".good_id").prop("checked","");
        $("#selall").prop("checked","");
    }
//  window.onload = load;