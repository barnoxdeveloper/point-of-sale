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
								<h3>Order ID : TRX-1234</h3>
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
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="total-bayar">Total Bayar*</label>
										<input type="text" name="total_bayar" id="total-bayar" required class="form-control" placeholder="Total Bayar" value="0">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="kembalian">Kembalian</label>
										<input type="hidden" id="kembalian" readonly>
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
					@if(session('success-invoices'))
						Swal.fire({
							icon: 'success',
							title: 'Your Data has been Saved!',
							showCancelButton: true,
							confirmButtonText: 'Data Orders',
							cancelButtonText: `Stay Here`,
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

					// onkeyup pendapatan
					$('#total-bayar').on("keyup", function () {
						let totalBayar = $('#total-bayar').val();
						let discount = $('#discount').val();
						let grandTotal = {{ $grandTotal }};
						let kembalian = $('#kembalian').val();
						let total = parseInt(totalBayar.replaceAll(",", "")) - (parseInt(grandTotal) - parseInt(discount.replaceAll(",", "")));
						if (!isNaN(total)) {
							$('#kembalian-preview').text('Rp. '+total.toLocaleString());
							$('#kembalian').val(total);
						}
					});
				});
		</script>
@endpush
