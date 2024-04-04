@extends('layouts.default')
@section('content')

<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        <div class="row">
            <div id="notifi-box" class="hidden alert alert-success print-error-msg">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            </div>

            <div class="col-12">
                <div class="card mb-4">
                    
            @if(isset($user))
            <div class="card-header"><strong>Cập nhật thành viên: {{$user->name}} #{{$user->id}}</span></div>
                <div class="card-body">
                    <div class="example">
                        <div class="body flex-grow-1">
                            <div class="tab-content rounded-bottom">
                                <form>
                                    {{ csrf_field() }}
                                    <input value="{{$user->id}}" name="id" type="hidden">
                                    <div class="tab-pane p-3 active preview" role="tabpanel">
                                        <div class="row">
                                            <div class="mb-3 col-4">
                                                <label class="form-label" for="emailIP">Email</label>
                                                <input value="{{$user->email}}" class="form-control" name="email" id="emailIP" type="email">
                                                <p class="error_msg" id="email"></p>
                                            </div>
                                            <div class="mb-3 col-4">
                                                <label class="form-label" for="nameIP">Tên</label>
                                                <input value="{{$user->name}}" class="form-control" name="name" id="nameIP" type="text">
                                                <p class="error_msg" id="name"></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-3 col-4">
                                                <label class="form-label" for="passwwordlIP">Mật khẩu</label>
                                                <input value="{{$user->password}}" class="form-control" name="password" id="passwwordlIP" type="password">
                                                <p class="error_msg" id="password"></p>
                                            </div>
                                            <div class="mb-3 col-4">
                                                <label class="form-label" for="rePasswwordIP">Nhập lại Mật khẩu</label>
                                                <input value="{{$user->password}}" class="form-control" name="rePassword" id="rePasswwordIP" type="password">
                                            </div>
                                            
                                        </div>
                                        <div class="row">
                                        <div class="mb-3 col-4">
                                            <label class="form-label" for="qtyIP">Quyền truy cập</label>
                    
                                        
                                            <div class="form-check">
                                                <label class="form-check-label">
                    <?php 
                    $checkAll = $checkPaulo = $checkFertilizer = $checkOther = '';
                    $roles = json_decode($user->role, true);
                        // dd($user->role);
                    if ( is_array($roles)) {
                        foreach ($roles as $key => $value) {
                            if ($value == 1) {
                                $checkAll = $checkPaulo = $checkFertilizer = $checkOther = 'checked';
                                break;
                            } else if ($value == 2) {
                                $checkPaulo = 'checked';
                                break;
                            } else if ($value == 3) {
                                $checkFertilizer = 'checked';
                                break;
                            } else if ($value == 4) {
                                $checkOther = 'checked';
                                break;
                            }
                        }   
                    }
                    
                    
                    ?>
                                                <input {{$checkAll}} id="role-all" name="roles[]" type="checkbox" class="form-check-input" value="1">Tất cả
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input {{$checkPaulo}} name="roles[]" type="checkbox" class="form-check-input" value="2">Paulo
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input {{$checkFertilizer}} name="roles[]" type="checkbox" class="form-check-input" value="3">Phân bón
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input {{$checkOther}} name="roles[]" type="checkbox" class="form-check-input" value="4">Khác
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mb-3 col-4">
                                            <label class="form-label" for="qtyIP">Trạng Thái</label>
                                            <div class="form-check">
                                                <input <?=  $user->status == 1 ? 'checked' : '' ?> class="form-check-input" type="radio" name="status" value="1"
                                                    id="flexRadioDefault1">
                                                <label class="form-check-label" for="flexRadioDefault1">
                                                    Bật
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input <?=  $user->status == 0 ? 'checked' : '' ?> class="form-check-input" type="radio" name="status" value="0"
                                                    id="flexRadioDefault2" >
                                                <label  class="form-check-label" for="flexRadioDefault2">
                                                    Tắt
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mb-3 col-4">
                                            <label class="form-label">Sale</label>
                                            <div class="form-check">
                                                <input <?=  $user->is_sale == 1 ? 'checked' : '' ?>  class="form-check-input" type="radio" name="is_sale" value="1"
                                                    id="isSaleTrueIP">
                                                <label class="form-check-label" for="isSaleTrueIP">
                                                    Có
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input <?=  $user->is_sale == 0 ? 'checked' : '' ?> class="form-check-input" type="radio" name="is_sale" value="0"
                                                    id="isSaleFalseIP" >
                                                <label  class="form-check-label" for="isSaleFalseIP">
                                                    Không
                                                </label>
                                            </div>
                                        </div>
                                        </div>
                                        
                                        <div class="loader hidden">
                                            <img src="{{asset('public/images/loader.gif')}}" alt="">
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
            <div class="card-header"><strong>Thêm thành viên mới </span></div>
            <div class="card-body">
                <div class="example">
                    <div class="body flex-grow-1">
                        <div class="tab-content rounded-bottom">
                            <form>
                                {{ csrf_field() }}
                                <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-1000">
                                    <div class="row">
                                        <div class="mb-3 col-4">
                                            <label class="form-label" for="emailIP">Email</label>
                                            <input class="form-control" name="email" id="emailIP" type="email">
                                            <p class="error_msg" id="email"></p>
                                        </div>
                                        <div class="mb-3 col-4">
                                            <label class="form-label" for="nameIP">Tên</label>
                                            <input class="form-control" name="name" id="nameIP" type="text">
                                            <p class="error_msg" id="name"></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-4">
                                            <label class="form-label" for="passwwordlIP">Mật khẩu</label>
                                            <input class="form-control" name="password" id="passwwordlIP" type="password">
                                            <p class="error_msg" id="password"></p>
                                        </div>
                                        <div class="mb-3 col-4">
                                            <label class="form-label" for="rePasswwordIP">Nhập lại Mật khẩu</label>
                                            <input class="form-control" name="rePassword" id="rePasswwordIP" type="password">
                                        </div>
                                        
                                    </div>
                                    
                                <div class="row">
                                    <div class="mb-3 col-4">
                                        <label class="form-label" for="qtyIP">Quyền truy cập</label>
                
                                   
                                    <div class="form-check">
                                        <label class="form-check-label">
                                          <input id="role-all" name="roles[]" type="checkbox" class="form-check-input" value="1">Tất cả
                                        </label>
                                      </div>
                                      <div class="form-check">
                                        <label class="form-check-label">
                                          <input name="roles[]" type="checkbox" class="form-check-input" value="2">Paulo
                                        </label>
                                      </div>
                                      <div class="form-check">
                                        <label class="form-check-label">
                                          <input name="roles[]" type="checkbox" class="form-check-input" value="3">Phân bón
                                        </label>
                                      </div>
                                      <div class="form-check">
                                        <label class="form-check-label">
                                          <input name="roles[]" type="checkbox" class="form-check-input" value="4">Khác
                                        </label>
                                      </div>
                                    </div>

                                    <div class="mb-3 col-4">
                                        <label class="form-label" for="qtyIP">Sale</label>
                                        <div class="form-check">
                                            <input checked  class="form-check-input" type="radio" name="is_sale" value="1"
                                                id="flexRadioDefault1">
                                            <label class="form-check-label" for="flexRadioDefault1">
                                                Có
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_sale" value="0"
                                                id="flexRadioDefault2" >
                                            <label  class="form-check-label" for="flexRadioDefault2">
                                                Không
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
    @endif
</div>
</div>
</div>
<script>
$(document).ready(function() {
    $("#submit").click(function(e) {
        e.preventDefault();
        $('.loader').show();

        var _token      = $("input[name='_token']").val();
        var name        = $("input[name='name']").val();
        var email       = $("input[name='email']").val();
        var password    = $("input[name='password']").val();
        var rePassword  = $("input[name='rePassword']").val();
        var id          = $("input[name='id']").val();
        var status      = $("input[name='status']:checked").val();
        var is_sale     = $("input[name='is_sale']:checked").val();

        console.log('is sale',is_sale);
        let roles = [];
        $("input[name='roles[]']:checked").each(function() {
            roles.push($(this).val());
        });


        
        if (password != rePassword) {
            var err = 'Mật khẩu không khớp';
            $('#password').text(err);
            $('.loader').hide();
        } else {
            $.ajax({
                url: "{{ route('save-user') }}",
                type: 'POST',
                data: {
                    _token: _token,
                    name,
                    email,
                    password,
                    id,
                    roles,
                    status,
                    is_sale
                },
                success: function(data) {
                    console.log(data);
                    if (!$.isEmptyObject(data.error)) {
                        $("#notifi-box").removeClass('alert-success'); 
                        $("#notifi-box").addClass('alert-danger');
                        $(".error_msg").html('');
                        $("#notifi-box").show();
                        $("#notifi-box").html(data.error);
                        $("#notifi-box").slideDown('fast').delay(5000).hide(0);
                    }
                    else if ($.isEmptyObject(data.errors)) {
                        $("#notifi-box").addClass('alert-success'); 
                        $("#notifi-box").removeClass('alert-danger');
                        $(".error_msg").html('');
                        $("#notifi-box").show();
                        $("#notifi-box").html(data.success);
                        $("#notifi-box").slideDown('fast').delay(5000).hide(0);
                    } else {
                        let resp = data.errors;
                        console.log(resp);
                        for (index in resp) {
                            $("#" + index).html(resp[index]);
                        }
                    }
                    $('.loader').hide();
                }
            });
        }
        
    });

    $("input[name='roles[]']").click(function () {
        // console.log($(this).val());
        // $("input[name='roles[]']").val();
        var values = [];
        
        if ($(this).val() == 1) {
            if ($(this).is(':checked') ) {
                $("input[name='roles[]']").prop('checked', true);
            } else {
                $("input[name='roles[]']").prop('checked', false);
            }
            
        } else {
            if (!$(this).is(':checked') ) {
                console.log('unchecked');
                $("#role-all").prop('checked', false);
            } else {
                let values = [];
                $("input[name='roles[]']:checked").each(function() {
                    values.push($(this).val());
                });
                console.log(values);
                
                if (values.length == 3) {
                    $("#role-all").prop('checked', true);
                }
            }
        }
    });

});
</script>
@stop