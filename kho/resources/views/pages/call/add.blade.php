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
                <div class="card-header"><strong>Thêm hoạt động call </strong></div>
                @if(isset($call))
                <div class="card-body">
                    <div class="body flex-grow-1">
                        <div class="tab-content rounded-bottom">
                            <form action="{{route('call-save')}}" method="POST">
                                {{ csrf_field() }}
                                <input value="{{$call->id}}" name="id" type="hidden">
                                <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-1000">
                                    <div class="row">
                                        <div class="mb-3 col-8">
                                            <label class="form-label" for="nameIP">Tên</label>
                                            <input class="form-control" value="{{$call->name}}" name="name" id="nameIP" type="text">
                                            <p class="error_msg" id="name"></p>
                                        </div>
                                        <div class="mb-3 col-4">
                                            <label class="form-label" for="timeFor">Thời Gian (giờ)</label>
                                            <input class="form-control" value="{{$call->time}}" name="time" id="timeFor" type="text">
                                            <p class="error_msg" id="time"></p>
                                        </div>
                                        <div class="mb-3 col-4">
                                            <label class="form-label" for="qtyIP">Trạng Thái</label>
                                                <div class="form-check">
                                                    <input <?=  $call->status == 1 ? 'checked' : '' ?> class="form-check-input" type="radio" name="status" value="1"
                                                        id="flexRadioDefault1">
                                                    <label class="form-check-label" for="flexRadioDefault1">
                                                        Bật
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input <?=  $call->status == 0 ? 'checked' : '' ?> class="form-check-input" type="radio" name="status" value="0"
                                                        id="flexRadioDefault2" >
                                                    <label  class="form-check-label" for="flexRadioDefault2">
                                                        Tắt
                                                    </label>
                                                </div>
                                        </div>
                                    </div>
                                    <button id="submit" class="btn btn-primary">Cập nhật</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>    
                
                @else
                <div class="card-body">
                    <div class="body flex-grow-1">
                        <div class="tab-content rounded-bottom">
                            <form action="{{route('call-save')}}" method="POST">
                                {{ csrf_field() }}
                                <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-1000">
                                    <div class="row">
                                        <div class="mb-3 col-4">
                                            <label class="form-label" for="nameIP">Tên</label>
                                            <input required class="form-control" name="name" id="nameIP" type="text">
                                            <p class="error_msg" id="name"></p>
                                        </div>
                                        <div class="mb-3 col-4">
                                            <label class="form-label" for="timeFor">Thời Gian (giờ)</label>
                                            <input class="form-control" name="time" id="timeFor" type="text">
                                            <p class="error_msg" id="time"></p>
                                        </div>
                                        
                                    </div>
                                    <button type="submit" id="submit" class="btn btn-primary">Tạo</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('public/js/notify.js'); }}"></script>
@stop