@extends('layouts.app-new')
@push('style')
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" type="text/css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css" type="text/css">  
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css"> --}}
<link rel="stylesheet" href="{{ asset('css/select2-bootstrap.css') }}">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.38.0/css/tempusdominus-bootstrap-4.min.css" />
@endpush
@section('content')
<style>
.ck-editor__editable_inline {
    min-height: 200px;
}
.btn-linear-1{
    
    background: linear-gradient(45deg, #03a9f4, #8bc34a);
    border: none;
    color:white;
}
.hover:hover{
    
    transform: translateY(-1px);
    box-shadow: 0 7px 14px rgb(50 50 93 / 10%), 0 3px 6px rgb(0 0 0 / 8%);
}

</style>

<form action="{{ route('admin.flashsale.update',$flashsale) }}" method="post" enctype="multipart/form-data" id="formData">
    @method('PUT')
    @csrf              

<div class="modal fade" id="modal-product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Search Product</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="text-danger d-block h5">* Jika ingin lebih dari satu pencaharian harap confirm terlebih dahulu.</div>
         
            <div class="error-and-loading mb-3">
                <div class="show-not-showing"></div>
                <div class="loader-search d-none"><i class='mdi mdi-spin mdi-loading'></i> Loading ...</div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <input class="form-control" type="text" name="" placeholder="Search Product" id="search">
                    <div class="input-group-append">
                        <span class="input-group-text" id="my-addon"><i class="mdi mdi-search-web"></i></span>
                    </div>
                </div>
            </div>
           <div class="row" id="show">
                <div class="loading d-none"><i class='mdi mdi-spin mdi-loading'></i> Loading ...</div>
            </div>

        </div>

        <div class="modal-footer">
          <button type="button"  class="btn btn-secondary" data-dismiss="modal">Close</button>
          {{-- <button type="submit" id="btn-create" class="btn btn-primary">Save changes</button> --}}
        </div>
        
      </div>
    </div>
</div>
<!-- Modal -->

<!-- Modal -->
<div class="row">
  <div class="col-xl-12">
      <div class="card">
          <div class="card-header">
              <h3>Create Flashsale Product</h3>
          </div>
        <div class="card-body">

                <div class="form-group">
                    <label for="">Title (Optional)</label>
                    <input type="text" name="title" value="{{ old('title',$flashsale->title) }}" class="form-control" placeholder="Title in here ...">
                </div>
                <div class="form-group">
                    <label for="">Description (Optional)</label>
                    <textarea name="description" class="form-control" placeholder="description in here ...">{{ old('desctiption',$flashsale->description )}}</textarea>
                </div>
                <div class="form-group">
                    <label for="">Start Date Flashsale</label>
                    <input type="datetime-local" name="start_flashsale" class="form-control" placeholder="Start Date in here ..." value="{{  \Carbon\Carbon::parse($flashsale->start_flashsale)->format('Y-m-d\TH:i'); }}">
                    <span class="text-danger error-text start_flashsale_error" style="font-size:13px"></span>
                           
                </div>
                <div class="form-group">
                    <label for="">End Date Flashsale</label>
                    <input type="datetime-local" name="end_flashsale" class="form-control" placeholder="End Date in here ..." value="{{ old('end_flashsale', \Carbon\Carbon::parse($flashsale->end_flashsale)->format('Y-m-d\TH:i')) }}">
                    <span class="text-danger error-text end_flashsale_error" style="font-size:13px"></span>
                    
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-success" id="data-product"><i class="mdi mdi-search-web"></i> Search Product Put to Flashsale</button>
                </div>

                <hr>
                <button id="btn-create" data-value="2" type="submit" class="btn btn-block btn-primary"><i class="mdi mdi-send"></i> Save Change</button>
        <div>
      </div>
  </div>
</div>

</form>
@endsection

@push('script')

<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

<script type="text/javascript">
showData();

function showData(){
    $.ajax({
        url : '{{ route("admin.flashsale.edit",$flashsale) }}',
        type: 'GET',  
         beforeSend:function(){
             $('.loading').removeClass('d-none');
        },    
        complete: function(){
             $('.loading').addClass('d-none');
           
        },
        success: function(response){
            
            let viewHtml = '';
            
            for(let i = 0; i < response.products.length;i++){
                viewHtml += `
                <div class="col-md-4 group" id="active-checkbox">
                    `+(response.products[i].flashsale_product ? (response.products[i].id == response.products[i].flashsale_product.product_id  ? '<input type="checkbox" class="check-unchecked" name="product_delete[]" style="display:none" value="'+response.products[i].id +'">' : '') : '')+`
                    
                    <input type="checkbox" class="pr-3 check" id="checkbox-1" name="product_id[]" 
                        value="`+response.products[i].id +`" `+(response.products[i].flashsale_product ? (response.products[i].id == response.products[i].flashsale_product.product_id  ? 'checked' : '') : '')+`>
                    
                    <div class="card d-inline ml-2" style="width: 10rem;box-shadow:none">
                        <img class="card-img-top"  onclick="check($(this))"  src="`+response.products[i].product_galleries[0].image+`" alt="Card image cap" style="height: 100px;object-fit:contain;cursor: pointer;">
                        <div class="card-body">
                        <h5 class="card-title">`+response.products[i].title+`</h5>
                        <label for=""><small>* Discount</small></label>
                        
                        <input type="number" name="discount[]" placeholder="discount ...." 
                                `+(
                                    response.products[i].flashsale_product ? 
                                    (response.products[i].id == response.products[i].flashsale_product.product_id  
                                    ? 'class="form-control discount" value="'+response.products[i].flashsale_product.discount+'" data-value="'+response.products[i].flashsale_product.discount+'"'  
                                    : '') 
                                    : 'class="form-control d-none invisible default-value" value="" data-value="0"')+`    
                        >   
                        <span class="text-danger error-text discount_error" style="font-size:13px"></span>
                        
                            
                        </div>
                    </div>
                </div>

                `;
                    
            }
            
            $('.modal').find('#show').html(viewHtml);
            
        },
        error:function() {
            
        }
    })
}

$('button#data-product').click(function() {
    $('#modal-product').modal('show');
});


function check(test){

    let data_val = test.closest('.group').find('input[data-value]').attr('data-value');
    
   
    if(test.closest('.group').find('.check').is(':checked') == false){
        // alert('sdd');
        
        test.closest('.group').find('input[type=number]').removeClass('d-none').removeClass('invisible');
        test.closest('.group').find('.default-value').attr('value','0');
        test.closest('.group').find('.check').prop('checked',true);
        test.closest('.group').find('.check-unchecked').prop('checked',false);
        test.closest('.group').find('input[type=number]').attr('value',data_val);
        
    }
      else if(test.closest('.group').find('.check').is(':checked') == true){
        test.closest('.group').find('input[type=number]').addClass('d-none').addClass('invisible');
        test.closest('.group').find('.check').prop('checked',false);
        test.closest('.group').find('.default-value').attr('value','');
        test.closest('.group').find('input[type=number]').attr('value','');
        test.closest('.group').find('.check-unchecked').prop('checked',true);
        
       
    }
}

$("#search" ).keyup(function() {
    // $.post($(this));
    let searchKey = $('#search').val();
    $.ajax({
        url : '{{ route("admin.flashsale.search.product",$flashsale) }}',
        type: 'POST',
        data : {
            _token: '{{ csrf_token() }}', search:searchKey
        } ,
         beforeSend:function(){
             $('.loader-search').removeClass('d-none');
        },    
        complete: function(){
             $('.loader-search').addClass('d-none');
        },
        success:function(response) {
            
            if(response.products.length == 0){
                $('.show-not-showing').html('<b>No result for : </b> '+searchKey);
            }
            else{
                $('.show-not-showing').html('');
            }
            console.log(response.products);
            let viewHtml = '';

            for(let i = 0; i < response.products.length;i++){
            viewHtml += `
            <div class="col-md-4 group" id="active-checkbox">
                `+(response.products[i].flashsale_product ? (response.products[i].id == response.products[i].flashsale_product.product_id  ? '<input type="checkbox" class="check-unchecked" name="product_delete[]" style="display:none" value="'+response.products[i].id +'">' : '') : '')+`
                        
                <input type="checkbox"  class="check pr-3" id="checkbox-1" name="product_id[]" 
                
                    value="`+response.products[i].id+`" `+(response.products[i].flashsale_product ? (response.products[i].id == response.products[i].flashsale_product.product_id  ? 'checked' : '') : '')+`>
                
                <div class="card d-inline ml-2" style="width: 10rem;box-shadow:none">
                    <img class="card-img-top"  onclick="check($(this))"  src="`+response.products[i].product_galleries[0].image+`" alt="Card image cap" style="height: 100px;object-fit:contain;cursor: pointer;">
                    <div class="card-body">
                    <h5 class="card-title">`+response.products[i].title+`</h5>
                    <label for=""><small>* Discount</small></label>
                    
                    <input type="number" name="discount[]" placeholder="discount ...." 
                            `+(
                                response.products[i].flashsale_product ? 
                                (response.products[i].id == response.products[i].flashsale_product.product_id  
                                ? 'class="form-control discount" value="'+response.products[i].flashsale_product.discount+'" data-value="'+response.products[i].flashsale_product.discount+'"'  
                                : '') 
                                : 'class="form-control d-none invisible" value="0"')+`    
                    >   
                    
                    <span class="text-danger error-text discount_error" style="font-size:13px"></span>
                    
                        
                    </div>
                </div>
            </div>

            `;

            }
            
            $('.modal').find('#show').html(viewHtml);
        },
        error:function() {

        }
    });
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
                $('#btn-create').removeClass("disabled").html("Save Change").attr('disabled',false);
                
            },
            success: function(res){
                // $("#count").text(res.count);
                if(res.success == true){
                    Swal.fire(
                    'Success!',
                    'You data is successfuly!',
                    'success'
                    )   

                    window.location.href="{{ route('admin.flashsale') }}";

                }
                else{
                    Swal.fire(
                    'Error!',
                    'Check your data!',
                    'error'
                    )  
                }
                console.log(res)
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