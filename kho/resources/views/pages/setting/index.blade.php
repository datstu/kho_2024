<link href="{{ asset('public/css/pages/notify.css'); }}" rel="stylesheet">
@extends('layouts.default')
@section('content')

@include('notify::components.notify')
<style>
    #laravel-notify .notify {
        z-index: 2;
    }
    .header.header-sticky {
        z-index: unset;
    }

</style>
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        <div class="row">
            <div id="notifi-box" class="hidden alert alert-success print-error-msg" >
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            </div>
        
            <div class="col-12">
                <div class="card-header"><strong>Tích hợp Telegram </strong></div>
                <div class="card-body">
                    <div class="body flex-grow-1">
                        <div class="tab-content rounded-bottom">
                            <form action="{{route('telegram-save')}}" method="POST">
                                {{ csrf_field() }}
                                <input value="<?= ($telegram) ? $telegram->id : ''; ?>" name="id" type="hidden">
                                <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-1000">
                                    <div class="mb-3 row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label">Token</label>
                                        <div class="col-lg-6 col-sm-10 ">
                                          <input value="<?= ($telegram) ? $telegram->token : ''; ?>" required type="text" name="token_telegram" class="form-control" id="staticEmail">
                                        </div>
                                      </div>
                                      <div class="mb-3 row">
                                        <label for="inputPassword" class="col-sm-2 col-form-label">Chat ID Niềm vui tới rồi</label>
                                        <div class="col-lg-6  col-sm-10">
                                          <input value="<?= ($telegram) ? $telegram->id_NVTR : ''; ?>" required type="text" name="id_NVTR" class="form-control" id="inputPassword">
                                        </div>
                                      </div>
                                      <div class="mb-3 row">
                                        <label for="inputPassword" class="col-sm-2 col-form-label">Chat ID CSKH</label>
                                        <div class="col-lg-6  col-sm-10">
                                          <input value="<?= ($telegram) ? $telegram->id_CSKH : ''; ?>" required type="text" name="id_CSKH" class="form-control" id="inputPassword">
                                        </div>
                                      </div>
                                        <div class="mb-3 row">
                                            <label class="form-label col-sm-2 " for="qtyIP">Trạng Thái</label>
                                            <div class="col-lg-6  col-sm-10">
                                                <div class="form-check ">
                                                    <input  <?= ($telegram && $telegram->status == 1) ? 'checked' : ''; ?> checked class="form-check-input" type="radio" name="status" value="1"
                                                        id="flexRadioDefault1">
                                                    <label class="form-check-label" for="flexRadioDefault1">
                                                        Bật
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input <?= ($telegram && $telegram->status == 0) ? 'checked' : ''; ?> class="form-check-input" type="radio" name="status" value="0"
                                                        id="flexRadioDefault2" >
                                                    <label  class="form-check-label" for="flexRadioDefault2">
                                                        Tắt
                                                    </label>
                                                </div>
                                            </div>
                                            
                                      </div>
                                    <button type="submit" id="submit" class="btn btn-primary">Lưu</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="12">
                        <p>Production:</p>

                        <p>&ensp; Gửi thông báo khi tạo đơn hàng mới đến 'nhóm Niềm vui tới rồi': -4126333554</p>
                            <p>&ensp; Gửi thông báo khi khách hàng nhận được hàng đến 'nhóm CSKH': -4128471334</p>
                        <p>Dev/Local:</p>
                        <p> &ensp; Gửi thông báo khi tạo đơn hàng mới đến 'nhóm Testbot': -4140296352</p>
                        <p>&ensp;  Gửi thông báo khi khách hàng nhận được hàng đến 'nhóm Testbot': -4140296352</p>
                   </pre></div>
                </div>
            </div>
        
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('public/js/notify.js'); }}"></script>
@stop