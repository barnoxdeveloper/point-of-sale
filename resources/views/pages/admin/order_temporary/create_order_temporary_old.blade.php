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
							<form action="#" method="POST" id="form-cart">
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
									<table class="table table-bordered table-striped w-100" id="table-data">
										<thead>
											<tr class="text-center">
												<th>#</th>
												<th>Product</th>
												<th>Harga</th>
												<th width="20%">QTY</th>
												<th>Sub Total</th>
												<th>Hapus</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="dropdown-divider"></div>
						<div class="row">
							<form action="">
								@csrf
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<label for="grand-total">Grand Total</label>
											<p class="form-control">Rp. 2.000</p>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="totalBayar">Total Bayar*</label>
											<input type="number" name="totalBayar" id="total-bayar" required class="form-control" placeholder="Total Bayar" value="5000">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="kembalian">Kembalian</label>
											<p class="form-control">Rp. 3.000</p>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="descriptions">Descriptions</label>
											<input type="text" name="descriptions" class="form-control" placeholder="Descriptions">
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
			</div>
		</section>
	</div>
@endsection

@push('style-table')
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css"/>
		<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jquery-editable/css/jquery-editable.css" rel="stylesheet"/>
		<style>
			.table th, td{
				font-size: 12px;
			}
		</style>
@endpush

@push('script-table')
			<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
			<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
			<script type="text/javascript">
				$(document).ready(function (){
					$('#table-data').DataTable({
						processing : true,
						serverSide : true,
						pageLength : 25,
						lengthMenu: [
							[10, 25, 50, -1],
							[10, 25, 50, 'All'],
						],
						columnDefs: [ {
							"targets": [0],
							"orderable": false,
							"searchable": false,
						} ],
						ajax : {
							url : "{{ route('order-temporary.index') }}",
							type : 'GET',
						},
						columns: [
							{ data: 'DT_RowIndex', name: 'DT_RowIndex', className: "text-center" },
							{ data: 'productName', name: 'productName', className: "text-center" },
							{ data: 'price', name: 'price', className: "text-center" },
							{ data: 'quantity', name: 'quantity', className: "text-center" },
							{ data: 'subTotal', name: 'subTotal', className: "text-center" },
							{ data: 'action', name: 'action', className: "text-center" },
						],
					});
				});
		</script>
@endpush

@push('modal-post')
		<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jquery-editable/js/jquery-editable-poshytip.min.js"></script>
		<script>$.fn.poshytip={defaults:null}</script>
		<script type="text/javascript">
			$(document).ready(function () {
				$.fn.editable.defaults.mode = 'inline';

				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': '{{ csrf_token() }}'
					}
				});

				$('.update').editable({
					url: "{{ route('order-temporary.store') }}",
					type: 'text',
				});

				// $(document).on('click', '.btn-update-quantity', function () {
				// 	$('.form-quantity').submit(function(e){
				// 		// e.preventDefault();
				// 		let formData = $('.form-quantity');
				// 		// console.info(formData);
				// 		$.ajax({
				// 			url: "{{ route('order-temporary.store') }}",
				// 			data: formData,
				// 			type: 'POST',
				// 			dataType: 'json',
				// 			cache: false,
				// 			contentType: false,
				// 			processData: false,
				// 			beforeSend:function() {
				// 				$(document).find('p.error-text').text('');
				// 			},
				// 			success: function (data) {
				// 				if (data.code == 0) {
				// 					$.each(data.messages, function(prefix, val) {
				// 						$('p.'+prefix+'_error').text(val[0]);
				// 					});
				// 				} else {
				// 					$('#table-data').DataTable().ajax.reload();
				// 					Swal.fire({
				// 						position: 'top-end',
				// 						icon: 'success',
				// 						title: 'Quantity Updated!',
				// 						showConfirmButton: false,
				// 						timer: 1500
				// 					});
				// 				}
				// 			},
				// 			error: function (data) {
				// 				$.each(data.messages, function(prefix, val) {
				// 					$('p.'+prefix+'_error').text(val[0]);
				// 				});
				// 			}
				// 		});
				// 	});
				// });

				// $('.form-quantity').submit(function(e){
				// 	e.preventDefault();
				// });

				// if ($(".form-quantity").length > 0) {
				// 	$(".form-quantity").validate({
				// 		submitHandler: function (form) {
				// 			let formData = new FormData(document.getElementByClassName('form-quantity'));
				// 			console.info(formData);
				// 			$.ajax({
				// 				url: "{{ route('order-temporary.store') }}",
				// 				data: formData,
				// 				type: 'POST',
				// 				dataType: 'json',
				// 				cache: false,
				// 				contentType: false,
				// 				processData: false,
				// 				beforeSend:function() {
				// 					$(document).find('p.error-text').text('');
				// 				},
				// 				success: function (data) {
				// 					if (data.code == 0) {
				// 						$.each(data.messages, function(prefix, val) {
				// 							$('p.'+prefix+'_error').text(val[0]);
				// 						});
				// 					} else {
				// 						$('#table-data').DataTable().ajax.reload();
				// 						Swal.fire({
				// 							position: 'top-end',
				// 							icon: 'success',
				// 							title: 'Quantity Updated!',
				// 							showConfirmButton: false,
				// 							timer: 1500
				// 						});
				// 					}
				// 				},
				// 				error: function (data) {
				// 					$.each(data.messages, function(prefix, val) {
				// 						$('p.'+prefix+'_error').text(val[0]);
				// 					});
				// 				}
				// 			});
				// 		}
				// 	});
				// }

				// $(document).on('click', '.delete', function () {
				// 	dataId = $(this).data('id');
				// 	$.ajax({
				// 		url: "order-temporary/" + dataId,
				// 		type: 'DELETE',
				// 		success: function (data) {
				// 			$('#table-data').DataTable().ajax.reload();
				// 		},
				// 		error: function (data) {
				// 			console.log('Error: ', data);
				// 		}
				// 	});
				// });
			});
		</script>
@endpush