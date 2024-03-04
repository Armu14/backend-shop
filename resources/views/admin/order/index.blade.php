@extends('layouts.app', ['title' => 'Orders'])
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold"><i class="fas fa-shopping-cart"></i>ORDERS</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.order.index') }}" method="GET">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="q" placeholder="Cari berdasarkan no.invoice">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>CARI</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col" style="text-align: center; width: 6%">NO.</th>
                                        <th scope="col">NO. INVOICE</th>
                                        <th scope="col">NAMA LENGKAP</th>
                                        <th scope="col">GRAND TOTAL</th>
                                        <th scope="col">STATUS</th>
                                        <th scope="col" style="width: 15%; text-align: center">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($invoices as $no => $invoice)
                                        <tr>
                                            <th scope="col" style="text-align: center;">{{++$no +($invoices->currentPage()-1) * $invoices->perPage() }}</th>
                                            <th>{{ $invoice->invoice }}</th>
                                            <th>{{ $invoice->name }}</th>
                                            <th class="text-center">{{ $invoice->status }}</th>
                                            <th>{{ moneyFormat($invoice->grand_total) }}</th>
                                            <th class="text-center">
                                                <a href="{{ route('admin.order.show', $invoice->id) }}" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-list-ul"></i>
                                                </a>
                                            </th>
                                        </tr>
                                    @empty
                                        <div class="alert alert-danger">
                                            Data Belum Tersedia!
                                        </div>
                                    @endforelse
                                </tbody>
                            </table>
                            <div style="text-align: center;">
                                {{ $invoices->links('vendor.pagination.bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection