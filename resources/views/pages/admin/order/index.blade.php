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

<div class="row">
  <div class="col-xl-12">
      <div class="card">
            <div class="card-header">
                <h3>Order Management</h3>

                <div class="float-right d-inline-flex">
                    <div class="date  d-flex">
                        <input type="date" class="form-control">
                        <input type="date" class="form-control ml-3">
                        <button type="submit" class="btn btn-primary btn-sm ml-3">Cari!</button>
                    </div>
                </div>
            </div>
          <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-borderedless" style="width:100%">
                        <thead class="thead-light">
                          <tr>
                            <th style="width:3%">No</th>
                            <th>No Invoice</th>
                            <th>Nama Lengkap</th>
                            <th>Grand Total</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($invoices as $invoice)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $invoice->invoice }}</td>
                                    <td>{{ $invoice->name }}</td>
                                    <td><span class="badge badge-default">{{ $invoice->status }}</span></td>
                                    <td>{{ number_format($invoice->grand_total) }}</td>
                                    <td>
                                        <a href="" class="btn btn-sm btn-success"> <i class="mdi mdi-clipboard-account"></i> Detail</a>
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

</script>
@endpush