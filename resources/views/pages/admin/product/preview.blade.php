@extends('layouts.app-new')
@push('style')
<link rel="stylesheet" type="text/css" href="{{ asset('xzoom/dist/xzoom.css') }}" media="all" /> 
@endpush
@section('content')

<style>
.preview-product .text-title h4{
    display: block;
    margin-bottom: 8px;
    font-size: 20px !important;
    word-break: break-word;
}
.section-stock p{
    font-size: 14px!important;
    line-height: 1.4!important;
}
.product-desc p ,.product-desc ul{
    color: black;
    font-size: 14px !important;
    line-height: 1.8 !important;
    font-weight: 400 !important;
}
.gallery .xzoom-container{
    display: block;
    width: 400px;
    object-fit: contain;
}

.gallery .xzoom-container .xzoom{
    box-shadow: none;
    width: 100%;
    object-fit: contain;
    margin-bottom: 10px;
    height: 400px;
}

.gallery .xzoom-container .xactive{
    border:4px solid #1abc9c;
    box-shadow: none;
}

</style>
<div class="row preview-product">
  <div class="col-xl-12">
      <div class="card">
          <div class="card-body">
             <div class="row">
                 <div class="col-md-5">   <!-- default start -->
                <section id="default" class="padding-top0">
                    <div class="row gallery">
                      <div class="large-5 column">
                        <div class="xzoom-container">
                          <img class="xzoom" id="xzoom-default" width="500" src="{{ $product->productGalleries->first()->image  }}" xoriginal="{{ $product->productGalleries->first()->image  }}" />
                          <div class="xzoom-thumbs">
                            @foreach ($product->productGalleries as $gallery)
                                  <a href="{{ $gallery->image }}"><img class="xzoom-gallery" width="80" src="{{ $gallery->image }}"  xpreview="{{ $gallery->image }}" title="{{ $gallery->title }}"></a>
                          
                            @endforeach
                            {{-- <a href="{{ asset('images/gallery/original/02_o_car.jpg') }}"><img class="xzoom-gallery" width="80" src="{{ asset('images/gallery/preview/02_o_car.jpg') }}" title="The description goes here"></a>
                            <a href="{{ asset('images/gallery/original/03_r_car.jpg') }}"><img class="xzoom-gallery" width="80" src="{{ asset('images/gallery/preview/03_r_car.jpg') }}" title="The description goes here"></a>
                            <a href="{{ asset('images/gallery/original/04_g_car.jpg') }}"><img class="xzoom-gallery" width="80" src="{{ asset('images/gallery/preview/04_g_car.jpg') }}" title="The description goes here"></a>
                          </div> --}}
                        </div>        
                      </div>
                      <div class="large-7 column"></div>
                    </div>
                    </section>
                    <!-- default end -->
                 </div>
                 <div class="col-md-7">
                     <div class="text-title mt-3 ml-3">
                         <h4>{{ $product->title }}</h4>
                     </div>
                     <div class="mt-5 text-price ml-3">
                       <div class="text-discount h5" style="color:#bbb;font-weight:400">
                            
                            @php
                                $priceAfterDiscount = $product->price;

                                if((int) $product->discount != 0){
                                    $discount = ($product->discount / 100) * $product->price;
                                    $priceAfterDiscount = $product->price - $discount;
                                }
                            @endphp
                            @if ($product->discount)
                            <span style="color:#bbb;text-decoration:line-through;padding-right:7px">
                                <span style='color:#bbb'>Rp.{{ number_format($product->price) }}</span>
                                </span>
                            @endif
                        </div>
                        <div style="color:#d71149;font-size: 20px;font-weight: 700;">Rp.{{  number_format($priceAfterDiscount) }} 
                            @if ($product->discount != 0)
                                <span style="margin-left: 4px;font-size: 12px;font-weight:400">Hemat {{ $product->discount }} %</span>
                            @endif
                        </div>

                        <div class="section-stock mt-3">
                            <p>Tersedia > <b>{{ $product->stock }}</b> stock barang</p>
                        </div>
                     </div>
                 </div>
             </div>
          <div>
      </div>
  </div>
</div>

<div class="row">

    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="text-center py-3 px-3">Informasi Product</h5>
                        
                    </div>

                     <div class="col-md-6" style="">
                        <div class="product-desc">
                             <p>{!! $product->content !!}</p>
                        </div>
                     </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
 {{-- <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js "></script>
  <script src=" https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
  
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script> --}}

<script type="text/javascript" src="{{ asset('xzoom/dist/xzoom.min.js') }}"></script>

<script>
$(document).ready(function() {
    $('.xzoom, .xzoom-gallery').xzoom({
        zoomWidth: 400, 
        title: true, 
        tint: '#333', 
        Xoffset: 15,
        defaultScale: 1, 
        fadeIn: true, adaptive: true,
    
    });
});
</script>
@endpush