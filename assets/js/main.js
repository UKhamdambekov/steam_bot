$(function () { 
     //Tooltip
     $('[data-toggle="tooltip"]').tooltip({
         container: 'body'
     });
     var input = document.querySelector('input[id="search"]');
    
     input.addEventListener('keypress', function(e){
          if(e.which === 13){
               e.preventDefault();
               var value = $("#search").val();
               if(value != '') alert('Search: '+value);
       }
     });
     
 });

function brand_delete(id){//удаление производителя
    swal({
        title: "Вы действительно хотите удалить производителя?",
        text: "При удаление производитель не будет доступен",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Да, удалить!",
        cancelButtonText: "Нет, отменить!",
        closeOnConfirm: false,
        showLoaderOnConfirm: true,
    }, function () {       
        $.get("/brands/delete/"+id, {}, function (data){ //выполняем запрос get
            if(data === "true") swal("Успешно!", "Данные удалены", "success");   
        });            
        setTimeout(function () {
            document.location.reload(true);                
        }, 1000);
    });        
}


function country_delete(id){//удаление страны
     swal({
          title: "Вы действительно хотите удалить страну?",
          text: "При удаление страна не будет доступен",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Да, удалить!",
          cancelButtonText: "Нет, отменить!",
          closeOnConfirm: false,
          showLoaderOnConfirm: true,
     }, function () {       
          $.get("/country/delete/"+id, {}, function (data){
          if(data === "true") swal("Успешно!", "Данные удалены", "success");   
          });            
          setTimeout(function () {
               document.location.reload(true);                
          }, 1000);
     }); 
}


function getcategory(){
     var group = $("#group").val();
     document.getElementById('category').innerHTML=''; //очишаем html блок
     $.get("/get/category/"+group, {}, function (data){ //выполняем запрос
          $("#category").append(data);//добавляем результат в html 
          $(".ms-list").append(data);
     });
}

function add_val(id){
     var type = "#type"+id; type = $(type).val();
     var text = "#text"+id; 
     var number = "#number"+id; 
     var col = "#col"+id; 
     if(type === 'color') val = $(col).val();
     else if(type === 'number') val = $(number).val();
     else val = $(text).val();
     $.post( "/options/add", { id: id, val: val, type: type})//выполняем post запрос
     .done(function( data ) { document.location.reload(true); });//если успешно
}

function opt_type(id){
     var type = "#type"+id; type = $(type).val();
     if(type === 'color'){$("#text").hide();  $("#color").show(); $("#number").hide();}
     else if(type === 'number'){$("#text").hide();  $("#color").hide(); $("#number").show();}
     else {$("#text").show(); $("#color").hide(); $("#number").hide();}
}

function options_delete(id){//удаление опции
     swal({
          title: "Вы действительно хотите удалить опцию?",
          text: "При удаление опция не будет доступен",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Да, удалить!",
          cancelButtonText: "Нет, отменить!",
          closeOnConfirm: false,
          showLoaderOnConfirm: true,
     }, function () {       
          $.get("/options/delete/"+id, {}, function (data){
          if(data === "true") swal("Успешно!", "Данные удалены", "success");   
          });            
          setTimeout(function () {
               document.location.reload(true);                
          }, 1000);
     }); 
}

function del_opt(id){ //удаление опции продукта     
     swal({
          title: "Вы действительно хотите удалить опцию из продукта?",
          text: "При удаление опции продукта не будет доступны для добавление",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Да, удалить!",
          cancelButtonText: "Нет, отменить!",
          closeOnConfirm: false,
          showLoaderOnConfirm: true,
     }, function () {       
          $.get("/product/del_opt/"+id, {}, function (data){
               if(data === "true"){ 
                    swal("Успешно!", "Данные удалены", "success");                          
                    setTimeout(function () {
                         document.location.reload(true);                
                    }, 2000);
                }  
               else swal("Ошибка удаление!", "Возможно опция используется магазинамы", "error");   
          });      
     }); 
}

function del_photo(id, type){ //удаление опции продукта     
     swal({
          title: "Вы действительно хотите удалить фотографии из продукта?",
          text: "При удаление фотграфии продукта не будет доступны",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Да, удалить!",
          cancelButtonText: "Нет, отменить!",
          closeOnConfirm: false,
          showLoaderOnConfirm: true,
     }, function () {       
          if(type === 'product') var link = '/product/photo/delete/';
          if(type === 'option') var link = '/product/photo/option/delete/';
          $.get(link+id, {}, function (data){
               swal("Успешно!", "Данные удалены", "success");                          
               setTimeout(function () {
                    document.location.reload(true);                
               }, 1000); 
          });      
     }); 
}


function del_product(id){ //удаление продукта     
     swal({
          title: "Вы действительно хотите удалить продукт?",
          text: "При удаление продукт не будет доступны для добавление",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Да, удалить!",
          cancelButtonText: "Нет, отменить!",
          closeOnConfirm: false,
          showLoaderOnConfirm: true,
     }, function () {       
          $.get("/product/delete/"+id, {}, function (data){
               if(data === "true"){ 
                    swal("Успешно!", "Данные удалены", "success");                          
                    setTimeout(function () {
                         document.location.reload(true);                
                    }, 2000);
                }  
               else swal("Ошибка удаление!", "Возможно опция используется магазинамы", "error");   
          });      
     }); 
}

function reset(form){
     var form = "#"+form;
     $(form)[0].reset();
}

function category_add(){
    var group = $("#groups").val();
    document.getElementById('category').innerHTML='';
    $.get("/get/category/"+group, {}, function (data){
         $('#category').prop('disabled', false);
         $("#category").append(data);
    });
 }
 
 function subcategory_add(){
    var category = $("#category").val();
    document.getElementById('subcategory').innerHTML='';
    $.get("/get/subcategory/"+category, {}, function (data){
         $('#subcategory').prop('disabled', false);
         $("#subcategory").append(data);
    });
 }

 function add_opt(){
     var selected = [];
     selected.push([$("#category").val()]);
     var category = JSON.stringify(selected);
     $.post( "/options", { category: category,})
     .done(function( data ) { alert(success); });
 }

