@extends('layouts.default')
@section('content')
<div class="body flex-grow-1 px-3">
        <div class="container-lg">
          <div class="row">
            <div id="notifi-box" class="hidden alert alert-success print-error-msg" >
              <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            </div>
          
            <div class="col-12">
              <div class="card mb-4">
                <div class="card-header"><strong>Thêm sản phẩm  mới </span></div>
            @if(isset($category))
                <div class="card-body">
                  <div class="example">
                    <div class="body flex-grow-1">
                      <div class="tab-content rounded-bottom">
                      <form>
                        {{ csrf_field() }}
                        <input value="{{$category->id}}" name="id" type="hidden">
                        <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-1000">
                          <div class="row">
                          <div class="mb-3 col-8">
                            <label class="form-label" for="nameIP">Tên danh mục</label>
                            <input class="form-control" value="{{$category->name}}" name="name" id="nameIP" type="text">
                            <p class="error_msg" id="name"></p>
                          </div>
                          </div>
                          <button id="submit" class="btn btn-primary">Cập nhật</button>
                        </div>
                      </form>
                      </div>
                    </div>
                  </div>    
                </div>
              </div>
            </div>
            @else
                <div class="card-body">
                  <div class="example">
                    <div class="body flex-grow-1">
                      <div class="tab-content rounded-bottom">
                      <form>
                        {{ csrf_field() }}
                        <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-1000">
                          <div class="row">
                          <div class="mb-3 col-8">
                            <label class="form-label" for="nameIP">Tên danh mục</label>
                            <input required class="form-control" name="name" id="nameIP" type="text">
                            <p class="error_msg" id="name"></p>
                          </div>
                          </div>
                          <button id="submit" class="btn btn-primary">Tạo</button>
                        </div>
                      </form>
                      </div>
                    </div>
                  </div>    
                </div>
              </div>
            </div>
            @endif
          </div>
        </div>
      </div>
<script>
$(document).ready(function() {
  $("#submit").click(function(e){
    e.preventDefault();
  
      var _token = $("input[name='_token']").val();
      var name = $("input[name='name']").val();
      // var price = $("input[name='price']").val();
      // var qty = $("input[name='qty']").val();
      var id = $("input[name='id']").val();
      if (name == '') {
        $("#name").html('Chưa nhập tên danh mục.');
      } else {
        $.ajax({
            url: "{{ route('save-category') }}",
            type:'POST',
            data: {_token:_token, name:name, id},
            success: function(data) {
              console.log(data);
                if($.isEmptyObject(data.errors)){
                    $(".error_msg").html('');
                    $("#notifi-box").show();
                    $("#notifi-box").html(data.success);
                    $("#notifi-box").slideDown('fast').delay(5000).hide(0);
                }else{
                    let resp = data.errors;
                    for (index in resp) {
                      console.log(index);
                      console.log(resp[index]);
                        $("#" + index).html(resp[index]);
                    }
                }
            }
        });
    }
  
  }); 
});
   </script> 
@stop