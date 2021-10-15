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
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Create Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="justify-content-center d-flex">
          <img style="margin 0 auto;" src="{{ asset('assets/img/icon/product.jpg') }}" alt="" width="60%">
  
        </div>
        <form action="" method="POST" enctype="multipart/form-data" id="formData">
          {{-- @method('put') --}}
          @csrf

          <div class="form-group">
            <label for="">Choose Category</label>
            <div class="mb-2  text-muted" style="font-size: 13px">* Noted : Silahkan pilih beberapa category untuk product</div>
            <select id="select2" class="form-control select2" name="category_id[]" multiple>
                {{-- <option value="">--</option> --}}
                
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
          
            <span class="text-danger error-text category_id_error"  style="font-size: 13px"></span>
          </div>
          <hr>
          <div class="form-group">
            <label for="">Product Name</label>
            <div class="mb-2  text-muted" style="font-size: 13px">* Noted : Silahkan tulis nama product</div>
            <input type="text" id="title" class="form-control" name="title" placeholder="Name Product in here ...">
          
            <span class="text-danger error-text title_error"  style="font-size: 13px"></span>
          </div>  
          
          <div class="form-group">
            <label for="">Color Code</label>
            <div class="mb-2  text-muted" style="font-size: 13px">* Noted : Silahkan tulis code color product Example : #0000 (warna hitam) </div>
            {{-- <input type="text" id="color_name" class="form-control" name="color_name" placeholder="Product color code ...">
           --}}
            <select class="form-control select2" id="color_id" multiple  name="color_id[]">
                <option>---</option>

                @foreach($colors as $color)
                    <option value="{{$color->id}}">{{ $color->color_name }}</option>
                @endforeach
            </select>
            <span class="text-danger error-text color_id_error"  style="font-size: 13px"></span>
          </div> 
          
          <div class="form-group">
            <label for="">Product Size</label>
            <div class="mb-2  text-muted" style="font-size: 13px">* Noted : Silahkan tulis Size of Product (Example : 43,42,41) </div>
            {{-- <input type="text" id="sizes_name" class="form-control" name="sizes_name" placeholder=" Size of Product ..."> --}}
            <select class="form-control select2" id="size_id" multiple name="size_id[]">
                <option>---</option>

                @foreach($sizes as $size)
                    <option value="{{$size->id}}">{{ $size->sizes_name }}</option>
                @endforeach
            </select>
            <span class="text-danger error-text size_id_error"  style="font-size: 13px"></span>
          </div>  

          <div class="form-group">
            <label for="">Description Product</label>
            {{-- <input type="text" id="content" class="form-control" name="content" placeholder="Name Descrption in here ..." id="title"> --}}
            <textarea class="form-control editor1" id="content" name="content" placeholder="Descrption in here ..."></textarea>
            <span class="text-danger error-text content_error"  style="font-size: 13px"></span>
          </div>

          <div class="form-group">
            <label for="">Weight</label>
            <div class="mb-2  text-muted" style="font-size: 13px">* Noted : Silahkan tulis Weight Product</div>
            <input type="text" id="weight" class="form-control" name="weight" placeholder="Weight in here ..." >
          
            <span class="text-danger error-text weight_error"  style="font-size: 13px"></span>
          </div> 

          <div class="form-group">
            <label for="">Price</label>
            <div class="mb-2  text-muted" style="font-size: 13px">* Noted : Silahkan tulis Price Product</div>
            <input type="number" id="price" class="form-control" name="price" placeholder="Price in here ..." value="0">
          
            <span class="text-danger error-text price_error"  style="font-size: 13px"></span>
          </div> 


          <div class="form-group">
            <label for="">Discount</label>
            <div class="mb-2  text-muted" style="font-size: 13px">* Noted : Silahkan tulis Discount Product (satuan %) Example : 10 (sama dengan 10%)</div>
            <input type="number" onkeyup="priceDiscount()" id="discount" class="form-control" name="discount" placeholder="Discount in here ...">
          
            <span class="text-danger error-text discount_error"  style="font-size: 13px"></span>
          </div> 

          <div class="form-group">
            <label for="">End of Discount</label>
            <div class="mb-2  text-muted" style="font-size: 13px">* Noted : Silahkan masukan tanggal discount selesai</div>
            <input type="datetime-local" id="end_of_discount" class="form-control" name="end_of_discount">
          
            <span class="text-danger error-text end_of_discount_error"  style="font-size: 13px"></span>
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
            <button class="btn btn-success" id="btn-store" data-store="{{ route('admin.product.store') }}" data-toggle="modal" data-target="#exampleModal"><i class="mdi mdi-plus"></i> Add Product</button>
            <a href="{{ route('sizecolor.index') }}" class="btn btn-primary text-white"><i class="mdi mdi-plus"></i> Add Size / Color Product</a>
       </div>
          <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-borderedless" style="width:100%">
                        <thead class="thead-light">
                          <tr>
                            <th style="width:3%">No</th>
                            <th>Slug</th>
                            <th>Name</th>
                            {{-- <th>Description</th> --}}
                            <th>Weight (Gram)</th>
                            <th>Price</th>
                            <th>Discount</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                          @php
                              $no = 1;
                              
                               
                          @endphp
                            @foreach ($products as $product)
                            @php
                                $priceAfterDiscount = $product->price;

                                if((int) $product->discount != 0){
                                    $discount = ($product->discount / 100) * $product->price;
                                    $priceAfterDiscount = $product->price - $discount;
                                }
                            @endphp
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td class="text-sm">{{ $product->slug }}</td>
                                <td class="text-sm">{{ $product->title }}</td>
                                {{-- <td class="text-sm">{{ $product->content }}</td> --}}
                                <td class="text-sm">{{ $product->weight }} Gram</td>
                                <td class="text-sm text-black">Rp.
                                    @if ($product->discount)
                                        <span style="color:red;text-decoration:line-through;padding-right:7px">
                                            <span style='color:#525f7f'>{{ number_format($product->price) }}</span>
                                        </span>
                                    @endif

                                    <span>{{  number_format($priceAfterDiscount) }}</span>
                                     
                                    
                                </td>
                                <td class="text-sm">{{ $product->discount }} %</td>
                                <td>
                                    <a href="{{ route('admin.product.createStock',$product) }}" class="btn btn-danger btn-sm"><i class="mdi mdi-plus"></i> Add Stock</a>
                                    <button data-url="{{ route('admin.product.edit',$product) }}" data-update="{{ route('admin.product.update',$product) }}" class="btn btn-warning btn-sm" id="btn-edit"><i class="mdi mdi-pencil"></i></button>
                                    <button id="btn-delete" data-delete="{{ route('admin.product.destroy',$product) }}" class="btn btn-danger btn-sm"><i class="mdi mdi-delete"></i></button>
                                    <a href="{{ route('admin.product.preview',$product) }}" class="btn btn-sm btn-info"><i class="mdi mdi-eye"></i></a>
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
    let editors;

    ClassicEditor
        .create( document.querySelector( '.editor1' ) )
        .then(editor => {
            window.editor = editor;
            editors = editor;
        })
        .catch( error => {
            console.error( error );
        } );


    $(document).ready(function() {
        $('.select2').select2({
            theme: "bootstrap",
        });
    });

    $('button#btn-store').click(function() {
        let data_store = $(this).data('store');
        $('#formData').attr('action',data_store);
        $("#formData").trigger('reset');
        $('#select2').val([]).change();
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
            
            let arr = [];

            for(let i= 0; i < res.categories.length;i++){
                for(let x=0; x < res.categoryByProduct.length; x++){
                    if(res.categories[i].id == res.categoryByProduct[x].id){
                        arr.push(res.categories[i].id);
                        // $('#select2').select2('val',["10","8"]);
                        // console.log(res.categories[i].id);
                    }
                }
            }
            
            let arrCol = [];
            for(let i = 0; i < res.productColorSize.colors.length;i++){
                arrCol.push(res.productColorSize.colors[i].id);
                // console.log(res.productColorSize.colors[i].id);
            }
            
            let arrSiz = [];
            
            for(let i = 0; i < res.productColorSize.sizes.length;i++){
                arrSiz.push(res.productColorSize.sizes[i].id);
                // console.log(res.productColorSize.colors[i].id);
            }
            
            $('#select2').val(arr).change();
            $('#color_id').val(arrCol).change();
            $('#size_id').val(arrSiz).change();
            editors.setData(res.product.content);
            $('#stock').val(res.product.stock);
            $('#title').val(res.product.title);
            $('#weight').val(res.product.weight);
            $('#price').val(res.product.price);
            $('#discount').val(res.product.discount);

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