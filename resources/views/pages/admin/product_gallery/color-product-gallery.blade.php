@extends('layouts.app-new')
@section('title')
   Product Gallery Color 
@endsection
@push('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" type="text/css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css" type="text/css">  
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('css/select2-bootstrap.css') }}">
@endpush

@section('content')
<!-- Modal -->
<div class="modal fade" id="addColorDefault" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Choose Color Default</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{ route('admin.gallery.colorKeyGallery',$product) }}" method="POST" enctype="multipart/form-data" id="formData">
            {{-- @method('put') --}}
            @csrf
  
            <div class="form-group">
              <label for="">Color Code</label>
              <div class="mb-2  text-muted" style="font-size: 13px">* Noted : Default Thumbail Color of the Product </div>
              {{-- <input type="text" id="color_name" class="form-control" name="color_name" placeholder="Product color code ..."> --}}
                <select name="color_key" id="" class="form-control select2">
                    <option value="">---</option>
                    @foreach ($productColor->colors as $color)
                        <option value="{{ $color->id }}">{{ $color->color_name }}</option>
                    @endforeach
                </select>
              <span class="text-danger error-text color_key_error"  style="font-size: 13px"></span>
            </div>
        
            <table class="table table-hover tabl-striped">
                <tr>
                    <td>Thumbail Default Color</td>
                    <td></td>
                    {{-- @php
                        $name_col = 'none';

                        if($col){
                            $name_col = $col->color_name;
                        }
                    @endphp
                    <td>{{ $name_col }} <button class="btn" style="{{ $name_col != 'none' ? 'box-shadow:0 7px 14px rgb(50 50 93 / 10%), 0 3px 6px rgb(0 0 0 / 8%);' : '' }}background: {{ $name_col }}"></button></td> --}}
                </tr>
            </table>
        </div>
        <div class="modal-footer">
          <button type="button"  class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" id="btn-create" class="btn btn-primary">Save changes</button>
        </div>
      </form>
      </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h3>Choose Color</h3>
                    <div class="float-right">
                        <div class="mb-3">
                            <small class="d-block">Noted : Harap pilih thumbnail.</small>
                            <small>Pilih warna terlebih dahulu dan klik data di bawah.</small>
                        </div>
                        @if ($product->productgallery_thumbnails)
                            @if ($product->productgallery_thumbnails->count() > 0)
                                <button class="btn btn-success">Status Thumbnail : <i class="mdi mdi-check-circle"></i> OK</button>
                             @endif
                            @else 
                             <button class="btn btn-danger">Status Thumbnail : <i class="mdi mdi-close-circle"></i> No Thumbnail</button>
                         
                        @endif
                    </div>
                
            </div>
            <div class="card-body">
                  <div class="table-responsive">
                    <table id="example" class="table table-striped table-borderedless" style="width:100%">
                        <thead class="thead-light">
                          <tr>
                            <th style="width:3%">No</th>
                            <th>Color Code</th>
                            <th>Color</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php $no =1 ?>
                            @forelse ($productColor->colors as $color)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $color->color_name }}</td>
                                    <td><button class="btn" style="box-shadow:0 7px 14px rgb(50 50 93 / 10%), 0 3px 6px rgb(0 0 0 / 8%);background: {{ $color->color_name }}"></button></td>
                                    <td>
                                        @if ($product->productGalleries->where('color_id',$color->id)->count() > 0)
                                            <button class="btn btn-sm btn-success">OK</button>
                                        @else 
                                            <button class="btn btn-danger btn-sm">No Image</button>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.gallery.show',[$product,$color]) }}" class="btn btn-primary"><i class="mdi mdi-image-album"></i> Add Photos</a>
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

<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js "></script>
<script src=" https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
$(document).ready(function() {
        $('.select2').select2({
            theme: "bootstrap",
        });
    });

    $(document).ready(function() {
        $('#example').DataTable({
            "columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": [0]
        } ],
        "order": [],
        language: {
            paginate: {
                next: '<i class="fas fa-angle-right"></i>',
                previous: '<i class="fas fa-angle-left"></i>'
            }
        },
    } );

    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
        
});

$('#formData').submit(function(e) {
        
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
                if(res.success == true){
                    Swal.fire(
                    'Success!',
                    'You data is successfuly!',
                    'success'
                    )   
                    // $('#exampleModal').modal('hide');
                    // $(this).trigger("reset");
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

</script>
@endpush