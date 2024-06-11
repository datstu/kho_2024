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
                                                <label class="form-label" for="nameIP">Tên đăng nhập</label>
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
                                                <label class="form-label" for="realNameIP">Tên</label>
                                                <input value="{{$user->real_name}}" class="form-control" name="real_name" id="realNameIP" type="text">
                                                <p class="error_msg" id="real_name"></p>
                                            </div>
                                        <div class="mb-3 col-4">
                                            <label class="form-label" for="qtyIP">Quyền truy cập</label>
                                            <div class="form-check">
                                                <label class="form-check-label">
                    <?php 
                    $checkAll = $checkPaulo = $checkFertilizer = $checkLeadSale = $other = '';
                    $roles = json_decode($user->role, true);
                        // dd($user->role);
                    if ( is_array($roles)) {
                        // dd($roles);
                        foreach ($roles as $key => $value) {
                            if ($value == 1) {
                                $checkAll = $checkPaulo = $checkFertilizer = $checkOther = 'checked';
                                break;
                            } 
                            if ($value == 2) {
                                $checkPaulo = 'checked';
                                // break;
                            } 
                            if ($value == 3) {
                                $checkFertilizer = 'checked';
                                // break;
                            } 
                            if ($value == 4) {
                                $checkLeadSale = 'checked';
                                // break;
                            }
                            if ($value == 5) {
                                $other = 'checked';
                                // break;
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
                                                <input {{$checkLeadSale}} name="roles[]" type="checkbox" class="form-check-input" value="4">Lead Sale
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input {{$other}} name="roles[]" type="checkbox" class="form-check-input" value="5">Khác
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
                                        <div class="mb-3 col-4">
                                            <label class="form-label">Chia data</label>
                                            <div class="form-check">
                                                <input <?=  $user->is_receive_data == 1 ? 'checked' : '' ?>  class="form-check-input" type="radio" name="is_receive_data" value="1"
                                                    id="isReceiveTrueIP">
                                                <label class="form-check-label" for="isReceiveTrueIP">
                                                    Có
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input <?=  $user->is_receive_data == 0 ? 'checked' : '' ?> class="form-check-input" type="radio" name="is_receive_data" value="0"
                                                    id="isReceiveFalseIP" >
                                                <label  class="form-check-label" for="isReceiveFalseIP">
                                                    Không
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mb-3 col-4">
                                            <label class="form-label">CSKH</label>
                                            <div class="form-check">
                                                <input <?=  $user->is_CSKH == 1 ? 'checked' : '' ?>  class="form-check-input" type="radio" name="is_CSKH" value="1"
                                                    id="is_CSKHTrueIP">
                                                <label class="form-check-label" for="is_CSKHTrueIP">
                                                    Có
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input <?=  $user->is_CSKH == 0 ? 'checked' : '' ?> class="form-check-input" type="radio" name="is_CSKH" value="0"
                                                    id="is_CSKHFalseIP" >
                                                <label  class="form-check-label" for="is_CSKHFalseIP">
                                                    Không
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3 col-4">
                                            <label class="form-label">Digital</label>
                                            <div class="form-check">
                                                <input <?=  $user->is_digital == 1 ? 'checked' : '' ?>  class="form-check-input" type="radio" name="is_digital" value="1"
                                                    id="is_digitalTrueIP">
                                                <label class="form-check-label" for="is_digitalTrueIP">
                                                    Có
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input <?=  $user->is_digital == 0 ? 'checked' : '' ?> class="form-check-input" type="radio" name="is_digital" value="0"
                                                    id="is_digitalFalseIP" >
                                                <label  class="form-check-label" for="is_digitalFalseIP">
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
                                        <label class="form-label" for="nameIP">Tên đăng nhập</label>
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
                                    <label class="form-label" for="realNameIP">Tên</label>
                                    <input class="form-control" name="real_name" id="realNameIP" type="text">
                                    <p class="error_msg" id="real_name"></p>
                                </div>
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
                                    {{-- <div class="form-check">
                                            <label class="form-check-label">
                                                <input name="roles[]" type="checkbox" class="form-check-input" value="4">Khác
                                            </label>
                                    </div> --}}
                                    <div class="form-check">
                                            <label class="form-check-label">
                                                <input name="roles[]" type="checkbox" class="form-check-input" value="4">Lead Sale
                                            </label>
                                    </div>
                                    {{-- <div class="form-check">
                                            <label class="form-check-label">
                                                <input name="roles[]" type="checkbox" class="form-check-input" value="4">
                                            </label>
                                    </div> --}}
                                </div>

                                <div class="mb-3 col-4">
                                    <label class="form-label">Sale</label>
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
                                
                                <div class="mb-3 col-4">
                                    <label class="form-label">CSKH</label>
                                    <div class="form-check">
                                        <input checked  class="form-check-input" type="radio" name="is_CSKH" value="1"
                                            id="is_CSKHTrueIP">
                                        <label class="form-check-label" for="is_CSKHTrueIP">
                                            Có
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_CSKH" value="0"
                                            id="is_CSKHFalseIP" >
                                        <label  class="form-check-label" for="is_CSKHFalseIP">
                                            Không
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-3 col-4">
                                    <label class="form-label">Digital</label>
                                    <div class="form-check">
                                        <input checked  class="form-check-input" type="radio" name="is_digital" value="1"
                                            id="is_digitalRadioDefault1">
                                        <label class="form-check-label" for="is_digitalRadioDefault1">
                                            Có
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_digital" value="0"
                                            id="is_digitalRadioDefault2" >
                                        <label  class="form-check-label" for="is_digitalRadioDefault2">
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
        var real_name   = $("input[name='real_name']").val();
        var email       = $("input[name='email']").val();
        var password    = $("input[name='password']").val();
        var rePassword  = $("input[name='rePassword']").val();
        var id          = $("input[name='id']").val();
        var status      = $("input[name='status']:checked").val();
        var is_sale     = $("input[name='is_sale']:checked").val();
        var is_receive_data     = $("input[name='is_receive_data']:checked").val();
        var is_digital  = $("input[name='is_digital']:checked").val();
        var is_CSKH     = $("input[name='is_CSKH']:checked").val();
        
        console.log('is sale',is_sale);
        let roles = [];
        $("input[name='roles[]']:checked").each(function() {
            roles.push($(this).val());
        });

        console.log(roles);
        
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
                    is_sale,
                    real_name,
                    is_receive_data,
                    is_digital,
                    is_CSKH
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
                    } else if ($.isEmptyObject(data.errors)) {
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
                
                if (values.length == 4) {
                    $("#role-all").prop('checked', true);
                }
            }
        }
    });

});
</script>
@stop