@extends('layouts.app-new')
@push('style')

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('css/select2-bootstrap.css') }}">
@endpush

@section('content')

<!-- Modal -->
<div class="modal fade" id="addStock" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
            {{-- <img style="margin 0 auto;" src="{{ asset('assets/img/icon/0001.jpg') }}" alt="" width="100%"> --}}
          </div>
          <div class="error-same-stock"></div>
          <form action="{{ route('admin.product.storeStock',$product) }}" method="POST" enctype="multipart/form-data" id="formDataStock">
            {{-- @method('put') --}}
            @csrf
  
            <div class="form-group">
                <label for="">Stock Product</label>
                <div class="mb-2  text-muted" style="font-size: 13px">* Noted : Silahkan tulis Stock Product</div>
                <input type="number" id="stock" class="form-control" name="stock" placeholder="Stock Product">
              
                <span class="text-danger error-text stock_error"  style="font-size: 13px"></span>
              </div> 
              
              <div class="form-group">
                <label for="">Color Code</label>
                <div class="mb-2  text-muted" style="font-size: 13px">* Noted : Silahkan tulis code color product Example : #0000 (warna hitam) </div>
                {{-- <input type="text" id="color_name" class="form-control" name="color_name" placeholder="Product color code ...">
               --}}
                <select class="form-control select2" id="color_id" name="color_id">
                    <option>---</option>
    
                    @foreach($sizeColor->colors as $color)
                        <option value="{{$color->id}}">{{ $color->color_name }}</option>
                    @endforeach
                </select>
                <span class="text-danger error-text color_id_error"  style="font-size: 13px"></span>
              </div> 
              
              <div class="form-group">
                <label for="">Product Size</label>
                <div class="mb-2  text-muted" style="font-size: 13px">* Noted : Silahkan tulis Size of Product (Example : 43,42,41) </div>
                {{-- <input type="text" id="sizes_name" class="form-control" name="sizes_name" placeholder=" Size of Product ..."> --}}
                <select class="form-control select2" id="size_id" name="size_id">
                    <option>---</option>
    
                    @foreach($sizeColor->sizes as $size)
                        <option value="{{$size->id}}">{{ $size->sizes_name }}</option>
                    @endforeach
                </select>
                <span class="text-danger error-text size_id_error"  style="font-size: 13px"></span>
              </div>
        </div>
        <div class="modal-footer">
          <button type="button"  class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" id="btn-create" class="btn btn-primary">Save changes</button>
        </div>
        
      </form>
      </div>
    </div>
</div><!-- Modal -->

<div class="modal fade" id="addStockUpdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"> Update Stock Product</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="justify-content-center d-flex">
            {{-- <img style="margin 0 auto;" src="{{ asset('assets/img/icon/0001.jpg') }}" alt="" width="100%"> --}}
          </div>
          <div class="error-same-stock"></div>
          <form action="" method="POST" enctype="multipart/form-data" id="formDataStockEdit">
            {{-- @method('put') --}}
            @csrf
  
            <div class="form-group">
                <label for="">Stock Product</label>
                <div class="mb-2  text-muted" style="font-size: 13px">* Noted : Silahkan tulis Stock Product</div>
                <input type="number" id="stock" class="form-control" name="stock" placeholder="Stock Product">
              
                <span class="text-danger error-text stock_error-edit"  style="font-size: 13px"></span>
              </div> 
              
              <div class="form-group">
                <label for="">Color Code</label>
                <div class="mb-2  text-muted" style="font-size: 13px">* Noted : Silahkan tulis code color product Example : #0000 (warna hitam) </div>
                {{-- <input type="text" id="color_name" class="form-control" name="color_name" placeholder="Product color code ...">
               --}}
                <select class="form-control select2" id="color_id" name="color_id">
                    <option>---</option>
    
                    @foreach($sizeColor->colors as $color)
                        <option value="{{$color->id}}">{{ $color->color_name }}</option>
                    @endforeach
                </select>
                <span class="text-danger error-text color_id_error-edit"  style="font-size: 13px"></span>
              </div> 
              
              <div class="form-group">
                <label for="">Product Size</label>
                <div class="mb-2  text-muted" style="font-size: 13px">* Noted : Silahkan tulis Size of Product (Example : 43,42,41) </div>
                {{-- <input type="text" id="sizes_name" class="form-control" name="sizes_name" placeholder=" Size of Product ..."> --}}
                <select class="form-control select2" id="size_id" name="size_id">
                    <option>---</option>
    
                    @foreach($sizeColor->sizes as $size)
                        <option value="{{$size->id}}">{{ $size->sizes_name }}</option>
                    @endforeach
                </select>
                <span class="text-danger error-text size_id_error-edit"  style="font-size: 13px"></span>
              </div>
        </div>
        <div class="modal-footer">
          <button type="button"  class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" id="btn-edit-stock" class="btn btn-primary">Save changes</button>
        </div>
        
      </form>
      </div>
    </div>
</div>
  
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h2>Set Stock of Product</h2>
                <div class="arrow-back">
                  <a href="{{ route('admin.product') }}"> <i class="mdi mdi-arrow-left"></i> Back to Product</a>
                </div>
            </div>
            <div class="card-body">
                <button data-target="#addStock" data-toggle="modal" class="btn btn-success mb-4"><i class="mdi mdi-plus"></i> Add Stock</button>
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-borderedless" style="width:100%">
                        <thead class="thead-light">
                          <tr>
                            <th style="width:3%">No</th>
                            <th>stock</th>
                            <th>Product</th>
                            <th>Color</th>
                            <th>Size</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php $no =1 ?>
                            @forelse ($stocks->sortBy('color_id') as $stock)  
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $stock->stock }}</td>
                                    <td>{{ $stock->product->title }}</td>
                                    <td>{{ $stock->color->color_name }}</td>
                                    <td>{{ $stock->size->sizes_name }}</td>
                                    <td>
                                        <button data-url="{{ route('admin.product.editStock',[$product,$stock]) }}" data-update="{{ route('admin.product.updateStock',[$product,$stock]) }}"  class="btn btn-sm btn-warning" id="btn-edit"><i class="mdi mdi-pencil"></i></button>
                                        <button class="btn btn-sm btn-danger" id="btn-delete" data-delete="{{ route('admin.product.deleteStock',[$product,$stock]) }}"><i class="mdi mdi-delete"></i></button>
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
@endsection

  


@push('script')

<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>
<script>
   $(document).ready(function() {
        $('.select2').select2({
            theme: "bootstrap",
        });
    });

$('#formDataStock').submit(function(e) {
        
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
            $(document).find('.error-same-stock').slideDown('fast').html('');
        },    
        complete: function(){
            $('#btn-create').removeClass("disabled").html("Save changes").attr('disabled',false);
            
        },
        success: function(res){
            // $("#count").text(res.count);
            if(res.success == true){
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

            else if(res.success == false){
                $('.error-same-stock').html('<div class="alert alert-danger"><ul><li>'+res.message+'</li></ul></div>');
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

$('#formDataStockEdit').submit(function(e) {
        
    e.preventDefault();

    var formData = new FormData(this);

    $.ajax({
        type: 'POST',
        data : formData,
        contentType: false,
        processData: false,
        url: $(this).attr('action'),
        beforeSend:function(){
            $('#btn-edit-stock').addClass("disabled").html("<i class='mdi mdi-spin mdi-loading'></i>  Processing...").attr('disabled',true);
            $(document).find('span.error-text').text(''); 
            $(document).find('.error-same-stock').slideDown('fast').html('');
        },    
        complete: function(){
            $('#btn-edit-stock').removeClass("disabled").html("Save changes").attr('disabled',false);
            
        },
        success: function(res){
            // $("#count").text(res.count);
            if(res.success == true){
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

            else if(res.success == false){
                $('.error-same-stock').html('<div class="alert alert-danger"><ul><li>'+res.message+'</li></ul></div>');
            }
        },
        error(err){
            $.each(err.responseJSON.errors,function(prefix,val) {
                $('.'+prefix+'_error-edit').text(val[0]);
            })

            console.log(err);
        }

    })
});


$('button#btn-edit').click(function(e) {
    let url = $(this).data('url');
    let data_update = $(this).data('update');

    $('#formDataStockEdit').attr('action',data_update);

    e.preventDefault();

    $.ajax({
        url: url,
        type:'GET',
        dataType:'json',     
        beforeSend:function(){
            $('#loading').removeClass('d-none');
            $(document).find('span.error-text').text(''); 
        },
        complete:function(){
            $('#addStockUpdate').modal('show');
        },
        success:function(res){ 
            $('#formDataStockEdit').find('input[name=stock]').val(res.stock.stock);
            $('#formDataStockEdit').find('#color_id').val(res.stock.color_id).change();
            $('#formDataStockEdit').find('#size_id').val(res.stock.size_id).change();
            

        }
    })
})

$('button#btn-delete').click(function(e){
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