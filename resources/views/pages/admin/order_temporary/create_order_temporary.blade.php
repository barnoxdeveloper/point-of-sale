@extends('layouts.admin_layout')
@section('title', $title)
@section('admin_content')

	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $title }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('order.index') }}">Data Order</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
		</div>
		<section class="content">
			<div class="container-fluid p-3">
				<div class="card" data-aos="fade-up">
					<div class="card-header">
						<div class="row">
							<div class="col-md-6">
								<h4>Order ID : {{ $orderId }}</h4>
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="text-center mb-3 mt-3">
							<form action="{{ route('order-temporary.store') }}" method="POST">
								@csrf
								<div class="row justify-content-center">
									<div class="col-md-4">
										<div class="form-group">
											<input list="product" autofocus="autofocus" required name="product" class="form-control" placeholder="Scan / Masukan ID atau Nama Product">
											<datalist id="product">
												@foreach($products as $item)
												<option value="{{ $item->product_code }}">{{ $item->name }}</option>
												@endforeach
											</datalist>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<button type="submit" class="btn btn-primary btn-block"><i class="fas fa-cart-plus"></i></button>
										</div>
									</div>
								</div>
							</form>
						</div>
						<div class="dropdown-divider"></div>
						<div class="row mb-3">
							<div class="col-12 text-center">
								<h2>Daftar Belanja</h2>
							</div>
							<div class="col-12">
								<div class="table-responsive">
									<table class="table table-bordered table-striped text-center w-100" id="table-data">
										<thead>
											<tr>
												<th>#</th>
												<th>Product</th>
												<th>Harga</th>
												<th width="20%">QTY</th>
												<th>Sub Total</th>
												<th>Hapus</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($items as $item)
												<tr>
													<td>{{ $i++ }}</td>
													<td>{{ $item->product_name }}</td>
													<td>Rp. {{ number_format($item->price,0,",",".") }}</td>
													<td>
														<form action="{{ route('order-temporary.update', encrypt($item->id)) }}" method="POST">
															@csrf
															@method('PUT')
															<div class="row">
																<div class="col-md-6">
																	<input type="number" name="quantity" required class="form-control" value="{{ $item->quantity }}">
																</div>
																<div class="col-md-2">
																	<button type="submit" title="Update" class="btn btn-sm btn-success">
																		<i class="fa fa-2xs fa-plus"></i>
																	</button>
																</div>
															</div>
														</form>
													</td>
													<td>Rp. {{ number_format($item->sub_total,0,",",",") }}</td>
													<td>
														<form action="{{ route('order-temporary.destroy', encrypt($item->id)) }}" method="POST">
															@csrf
															@method('DELETE')
															<button type="submit" title="Hapus" class="btn btn-sm btn-danger"><i class="far fa-2xs fa-trash-alt"></i></button>
														</form>
													</td>
												</tr>
											@endforeach
										</tbody>
										<tfoot>
											<tr>
												<td colspan="4">Grand total</td>
												<td>Rp. {{ number_format($grandTotal,0,",",",") }}</td>
												<td></td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
						<div class="dropdown-divider"></div>
						{{-- @if ($errors->any())
							<div class="alert alert-danger">
								<ul>
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						@endif --}}
						<form action="{{ route('order.store') }}" method="POST">
							@csrf
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="grand-total">Grand Total</label>
										<p class="form-control">Rp. {{ number_format($grandTotal,0,",",",") }}</p>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="discount">Discount</label>
										<input type="text" name="discount" id="discount" class="form-control" maxlength="11" placeholder="Discount" value="0">
										@error('discount')<div class="text-danger">{{ $message }}</div>@enderror
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="total-bayar">Total Bayar*</label>
										<input type="text" name="total_bayar" id="total-bayar" required class="form-control @error('total_bayar') is-invalid @enderror" placeholder="Total Bayar" value="0">
										@error('total_bayar')<div class="text-danger">{{ $message }}</div>@enderror
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="kembalian">Kembalian</label>
										<p class="form-control" id="kembalian-preview">Rp. 0</p>
									</div>
								</div>
								{{-- <div class="col-md-3">
									<div class="form-group">
										<label for="description">Descriptions</label>
										<input type="text" name="description" class="form-control" placeholder="Descriptions">
									</div>
								</div> --}}
							</div>
							<div class="row justify-content-center">
								<div class="col-md-3">
									<div class="form-group">
										<label for="store-id">Store*</label>
										<select class="form-control select2" name="store_id" id="store-id" required style="width: 100%;">
											<option value="" selected disabled>Select Store</option>
											@foreach($stores as $item)
											<option {{ old('store_id') == $item->id ? "selected" : "" }} value="{{ $item->id }}">{{ $item->name }}</option>
											@endforeach
										</select>
										<p class="text-danger error-text store_id_error"></p>
									</div>
								</div>
							</div>
							<div class="row justify-content-center">
								<div class="col-md-3">
									<div class="form-group">
										<button type="submit" class="btn btn-success btn-block"><i class="fas fa-money-check"></i>&nbsp; Bayar</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</section>
	</div>
@endsection

@push('style-table')
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css"/>
		<style>
			.table th, td{
				font-size: 12px;
				text-align: center;
			}
		</style>
@endpush

@push('script-table')
			<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
			<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
			<script type="text/javascript">
				$(document).ready(function (){
					$('#table-data').DataTable({
						lengthChange: false,
						searching: false,
						paging: false,
					});
					@if (session('success'))
						Swal.fire({
							position: 'top-end',
							icon: 'success',
							title: '{{ session('success') }}',
							timer: 1500
						});
					@endif
					@if (session('failed'))
						Swal.fire({
							position: 'top-end',
							icon: 'error',
							title: '{{ session('failed') }}',
							timer: 1500
						});
					@endif
					// sweet alert success start
					@if(session('success_invoices'))
						Swal.fire({
							icon: 'success',
							title: 'Transaksi Berhasil!',
							showCancelButton: true,
							confirmButtonText: 'Data Order',
							cancelButtonText: `Tetap Disini`,
							html: `<a href="{{ route('print-invoice', session('success_invoices.order_id')) }}" class="btn btn-md btn-success" target="_blank"><i class="fa fa-print"></i></a>`,
						}).then((result) => {
							if (result.isConfirmed) {
								window.location = "{{ route('order.index') }}";
							}
						});
					@endif
					// sweet alert success end
					function updateTextView(_obj) {
						let num = getNumber(_obj.val());
						if (num == 0) {
							_obj.val("");
						} else {
							_obj.val(num.toLocaleString());
						}
					}
					function getNumber(_str) {
						let arr = _str.split("");
						let out = new Array();
						for (let cnt = 0; cnt < arr.length; cnt++) {
							if (isNaN(arr[cnt]) == false) {
								out.push(arr[cnt]);
							}
						}
						return Number(out.join(""));
					}
					$("input[type=text]").on("keyup", function () {
						updateTextView($(this));
					});
					$('#total-bayar').on("keyup", function () {
						
					});
					// onkeyup pendapatan
					$('#total-bayar, #discount').on("keyup", function () {
						let totalBayar = $('#total-bayar').val();
						let grandTotal = {{ $grandTotal }};
						let discount = $('#discount').val();
						if (totalBayar == '') {
							newTotalBayar = $('#total-bayar').val("0,0");
							$('#kembalian-preview').text("Rp. 0");
						}
						if (discount == '') {
							newDiscount = $('#discount').val("0,0");
						}
						let total = parseInt($('#total-bayar').val().replaceAll(",", "")) - (parseInt(grandTotal) - parseInt($('#discount').val().replaceAll(",", "")));
						if($('#total-bayar').val() == '0,0') {
							$('#kembalian-preview').text("Rp. 0");
						} else if (!isNaN(total)) {
							$('#kembalian-preview').text("Rp. "+total.toLocaleString());
						} 
					});
				});
		</script>
@endpush

@push('style-select2')

	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('script-select2')

		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
		<script>
			$(document).ready(function() {
				$('.select2').select2();
			});
		</script>
@endpush
