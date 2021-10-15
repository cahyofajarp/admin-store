@extends('layouts.app-new')
@section('title')
   Product Gallery
@endsection
@push('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" type="text/css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css" type="text/css">  
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('css/select2-bootstrap.css') }}">
@endpush

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h3>Create Product Image</h3> - <h3>for Color : ({{ $color->color_name }}) <button class="btn" style="box-shadow:0 7px 14px rgb(50 50 93 / 10%), 0 3px 6px rgb(0 0 0 / 8%);background-color: {{ $color->color_name }}"></button></h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.gallery.store',[$product,$color]) }}" method="POST" enctype="multipart/form-data" id="formData">
                    @csrf
                    <div class="form-group">
                        <label for="">Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Title in here ... ">
                        <span class="text-danger error-text title_error"  style="font-size: 13px"></span>
          
                    </div>


                    <div class="form-group">
                        <label for="">Image for Product</label>
                        <small class="d-block">create an image for your product!</small>
                        <input type="file" name="image" class="form-control mt-2" placeholder="Title in here ... ">
                        <span class="text-danger error-text image_error"  style="font-size: 13px"></span>
          
                    </div>

                    <button id="btn-create" type="submit" class="btn btn-success"><i class="ni ni-send"></i> Save Changes</button>
                </form>
            </div>
        </div>
    </div>
  </div>
@endsection

@push('script')

<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js "></script>
<script src=" https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>

{{-- <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script> --}}
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
                if(res){
                    Swal.fire(
                    'Success!',
                    'You data is successfuly!',
                    'success'
                    )   
                    // $('#exampleModal').modal('hide');
                    // $(this).trigger("reset");
                    window.location.href= '{{ route("admin.gallery.show",[$product,$color]) }}';
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