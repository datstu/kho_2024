@extends('layouts.default')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
<style>
    #laravel-notify .notify {
      z-index: 9999;
  }
</style>

<?php $id = $name = $member = $membersStr = '';
    $status = 1;
    $members = $srcs = $products = [];
    if (isset($group)) {
        $id = $group->id;
        $name = $group->name;
        $status = $group->status;
        $members = $group->sales->pluck('id_user')->toArray();
        // dd($members);
        $srcs = $group->srcs->pluck('id')->toArray();
        $products = $group->products->pluck('id_product')->toArray();
    }

?>
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        <div class="row">
            <div id="notifi-box" class="hidden alert alert-success print-error-msg">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            </div>

            <div class="col-12">
                <div class="card mb-4">
                    
            <div class="card-header"><strong>Lưu thông tin nhóm </span></div>
            <div class="card-body">
                <div class="body flex-grow-1">
                    <div class="tab-content rounded-bottom">
                        <form method="POST" action="{{route('save-group')}}">
                            <input type="hidden" name="id" value="{{$id}}">
                            {{ csrf_field() }}
                            <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-1000">
                                <div class="row">
                                    <div class="mb-3 col-4">
                                        <label class="form-label" for="nameIP">Tên nhóm</label>
                                        <input value="{{$name}}" class="form-control" name="name" id="nameIP" type="text" required>
                                        <p class="error_msg" id="name"></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 form-group">
                                        <label for="like-color">Chọn thành viên</label>
                                        <select required name="member[]" id="list-sale" class="custom-select" multiple>
                                            
                                            @foreach($listSale as $sale) 
                                                <option <?= (in_array($sale->id, $members)) ? 'selected' : ''; ?> value="{{$sale->id}}">{{$sale->real_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 form-group">
                                        <label for="like-color">Chọn nguồn data</label>
                                        <select required name="src[]" id="list-src" class="custom-select" multiple>
                                            
                                            @foreach($listSrc as $src) 
                                                <option <?= (in_array($src->id, $srcs)) ? 'selected' : ''; ?> value="{{$src->id}}">{{$src->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 form-group">
                                        <label for="like-color">Chọn sản phẩm</label>
                                        <select required name="product[]" id="list-product" class="custom-select" multiple>
                                             
                                            @foreach($listProduct as $product) 
                                                <option <?= (in_array($product->id, $products)) ? 'selected' : ''; ?> value="{{$product->id}}">{{$product->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3 col-2">
                                        <label class="form-label" for="qtyIP">Trạng Thái</label>
                                        <div class="form-check">
                                            <input <?= ($status) ? 'checked' : '';?> class="form-check-input" type="radio" name="status" value="1"
                                                id="flexRadioDefault1">
                                            <label class="form-check-label" for="flexRadioDefault1">
                                                Bật
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input <?= (!$status) ? 'checked' : '';?> class="form-check-input" type="radio" name="status" value="0"
                                                id="flexRadioDefault2" >
                                            <label  class="form-check-label" for="flexRadioDefault2">
                                                Tắt
                                            </label>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="loader hidden">
                                    <img src="{{asset('public/images/loader.gif')}}" alt="">
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
</div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
<script>
    $(function() {
        $('#list-sale').select2();
        $('#list-product').select2();
        $('#list-src').select2();
        
    });
    if ($('.flex.items-start').length) {
        console.log('tadada')
        
        setTimeout(function() { 
            $('.notify.fixed').hide();
        }, 3000);
    }
</script>
@stop