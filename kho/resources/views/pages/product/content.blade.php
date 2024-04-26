
<div class="tab-content rounded-bottom">
                  <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-1001">
                    
                        <div class="row ">
                        <?php $checkAll = isFullAccess(Auth::user()->role);?>
                        @if ($checkAll)
                          <div class="col col-4">
                            <a class="btn btn-primary" href="{{route('add-product')}}" role="button">+ Thêm mới</a>
                          </div>
                        @endif

                          <div class="col-8 ">
                            <form class ="row tool-bar d-flex justify-content-end" action="{{route('search-product')}}" method="get">
                              <div class="col-3">
                                <input class="form-control" name="search" placeholder="Tìm sản phẩm..." type="text">
                              </div>
                              <div class="col-3 " style="padding-left:0;">
                                <button type="submit" class="btn btn-primary"><svg class="icon me-2">
                                            <use xlink:href="{{asset('public/vendors/@coreui/icons/svg/free.svg#cil-search')}}"></use>
                                          </svg>Tìm</button>
                            </form>
                              </div>
                          </div>
                        </div>
                        <div class="example mt-0">
                          <div class="tab-content rounded-bottom">
                            <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-1002">
                              <table class="table table-striped">
                                <thead>
                                  <tr>
                                    <th scope="col">#</th>
                                    
                                    <th scope="col" style="width:30%">Tên sản phẩm</th>
                                    <th scope="col">Giá</th>
                                    <th scope="col">Số lượng</th>
                                    <th scope="col">Trạng thái</th>
                                    <th scope="col">Ngày nhập</th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                  </tr>
                                </thead>
                                <tbody>

                                @foreach ($list as $item)
                          
                                  <tr>
                                    <th scope="row col-1">{{ $item->id }}</th>
                                    <td scope="col-7" >  {{ $item->name }}</td>
                                    <td scope="col-1">  {{ $item->price }} đ</td>
                                    <td scope="col-1">  {{ $item->qty }}</td>
                                    <td scope="col-1">  {{ ($item->status == 1) ? 'Bật' : 'Tắt'; }}</td>
                                    <td scope="col-1">  {{ date_format($item->created_at,"H:i:s d-m-Y ")}}</td>
                                    <td scope="col-1">
                                      
                                    @if ($checkAll)

                                    <a href="{{route('update-product',['id'=>$item->id])}}" role="button">
                                      
                                        <svg class="icon me-2">
                                          <use xlink:href="{{asset('public/vendors/@coreui/icons/svg/free.svg#cil-color-border')}}"></use>
                                        </svg>
                                    </a>
                                    
                                    @endif

                                    </td>
                                    <td scope="col-1">

                                      @if ($checkAll)

                                      <a href="{{route('delete-product',['id'=>$item->id])}}" role="button">
                                        <svg class="icon me-2">
                                          <use xlink:href="{{asset('public/vendors/@coreui/icons/svg/free.svg#cil-backspace')}}"></use>
                                        </svg>
                                      </a>

                                      @endif

                                    </td>
                                  </tr>

                                  @endforeach
                                  
                                </tbody>
                              </table>
                              {{$list->links('pagination::bootstrap-5')}}
                            </div>
                          </div>
                        </div>
</div>
  
