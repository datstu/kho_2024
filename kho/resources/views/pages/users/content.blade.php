
<div class="tab-content rounded-bottom">
                  <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-1001">
                    
                        <div class="row ">
                          <div class="col col-4">
                            <a class="btn btn-primary" href="{{route('add-user')}}" role="button">+ Thêm thành viên</a>
                          </div>
                          <div class="col-8 ">
                            <form class ="row tool-bar d-flex justify-content-end" action="{{route('search-product')}}" method="get">
                              <div class="col-3">
                                <input class="form-control" name="search" placeholder="Tìm thành viên..." type="text">
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
                            <div class=" tab-pane p-3 active preview" role="tabpanel" id="preview-1002">
                              <table class="table table-striped">
                                <thead>
                                  <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Họ và tên</th>
                                    <th scope="col">email</th>
                                    <th scope="col">Quyền thao tác</th>
                                    <th scope="col">Ngày tạo</th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                  </tr>
                                </thead>
                                <tbody>

                                @foreach ($list as $item)
                          
                                  <tr>
                                    <th scope="row col-1">{{ $item->id }}</th>
                                    <td scope="col-7" >  {{ $item->name }}</td>
                                    <td scope="col-1">  {{ $item->email }}</td>
                                    <td scope="col-1">  {{ $item->role }}</td>
                                    <td scope="col-1">  {{ date_format($item->created_at,"d-m-Y ")}}</td>
                                    <td scope="col-1">
                                    <a class="btn btn-warning" href="{{route('update-user',['id'=>$item->id])}}" role="button">
                                      
                                        <svg class="icon me-2">
                                          <use xlink:href="{{asset('public/vendors/@coreui/icons/svg/free.svg#cil-color-border')}}"></use>
                                        </svg>Sửa
                                    </a>
                                    </td>
                                    <td scope="col-1">
                                      <a class="btn btn-danger active" href="{{route('delete-user',['id'=>$item->id])}}" role="button">
                                        <svg class="icon me-2">
                                          <use xlink:href="{{asset('public/vendors/@coreui/icons/svg/free.svg#cil-backspace')}}"></use>
                                        </svg>Xoá
                                      </a>
                                    </td>
                                  </tr>
                                  @endforeach
                                  
                                </tbody>
                              </table>
                              {!! $list->links() !!}
                            </div>
                          </div>
                        </div>
</div>
  
