<html lang="en">
<head>
  <title>Multiple File Images Upload</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <style>
    .list-images {
        width: 50%;
        margin-top: 20px;
        display: inline-block;
    }
    .hidden { display: none; }
    .box-image {
        width: 100px;
        height: 108px;
        position: relative;
        float: left;
        margin-left: 5px;
    }
    .box-image img {
        width: 100px;
        height: 100px;
    }
    .wrap-btn-delete {
        position: absolute;
        top: -8px;
        right: 0;
        height: 2px;
        font-size: 20px;
        font-weight: bold;
        color: red;
    }
    .btn-delete-image {
        cursor: pointer;
    }
    .table {
        width: 15%;
    }
  </style>
</head>
<body>
  
<div class="container lst">
  
@if (count($errors) > 0)
<div class="alert alert-danger">
    <strong>Sorry!</strong> There were more problems with your HTML input.<br><br>
    <ul>
      @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
      @endforeach
    </ul>
</div>
@endif
  
@if(session('success'))
<div class="alert alert-success">
  {{ session('success') }}
</div> 
@endif
@if (isset($list_images))
    <form method="post" action="{{url('edit')}}" enctype="multipart/form-data">
@else    
    <form method="post" action="{{url('create')}}" enctype="multipart/form-data">
    @endif
        @csrf
        <div class="input-group hdtuto control-group lst increment" >
            <div class="list-input-hidden-upload">
                <input type="file" name="filenames[]" id="file_upload" class="myfrm form-control hidden">
            </div>
            <div class="input-group-btn"> 
                <button class="btn btn-success btn-add-image" type="button"><i class="fldemo glyphicon glyphicon-plus"></i>+Add image</button>
            </div>
        </div>
        <div class="list-images">
            @if (isset($list_images) && !empty($list_images))
                @foreach (json_decode($list_images) as $key => $img)
                    <div class="box-image">
                        <input type="hidden" name="images_uploaded[]" value="{{ $img }}" id="img-{{ $key }}">
                        <img src="{{ asset('files/'.$img) }}" class="picture-box">
                        <div class="wrap-btn-delete"><span data-id="img-{{ $key }}" class="btn-delete-image">x</span></div>
                    </div>
                @endforeach
                <input type="hidden" name="images_uploaded_origin" value="{{ $list_images }}">
                <input type="hidden" name="id" value="{{ $id }}">
            @endif
        </div>
        <div class="button-submit">
            <button type="submit" class="btn btn-success" style="margin-top:10px">
                @if (isset($list_images))
                    Update
                @else
                    Submit
                @endif
            </button>
        </div>
    </form>
    @if (isset($files) && $files->count() > 0)
        <table class="table table-bordered">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Edit</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($files as $elm)
                <tr>
                  <th scope="row">{{ $elm->id }}</th>
                  <td><a href="{{ route('file.edit',['id' => $elm->id]) }}"><button type="button" class="btn btn-info">Edit</button></a></td>
                </tr>
                
            @endforeach
          </tbody>
        </table>        
    @endif
</div>
  
<script type="text/javascript">
    $(document).ready(function() {
    $(".btn-add-image").click(function(){
        $('#file_upload').trigger('click');
    });

    $('.list-input-hidden-upload').on('change', '#file_upload', function(event){
        let today = new Date();
        let time = today.getTime();
        let image = event.target.files[0];
        let file_name = event.target.files[0].name;
        let box_image = $('<div class="box-image"></div>');
        box_image.append('<img src="' + URL.createObjectURL(image) + '" class="picture-box">');
        box_image.append('<div class="wrap-btn-delete"><span data-id='+time+' class="btn-delete-image">x</span></div>');
        $(".list-images").append(box_image);

        $(this).removeAttr('id');
        $(this).attr( 'id', time);
        let input_type_file = '<input type="file" name="filenames[]" id="file_upload" class="myfrm form-control hidden">';
        $('.list-input-hidden-upload').append(input_type_file);
    });

    $(".list-images").on('click', '.btn-delete-image', function(){
        let id = $(this).data('id');
        $('#'+id).remove();
        $(this).parents('.box-image').remove();
    });
    });
</script>
  
</body>
</html>