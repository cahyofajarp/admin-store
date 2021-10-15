@extends('layouts.app-new')
@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" type="text/css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css" type="text/css">
  
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('css/select2-bootstrap.css') }}">
<style>

.table td, .table th {
    font-size: .8125rem;
    white-space: normal !important;
}
</style>
@endpush
@section('content')

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Create Product Highlight</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="justify-content-center d-flex">
          <img style="margin 0 auto;" src="{{ asset('assets/img/icon/female-friends-enjoying-shopping-together_74855-7345.jpg') }}" alt="" width="70%">
  
        </div>
        <form action="" method="POST" enctype="multipart/form-data" id="formData">
          
        <input type="hidden" name="_method" value="" id="method">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

          <div class="form-group">
            <label for="">Product</label>
            <select name="product_id" id="product_id" class="form-control select2">
                <option value="">---</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->title }}</option>
                @endforeach
            </select>
            {{-- <input type="text" class="form-control" name="product_id" placeholder="Title in here ..." id="product_id"> --}}
            <span class="text-danger error-text product_id_error"  style="font-size: 13px"></span>
          </div>

          <div class="form-group">
            <label for="">Title</label>
            <input type="text" class="form-control" name="title" placeholder="Title in here ..." id="title">
            <span class="text-danger error-text title_error"  style="font-size: 13px"></span>
          </div>
          
          <div class="form-group">
            <label for="">Description</label>
            <input type="text" class="form-control" name="description" placeholder="Description in here ..." id="description">
            <span class="text-danger error-text description_error"  style="font-size: 13px"></span>
          </div>

          <div class="form-group">
            <label for="">Image Hightlight</label>
            <div class="d-block mb-2" style="font-size:13px">* Noted : Pastikan gambar jernih dan ukuran lebih dari 1000px x 500px</div>
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
      @if(!empty($warning))
        <div class="alert alert-warning"> {{ $warning }}, Click here <a href="{{ route('admin.product') }}">Go to Product</a></div>
      @endif
      <div class="card">
          <div class="card-header">
           @if ($product_highlight->count() == 0)
                <button class="btn btn-success" id="btn-store" data-store="{{ route('hightlight.store') }}" data-toggle="modal" data-target="#exampleModal"><i class="mdi mdi-plus"></i> Add Hightlight</button>
           @else
              <button class="btn btn-primary">Product Highlight is Available</button> 
           @endif
          </div>
          <div class="card-body">
             <div class="table-responsive">
                <table  class="table" style="width:120%">
                    <thead class="thead-light">
                      <tr>
                        <th  scope="col">No</th>
                        <th  scope="col">Title Hightlight</th>
                        <th  scope="col">Description Hightlight</th>
                        <th  scope="col">Image</th>
                        <th  scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                      @php
                          $no = 1;
                      @endphp
                    @forelse ($product_highlight as $hightlight)
                        <tr class="w-50">
                          <td  scope="row">{{ $no++ }}</td>
                          <td style="width: 20%;">{{ $hightlight->title }}</td>
                          <td style="width:30%">{{ $hightlight->description }}</td>
                          <td style="width:20%"><img src="{{ $hightlight->image }}" width="100%"  style="border-radius:1rem;"></td>
                          <td style="width:20%">
                            <button data-url="{{ route('hightlight.edit',$hightlight) }}" data-update="{{ route('hightlight.update',$hightlight) }}" class="btn btn-warning" id="btn-edit"><i class="mdi mdi-pencil"></i></button>
                            <button id="btn-delete" data-delete="{{ route('hightlight.destroy',$hightlight) }}" class="btn btn-danger"><i class="mdi mdi-delete"></i></button>
                          </td>
                      </tr>
                    @empty
                        
                    @endforelse
                    </tbody>
                  </table>
             </div>
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

  t.on('order.dt search.dt', function () {
      t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      } );
  } ).draw();
      
});

$(document).ready(function() {
    $('.select2').select2({
        theme: "bootstrap",
    });
});
  // } );  
</script>

<script>
$('button#btn-store').click(function() {
    let data_store = $(this).data('store');
    $('#formData').attr('action',data_store);
    $("#formData").trigger('reset');
    $('.error-text').text('');
    $('#method').val('POST');

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
            $('#method').val('PUT');
            $('#title').val(res.data.title);
            $('#description').val(res.data.description);
            $('#product_id').val(res.data.product_id).change();
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
{{-- <script src="{{ asset('js-app/slider/crud.js') }}"></script> --}}

@endpush