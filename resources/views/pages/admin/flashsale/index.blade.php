@extends('layouts.app-new')
@push('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" type="text/css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css" type="text/css">  
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('css/select2-bootstrap.css') }}">

@endpush
@section('content')
<style>
.ck-editor__editable_inline {
    min-height: 200px;
}

</style>
<!-- Modal -->
<div class="modal fade" id="modal-product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Product - <span class="flashsale-title text-uppercase"></span></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                
            <table class="table table-striped  table-hover table-responsive d-table" style="width: 100%">
                <thead>
                     <tr>
                         <th>#</th>
                         <th>Product Name</th>
                         <th>Action</th>
                     </tr>
                </thead>
                <tbody id="tbody">
                  
                </tbody>
             </table>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button"  class="btn btn-secondary" data-dismiss="modal">Close</button>
          {{-- <button type="submit" id="btn-create" class="btn btn-primary">Save changes</button> --}}
        </div>
        
      </div>
    </div>
  </div>
<div class="row">
  <div class="col-xl-12">
      <div class="card">
          <div class="card-header">
            <div class="content-flashsale-info">
                <h4>Flashsale Deal Product</h4>
                <small>Create Flashsale Product to show costumers these feature.</small>
            </div>
            <hr>
            
            @if ($showButtonCreate || !$flashsale_end)
                <a href="{{ route('admin.flashsale.create') }}" class="btn" style="background: #ff9800;color:white"><i class="mdi mdi-plus"></i> Add Flashsale</a>
            @else 
                <button class="btn btn-warning">Flashsle still ongoing</button>
                <br>
                <div class="mt-3"><h5>Note : Flashsale masih berjalan silahkan tunggu sampai flashsale selesai.</h5></div>
            @endif
            
            </div>

          <div class="card-body">
                <div class="table-responsive">
                    <table id="table" class="table table-striped table-borderedless" style="width:100%">
                        <thead class="thead-light">
                          <tr>
                            <th style="width:3%">No</th>
                            <th>Title (Optional)</th>
                            <th>Product Name</th>
                            <th>Description (Optional)</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Banner</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($flashsales as $flashsale)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $flashsale->title }}</td>
                                <td><button class="btn btn-primary btn-sm" id="btn-product" data-url="{{ route('admin.flashsale.product',$flashsale) }}"><i class="mdi mdi-shopping"></i> Products</button></td>
                                <td>{{ $flashsale->description }}</td>
                                <td>{{ $flashsale->start_flashsale }}</td>
                                <td>{{ $flashsale->end_flashsale }}</td>
                                <td><span class="badge badge-success">{{ $flashsale->status }}</span></td>
                                <td>
                                    @if ($flashsale->banner == NULL)
                                      <span class="text-muted">  Tidak ada Banner</span>
                                    @else 
                                        <img src="{{ Storage::url($flashsale->banner) }}" alt="">
                                    @endif
                                </td>
                                <td>
                                    <a href="" class="btn btn-sm btn-danger"><i class="mdi mdi-delete"></i> Non Aktif</a>
                                    <a href="{{ route('admin.flashsale.edit',$flashsale) }}" class="btn btn-sm btn-warning"><i class="mdi mdi-pencil"></i> Edit</a>
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

<script>


</script>
<script>
    $(document).ready(function() {
        $('#table').DataTable({
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
</script>
{{-- <script src="{{ asset('js-app/product/crud.js') }}"></script> --}}

<script>
 

    $('button#btn-product').click(function(e) {
        let url = $(this).data('url');
        
        $('#modal-product').modal('show');

        e.preventDefault();

        
        $.ajax({
            url: url,
            type:'GET',  
            beforeSend:function(){
                $('#loading').removeClass('d-none');   
                $('#tbody').html('<td colspan="3"><i class="mdi mdi-spin mdi-loading"></i> Loading ....</td>')
                
            },
            complete:function(){
                $('#loading').addClass('d-none');
            },
            success:function(res){ 
                console.log(res);
                let products_flashsale = res.products_flashsale;
                let viewHtml = '';
                for(let i =0; i < products_flashsale.length;i++){
                   

                    viewHtml +=  `
                        <tr>
                            <td>`+ (i+1) +`</td>
                            <td>`+ products_flashsale[i].product.title+`</td>
                            <td><a href='' class='btn btn-primary btn-sm'><i class='mdi mdi-eye'></i> Detail</a></td>
                        </tr>
                    `;

                }

                $('.flashsale-title').text(res.flashsale.title);
                $('#tbody').html(viewHtml)
                
                
            }
        })

    })
</script>
@endpush