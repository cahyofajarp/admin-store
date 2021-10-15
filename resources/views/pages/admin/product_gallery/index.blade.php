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
            <p class="font-bold" style="font-weight: bold"><i class="ni ni-bag-17"></i> All Product</p>
            <small>Create an Image in your product to be interesting to customers!</small>
              {{-- <button class="btn btn-success" id="btn-store" data-store="{{ route('admin.product.store') }}" data-toggle="modal" data-target="#exampleModal"><i class="mdi mdi-plus"></i> Add Product</button> --}}
            </div>
            <div class="card-body">
                  <div class="table-responsive">
                      <table id="example" class="table table-striped table-borderedless" style="width:100%">
                          <thead class="thead-light">
                            <tr>
                              <th style="width:3%">No</th>
                              <th>Name</th>
                              <th>Total Pictures</th>
                              <th>Status</th>
                              <th>Action</th>
                          </tr>
                          </thead>
                          <tbody>
                            @php
                                $no = 1;
                                
                                 
                            @endphp
                              @foreach ($products as $product)
                        
                              <tr>
                                  <td>{{ $no++ }}</td>
                                  <td class="text-sm">{{ $product->title }}</td>
                                  <td><span class="badge badge-primary">{{ $product->productGalleries->count() }} Photo</span></td>
                                  <td>
                                      @if ($product->productGalleries->count() > 0 && $product->productgallery_thumbnails)
                                            <button class="btn btn-success btn-sm">OK</button>
                                        @else 
                                        <button class="btn btn-warning btn-sm">Belum Lengkap</button>
                                      @endif
                                  </td>
                                  <td>
                                      {{-- <button data-url="{{ route('admin.product.edit',$product) }}" data-update="{{ route('admin.product.update',$product) }}" class="btn btn-warning btn-sm" id="btn-edit"><i class="mdi mdi-pencil"></i></button>
                                      <button id="btn-delete" data-delete="{{ route('admin.product.destroy',$product) }}" class="btn btn-danger btn-sm"><i class="mdi mdi-delete"></i></button>
                                      <a href="{{ route('admin.product.preview',$product) }}" class="btn btn-sm btn-info"><i class="mdi mdi-eye"></i></a> --}}

                                      <a class="btn btn-primary" href="{{ route('admin.gallery.color',$product) }}"><i class="mdi mdi-plus"></i> Create Gallery</a>
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

<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>

{{-- <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script> --}}
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

</script>
@endpush