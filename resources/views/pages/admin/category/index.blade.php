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
          <img style="margin 0 auto;" src="{{ asset('assets/img/icon/0001.jpg') }}" alt="" width="80%">
  
        </div>
        <form action="" method="POST" enctype="multipart/form-data" id="formData">
          {{-- @method('put') --}}
          @csrf

          <div class="form-group">
            <label for="">Category</label>
            <div class="mb-2  text-muted" style="font-size: 13px">* Noted : Silahkan tulis nama category</div>
            <input type="text" id="name" class="form-control" name="name" placeholder="Name Category in here ..." id="title">
          
            <span class="text-danger error-text name_error"  style="font-size: 13px"></span>
          </div> 
          
          <div class="form-group">
            <label for="">Title</label>
            <div class="mb-2  text-muted" style="font-size: 13px">* Noted : Silahkan tulis Title category</div>
            <input type="text" id="title_category" class="form-control" name="title_category" placeholder="Title Category in here ..." id="title">
          
            <span class="text-danger error-text title_category_error"  style="font-size: 13px"></span>
          </div>

          <div class="form-group">
            <label for="">Text Category</label>
            <div class="mb-2  text-muted" style="font-size: 13px">* Noted : Silahkan tulis text untuk di image category</div>
            <textarea name="text_desc_category" class="form-control" id="text_desc_category"></textarea>
            <span class="text-danger error-text text_desc_category_error"  style="font-size: 13px"></span>
          </div>

          <div class="form-group">
            <label for="">Image</label>
            <div class="mb-2  text-muted" style="font-size: 13px">* Noted : Image untuk category</div>
            <input type="file" id="image" class="form-control" name="image" placeholder="image Category in here ..." id="title">
          
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
            <button class="btn btn-success" id="btn-store" data-store="{{ route('admin.category.store') }}" data-toggle="modal" data-target="#exampleModal"><i class="mdi mdi-plus"></i> Add Category</button>
          </div>
          <div class="card-body">
              <div class="table-responsive">
                  
              <table id="example" class="table table-striped table-borderedless" style="width:100%">
                <thead class="thead-light">
                  <tr>
                    <th style="width:3%">No</th>
                    <th>Slug</th>
                    <th>Name</th>
                    <th>Image Category</th>
                    <th>Title Category</th>
                    <th>Text</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                  @php
                      $no = 1;
                  @endphp
                    @foreach ($categories as $category)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td class="text-sm">{{ $category->slug }}</td>
                        <td class="text-sm">{{ $category->name }}</td>
                        <td> 
                            @if ($category->image != NULL)
                                <img src="{{ $category->image }}" width="100" height="100" style="object-fit: contain;border-radius:1rem">
                                {{-- {{ $category->image }} --}}
                            @else 
                                <span class="badge badge-warning">No Image</span>
                            @endif
                        </td>
                        <td>{{ $category->title_category }}</td>
                        <td>{{ $category->text_desc_category }}</td>
                        <td>
                            <button data-url="{{ route('admin.category.edit',$category) }}" data-update="{{ route('admin.category.update',$category) }}" class="btn btn-warning btn-sm" id="btn-edit"><i class="mdi mdi-pencil"></i></button>
                            <button id="btn-delete" data-delete="{{ route('admin.category.destroy',$category) }}" class="btn btn-danger btn-sm"><i class="mdi mdi-delete"></i></button>
                          </td>
                    </tr>
                    @endforeach
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
</script>
{{-- <script src="{{ asset('js-app/category/crud.js') }}"></script> --}}

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
                    'You data is successfuly!',
                    'success'
                    )   
                    $('#exampleModal').modal('hide');
                    $(this).trigger("reset");
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

        $('.error-text').text('');
        
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
                $('#name').val(res.category.name);
                $('#title_category').val(res.category.title_category);
                $('#text_desc_category').val(res.category.text_desc_category);
                
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