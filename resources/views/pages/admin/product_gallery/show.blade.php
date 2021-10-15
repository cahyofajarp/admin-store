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
                <h3>All Product Image - {{ $product->title }}</h3>
                <a href="{{ route('admin.gallery.create',[$product,$color]) }}" class="btn btn-success"><i class="ni ni-image"></i> Add Photo</a>
            </div>
            <div class="card-body">
                  <div class="table-responsive">
                      <table id="example" class="table table-striped table-borderedless" style="width:100%">
                          <thead class="thead-light">
                            <tr>
                              <th style="width:3%">No</th>
                              <th>Title</th>
                              <th>Image</th>
                              <th>Status</th>
                              <th>Action</th>
                          </tr>
                          </thead>
                          <tbody>
                              @php
                                  $no =1;
                              @endphp
                            @forelse ($productGallery as $gallery)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $gallery->title }}</td>
                                    <td><img src="{{ $gallery->image }}" alt="" width="100" height="100" style="object-fit:cover;border-radius:1rem;"></td>
                                    <td>
                                        @if ($gallery->productgallery_thumbnails->count() > 0 )
                                           <div class="text-center"> <i class="mdi mdi-check-circle"></i> Thumbnail</div>
                                        @else 
                                           <div class="icon-not-thumbnail text-center" style="font-size: 2rem">-</div>
                                        @endif
                                    </td>
                                    <td>
                                        <button id="btn-delete" data-delete="{{ route('admin.gallery.destroy',$gallery) }}" class="btn  btn-danger"><i class="mdi mdi-delete"></i></button>
                                        <button id="btn-choose" data-id="{{ $gallery->id }}" data-create="{{ route('admin.gallery.createThumbnail',[$product,$color]) }}" class="btn btn-success"> <i class="mdi mdi-check"></i> Pilih Thumbnail</button>
                                    </td>
                                </tr>
                            @empty
                            <tr>
                                <td colspan="4">No data.</td>
                            </tr>
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

<script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
<script>


</script>
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
{{-- <script src="{{ asset('js-app/product/crud.js') }}"></script> --}}

<script>
  
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
                    if(response.success == true){
                          Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                        location.reload();
                    }
                    console.log(response);
                        
                    },
                    error:function(err){
                        console.log(err);
                    }
                });
            }
        })
    });

    $('button#btn-choose').click(function(e) {
        e.preventDefault();

        let product_galleries_id = $(this).data('id');
        let dataDelete = $(this).data('create');

        Swal.fire({
        title: 'Anda yakin memilih ini menjadi thumbnail?',
        text: "Pilih thumbnail !",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, I choose this!'
        }).then((result) => {
            if (result.isConfirmed) {
              
                $.ajax({
                    type:'POST',
                    url:dataDelete,
                    data:{
                        product_galleries_id :product_galleries_id,
                        _token:"{{ csrf_token() }}",

                    },
                    success:function(response){
                        if(response.success == true){
                            Swal.fire(
                            'Success!',
                            'Sukses memilih thumbnail terimakasih.',
                            'success'
                            )
                            location.reload();
                        }
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