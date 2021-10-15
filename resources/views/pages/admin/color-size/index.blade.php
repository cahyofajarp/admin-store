@extends('layouts.app-new')

@section('content')

<!-- Modal -->
<div class="modal fade" id="addColor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Create Color Product</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="justify-content-center d-flex">
            <img style="margin 0 auto;" src="{{ asset('assets/img/icon/product.jpg') }}" alt="" width="60%">
          </div>
          <form action="{{ route('sizecolor.store') }}" method="POST" enctype="multipart/form-data" id="formDataColor">
            {{-- @method('put') --}}
            @csrf
  
            <div class="form-group">
              <label for="">Color Code</label>
              <div class="mb-2  text-muted" style="font-size: 13px">* Noted : Silahkan tulis code color product Example : #0000 (warna hitam) </div>
              <input type="text" id="color_name" class="form-control" name="color_name" placeholder="Product color code ...">
            
              <span class="text-danger error-text color_name_error"  style="font-size: 13px"></span>
            </div> 
            
  
        </div>
        <div class="modal-footer">
          <button type="button"  class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" id="btn-create" class="btn btn-primary">Save changes</button>
        </div>
        
      </form>
      </div>
    </div>
</div>

<div class="modal fade" id="addSize" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Create Color Product</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="justify-content-center d-flex">
            <img style="margin 0 auto;" src="{{ asset('assets/img/icon/product.jpg') }}" alt="" width="60%">
          </div>
          <form action="{{ route('size.store') }}" method="POST" enctype="multipart/form-data" id="formDataSize">
            {{-- @method('put') --}}
            @csrf
  
            <div class="form-group">
              <label for="">Product Size</label>
              <div class="mb-2  text-muted" style="font-size: 13px">* Noted : Silahkan tulis Size of Product (Example : 43,42,41) </div>
              <input type="text" id="sizes_name" class="form-control" name="sizes_name" placeholder=" Size of Product ...">
            
              <span class="text-danger error-text sizes_name_error"  style="font-size: 13px"></span>
            </div> 
            
  
        </div>
        <div class="modal-footer">
          <button type="button"  class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" id="btn-create-size" class="btn btn-primary">Save changes</button>
        </div>
        
      </form>
      </div>
    </div>
</div>
  
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h2>Size and Color Product Set</h2>
                <div class="arrow-back">
                  <a href="{{ route('admin.product') }}"> <i class="mdi mdi-arrow-left"></i> Back to Product</a>
                </div>
            </div>
            <div class="card-body">
                <ul class="nav nav-pills mb-5" id="pills-tab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link btn active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Color</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link btn" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Size</a>
                    </li>
                  </ul>
                  <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <hr>
                        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addColor"><i class="mdi mdi-plus"></i> Add Color</button>
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-borderedless" style="width:100%">
                                <thead class="thead-light">
                                  <tr>
                                    <th style="width:3%">No</th>
                                    <th>Color Code</th>
                                    <th>Color</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php $no =1 ?>
                                    @forelse ($colors as $color)  
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $color->color_name }}</td>
                                            <td>
                                                <button class="btn" style="box-shadow:0 7px 14px rgb(50 50 93 / 10%), 0 3px 6px rgb(0 0 0 / 8%);background: {{ $color->color_name }}"></button>
                                            </td>
                                            <td>
                                                <button class="btn btn-danger btn-delete" id="btn-delete" data-delete="{{ route('sizecolor.destroy',$color) }}"><i class="mdi mdi-delete"></i></button>
                                            </td>
                                        </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3">No data.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                              </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <hr>

                        <button class="btn btn-primary mb-3"  data-toggle="modal" data-target="#addSize"><i class="mdi mdi-plus"></i> Add Size</button>
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-borderedless" style="width:100%">
                                <thead class="thead-light">
                                  <tr>
                                    <th style="width:3%">No</th>
                                    <th>Size</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php $no =1 ?>
                                    @forelse ($sizes->sortBy('sizes_name') as $size)  
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td><button class="btn btn-primary">{{ $size->sizes_name }}</button> | add Product with this size on product link in sidebar</td>
                                           
                                            <td>
                                                <button class="btn btn-danger btn-delete" id="btn-delete" data-delete="{{ route('size.destroy',$size) }}"><i class="mdi mdi-delete"></i></button>
                                            </td>
                                        </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3">No data.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                              </table>
                        </div>
                    </div>
                  </div>
            </div>
        </div>
    </div>
  </div>
@endsection

<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
@push('script')
<script>
 $('#formDataColor').submit(function(e) {
    e.preventDefault();

    var formData = new FormData(this);

    $.ajax({
        type: 'POST',
        data : formData,
        contentType: false,
        processData: false,
        url: $(this).attr('action'),
        beforeSend:function(){
            $('#btn-create').addClass("disabled").html("<i class='mdi mdi-spin mdi-loading'></i>  Processing...").attr('disabled',true);
            $(document).find('span.error-text').text('');
        },    
        complete: function(){
            $('#btn-create').removeClass("disabled").html("Save changes").attr('disabled',false);
            
        },
        success: function(res){
            // $("#count").text(res.count);
            if(res){
                Swal.fire(
                'Success!',
                'You data is successfuly!',
                'success'
                )   
                $('#exampleModal').modal('hide');
                $(this).trigger("reset");
                location.reload();
                // console.log(res.)
            }
        },
        error(err){
            $.each(err.responseJSON.errors,function(prefix,val) {
                $('.'+prefix+'_error').text(val[0]);
            })

            console.log(err);
        }

    })
}); 

$('#formDataSize').submit(function(e) {
    e.preventDefault();

    var formData = new FormData(this);

    $.ajax({
        type: 'POST',
        data : formData,
        contentType: false,
        processData: false,
        url: $(this).attr('action'),
        beforeSend:function(){
            $('#btn-create-size').addClass("disabled").html("<i class='mdi mdi-spin mdi-loading'></i>  Processing...").attr('disabled',true);
            $(document).find('span.error-text').text('');
        },    
        complete: function(){
            $('#btn-create-size').removeClass("disabled").html("Save changes").attr('disabled',false);
            
        },
        success: function(res){
            // $("#count").text(res.count);
            if(res){
                Swal.fire(
                'Success!',
                'You data is successfuly!',
                'success'
                )   
                $('#exampleModal').modal('hide');
                $(this).trigger("reset");
                location.reload();
                // console.log(res.)
            }
        },
        error(err){
            $.each(err.responseJSON.errors,function(prefix,val) {
                $('.'+prefix+'_error').text(val[0]);
            })

            console.log(err);
        }

    })
});

$('button.btn-delete').click(function(e){
        e.preventDefault();
        
        let dataDelete = $(this).data('delete');
        // console.log(dataDelete);
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type:'DELETE',
                    url:dataDelete,
                    data:{
                        _token:"{{ csrf_token() }}"
                    },
                    success:function(response){
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                        location.reload();
                    // console.log(response);
                        
                    },
                    error:function(err){
                        console.log(err);
                    }
                });
            }
        })
    });
</script>
@endpush