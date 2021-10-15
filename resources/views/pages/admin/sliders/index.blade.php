@extends('layouts.app-new')
@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" type="text/css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css" type="text/css">
  
@endpush
@section('content')

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Create Slider</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="justify-content-center d-flex">
          <img style="margin 0 auto;" src="{{ asset('assets/img/icon/female-friends-enjoying-shopping-together_74855-7345.jpg') }}" alt="" width="70%">
  
        </div>
        <form action="" method="POST" enctype="multipart/form-data" id="formData">
          {{-- @method('put') --}}
          @csrf

          <div class="form-group">
            <label for="">Title</label>
            <input type="text" class="form-control" name="title" placeholder="Title in here ..." id="title">
          
            <span class="text-danger error-text title_error"  style="font-size: 13px"></span>
          </div>

          <div class="form-group">
            <label for="">Image Slider</label>
            <div class="d-block mb-2" style="font-size:13px">* Noted : Pastikan gambar jernih dan ukuran lebih dari 400px</div>
            <input type="file" class="form-control" name="image">
            <span class="text-danger error-text image_error"  style="font-size: 13px"></span>
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


<div class="row">
  <div class="col-xl-12">
      <div class="card">
          <div class="card-header">
            <button class="btn btn-success" id="btn-store" data-store="{{ route('admin.sliders.store') }}" data-toggle="modal" data-target="#exampleModal"><i class="mdi mdi-plus"></i> Add Slider</button>
          </div>
          <div class="card-body">
     

              <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead class="thead-light">
                  <tr>
                    <th style="width:3%">No</th>
                    <th>Title</th>
                    <th>Bg Slider</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                  @php
                      $no = 1;
                  @endphp
                @forelse ($sliders as $slider)
                    <tr>
                      <td>{{ $no++ }}</td>
                      <td>{{ $slider->title }}</td>
                      <td><img src="{{ $slider->image }}" width="100%" height="60" style="border-radius:1rem;object-fit:contain;"></td>
                      <td>
                        <button data-url="{{ route('admin.sliders.edit',$slider) }}" data-update="{{ route('admin.sliders.update',$slider) }}" class="btn btn-warning" id="btn-edit"><i class="mdi mdi-pencil"></i></button>
                        <button id="btn-delete" data-delete="{{ route('admin.sliders.destroy',$slider) }}" class="btn btn-danger"><i class="mdi mdi-delete"></i></button>
                      </td>
                  </tr>
                @empty
                    
                @endforelse
                </tbody>
              </table>
          <div>
      </div>
  </div>
</div>
@endsection

@push('script')
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js "></script>
<script src=" https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
 
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>


<script>
  $(document).ready(function() {
      $('#example').DataTable({
          "columnDefs": [ {
          "searchable": false,
          "orderable": false,
          "targets": [0,3]
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
  // } );  
</script>
{{-- <script src="{{ asset('js-app/slider/crud.js') }}"></script> --}}

<script>
$('button#btn-store').click(function() {
    let data_store = $(this).data('store');
    $('#formData').attr('action',data_store);
    $("#formData").trigger('reset');
    $('.error-text').text('');

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
                'You data is successfuly created!',
                'success'
                )   
                $('#exampleModal').modal('hide');
                $('form').trigger("reset");
                location.reload();
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

$('button#btn-edit').click(function(e) {
    let url = $(this).data('url');
    let data_update = $(this).data('update');
    
    $('#formData').attr('action',data_update);
    e.preventDefault();

    $.ajax({
        url: url,
        type:'GET',
        dataType:'json',     
        beforeSend:function(){
            $('#loading').removeClass('d-none');
        },
        complete:function(){
            $('#loading').addClass('d-none');
            $('#exampleModal').modal('show');
        },
        success:function(res){
            $('#title').val(res.slider.title);
            console.log(res);
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