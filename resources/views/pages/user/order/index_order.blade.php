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
								@if (Auth::user()->is_roles_cashier)
								<a href="{{ route('order-temporary-user.create') }}" class="btn btn-sm btn-success" id="btn-create">
									+ Create Order
								</a>
								@endif
							</div>
							<div class="col-md-6">
								<!-- Date range -->
								{{-- <form action="" id="daterange-form"> --}}
									<div class="row justify-content-end">
										<div class="col-8">
											<div class="form-group">
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text">
															<i class="far fa-calendar-alt"></i>
														</span>
													</div>
													<input type="text" class="form-control float-right" required name="reservation" id="reservation">
												</div>
												<input type="hidden" name="startDate" id="start-date">
												<input type="hidden" name="endDate" id="end-date">
											</div>
										</div>
										<div class="col-4">
											<div class="form-group">
												<button type="button" name="filter" id="filter" class="btn btn-primary" title="Get Data">
													<i class="far fa-paper-plane"></i>
												</button>
												<button type="button" name="refresh" id="refresh" class="btn btn-warning" title="Reset">
													<i class="fas fa-sync"></i>
												</button>
											</div>
										</div>
									</div>
								{{-- </form> --}}
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id="table-data" class="table table-bordered table-striped w-100">
								<thead>
									<tr class="text-center">
										<th>#</th>
										<th>Date</th>
										<th>ID</th>
										<th>User</th>
										<th>Total</th>
										<th>Actions</th>
									</tr>
								</thead>
                                <tbody></tbody>
                                <tfoot>
									<tr>
										<th colspan="4">Total</th>
										<th id="total-all"></th>
										<th>-</th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>

	{{-- modal detail --}}
	<div class="modal fade" id="detail-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="static-backdrop-label" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="static-backdrop-label"></h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<p id="detail-date"></p>
					<p id="detail-store"></p>
					<p id="detail-user"></p>
					<p id="detail-total"></p>
					<p id="detail-discount"></p>
					<p id="detail-grand-total"></p>
					<p id="detail-total-bayar"></p>
					<p id="detail-kembalian"></p>
					<p id="detail-order"></p>
				</div>
			</div>
		</div>
	</div>

@endsection

@push('style-table')

		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css"/>
		<style>
			.table th, td{
				font-size: 12px;
			}
		</style>
@endpush

@push('style-daterange')

		<link rel="stylesheet" href="{{ url('backend/plugins/daterangepicker/daterangepicker.css') }}">
@endpush

@push('script-daterange')

		<script	script src="{{ url('backend/plugins/daterangepicker/daterangepicker.js') }}"></script>
@endpush

@push('script-table')

		<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.12.1/api/sum().js"></script>
		<script type="text/javascript">
			$(document).ready(function () {
				$('#reservation').removeAttr('value');
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
					}
				});
				loadData();
				function loadData(startDate = '', endDate = '') {
					$('#table-data').DataTable({
						processing : true,
						serverSide : true,
						pageLength : 25,
						ajax : {
							url : "{{ route('order-user.index') }}",
							data : {startDate, endDate},
							type : 'GET',
						},
						columns : [
							{ data: 'DT_RowIndex', name: 'DT_RowIndex', className: "text-center" },
							{ data: 'date', name: 'date', className: "text-center" },
							{ data: 'orderId', name: 'orderId', className: "text-center" },
							{ data: 'user', name: 'user', className: "text-center" },
							{ data: 'total', name: 'total', className: "text-center", "render": $.fn.dataTable.render.number( '.', ',', 0, 'Rp ' ) },
							{ data: 'action', name: 'action', className: "text-center" },
						],
						columnDefs : [{
							"targets" : [1, 2, 5],
							"orderable" : false,
							"searchable" : false,
						}],
						lengthMenu : [
							[10, 25, 50, -1],
							[10, 25, 50, 'All'],
						],
						drawCallback: function () {
							let sum = $('#table-data').DataTable().column(4).data().sum();
							$('#total-all').html(`Rp. ${sum.toLocaleString('id-ID')}`);
						}	
					});
				}
				$('#reservation').daterangepicker({
					// autoUpdateInput: false,
					locale: {
						format: 'YYYY-MM-DD',
						separator: " to ",
						cancelLabel: 'Clear'
					}
				});
				$('#reservation').on('cancel.daterangepicker', function(ev, picker) {
					//do something, like clearing an input
					$('#reservation').val('');
				});
				$('#reservation').on('apply.daterangepicker', function (ev, picker) {
					let startDate = picker.startDate;
					let endDate = picker.endDate;
					// format it 
					let start = startDate.format('YYYY-MM-DD');
					let end = endDate.format('YYYY-MM-DD');
					$('#start-date').val(start);
					$('#end-date').val(end);
				});
				$('#filter').click(function() {
					let startDate = $('#start-date').val();
					let endDate = $('#end-date').val();	
					if (startDate !== '' && endDate !== '') {
						$('#table-data').DataTable().destroy();
						loadData(startDate, endDate);
					} else {
						Swal.fire({
							icon: 'error',
							title: 'Oops...',
							text: 'Both Date is Required!'
						});
					}
				});
				$('#refresh').click(function() {
					$('#reservation').val('');
					$('#start-date').val('');
					$('#end-date').val('');
					$('#reservation').data('daterangepicker').setStartDate({});
					$('#reservation').data('daterangepicker').setEndDate({});
					$('#table-data').DataTable().destroy();
					loadData();
				});
			});
		</script>
@endpush

@push('modal-post')
	<script>
		// method create
		$(function() {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
				}
			});
			// method detail
			$(document).on('click', '.btn-detail', function () {
				let dataId = $(this).data('id');
				$.get('/order-user/' + dataId, function (data) {
					$('#detail-modal').modal('show');
					$('.modal-title').text(`Detail Order : ${data.order_id}`);
					const event = new Date(data.date);
					const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
					$('#detail-date').text(`Date : ${event.toLocaleDateString('id-ID', options)}`);
					$('#detail-user').text(`User : ${data.user.name}`);
					$('#detail-total').text(`Total : Rp. ${data.total.toLocaleString()}`);
					$('#detail-discount').text(`Discount : Rp. ${data.discount.toLocaleString()}`);
					$('#detail-grand-total').text(`Grand Total : Rp. ${(data.total - data.discount).toLocaleString()}`);
					$('#detail-total-bayar').text(`Total Bayar : Rp. ${data.total_bayar.toLocaleString()}`);
					$('#detail-kembalian').text(`Kembalian : Rp. ${data.kembalian.toLocaleString()}`);
					let html = '<table class="table table-bordered table-striped w-100"><thead><tr class="text-center"><th>#</th><th>Product</th><th>Price</th><th>QTY</th><th>Sub Total</th></tr></thead><tbody>';
					data.order_detail.forEach((element, index) => html += '<tr class="text-center">' + '<td>' + (index + 1) + '</td>' + '<td>' + element.product_name + '</td>' + '<td> Rp. ' + element.price.toLocaleString() + '</td>' + '<td>' + element.quantity + '</td>' + '<td> Rp. ' + element.sub_total.toLocaleString() + '</td>' + '</tr>');
					html += '</tbody></table>';
					$('#detail-order').html(html);
				});
			});
			// method delete start
			$(document).on('click', '.delete', function () {
				dataId = $(this).data('id');
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
								url: "/order-user/" + dataId,
								type: 'DELETE',
							success: function (data) {
								$('#delete-modal').modal('hide');
								if (data.code == 200) {
									Swal.fire(
										'Saved!',
										'Your Data has been Deleted.',
										'success'
									);
									$('#table-data').DataTable().ajax.reload();
								}
							},
							error: function (data) {
								console.log('Error: ', data);
							}
						});
					}
				});
			});
			// method delete end
		});
	</script>

@endpush
