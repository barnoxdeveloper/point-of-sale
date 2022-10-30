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
								<a href="{{ route('order.create') }}" class="btn btn-sm btn-success" id="btn-create">
									+ Create Order
								</a>
								<button class="btn btn-sm btn-danger d-none deleteAllBtn" id="delete-all-btn">Delete All</button>
							</div>
							<div class="col-md-6">
								<!-- Date range -->
								{{-- <form action="" id="daterange-form"> --}}
									<div class="row justify-content-end">
										<div class="col-8">
											<div class="form-group">
												{{-- <div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text">
															<i class="far fa-calendar-alt"></i>
														</span>
													</div>
													<input type="text" class="form-control float-right" required name="reservation" id="reservation">

												</div> --}}
												<input type="date" name="start_date" id="start-date">
												<input type="date" name="end_date" id="end-date">
											</div>
										</div>
										<div class="col-4">
											<div class="form-group">
												<a name="filter" id="filter" class="btn btn-primary" title="Get Data">
													<i class="far fa-paper-plane"></i>
												</a>
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
										<th width="5%"><input type="checkbox" name="main_checkbox"><label></label></th>
										<th>#</th>
										<th>Date</th>
										<th>ID</th>
										<th>User</th>
										<th>Descp</th>
										<th>Total</th>
										<th>Actions</th>
									</tr>
								</thead>
                                <tbody></tbody>
                                <tfoot>
									<tr>
										<th colspan="6">Total</th>
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
@endsection

@push('style-table')

		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css"/>
		<style>
			.table th, td{
				font-size: 12px;
			}
		</style>
@endpush

@push('script-table')

		<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.12.1/api/sum().js"></script>
		<script type="text/javascript">
			$(document).ready(function (){
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
							url : "{{ route('order.index') }}",
							data : {startDate, endDate},
							type : 'GET',
							dataType:"json",
						},
						columns: [
							{ data: 'checkbox', name: 'checkbox', className: "text-center"},
							{ data: 'DT_RowIndex', name: 'DT_RowIndex', className: "text-center" },
							{ data: 'date', name: 'date', className: "text-center" },
							{ data: 'orderId', name: 'orderId', className: "text-center" },
							{ data: 'user', name: 'user', className: "text-center" },
							{ data: 'description', name: 'description', className: "text-center" },
							{ data: 'total', name: 'total', className: "text-center", "render": $.fn.dataTable.render.number( '.', ',', 0, 'Rp ' ) },
							{ data: 'action', name: 'action', className: "text-center" },
						],
						columnDefs: [ {
							"targets" : [0, 2, 4],
							"orderable" : false,
							"searchable" : false,
						}],
						// fixedHeader: {
						// 	header: true,
						// 	footer: true
						// },
						lengthMenu: [
							[10, 25, 50, -1],
							[10, 25, 50, 'All'],
						],
						drawCallback: function () {
							let sum = $('#table-data').DataTable().column(6).data().sum();
							$('#total-all').html(`Rp. ${sum.toLocaleString('id-ID')}`);
						}	
					}).on('draw', function () {
						$('input[name="order_checkbox"]').each(function (){
							this.checked = false;
						});
						$('input[name="main_checkbox"]').prop('checked', false);
						$('#delete-all-btn').addClass('d-none');
					});
				}

				$('#filter').click(function() {
					let startDate = $('#start-date').val();
					let endDate = $('#end-date').val();	
					if (startDate !== '' && endDate !== '') {
						console.info(startDate + " - " + endDate );
						$('#table-data').DataTable().destroy();
						loadData(startDate, endDate);
						// console.info(loadData());
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

@push('style-select2')

		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('style-daterange')

		<link rel="stylesheet" href="{{ url('backend/plugins/daterangepicker/daterangepicker.css') }}">
@endpush

@push('script-select2')

		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush

@push('script-daterange')

		<script	script src="{{ url('backend/plugins/daterangepicker/daterangepicker.js') }}"></script>
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
			// Date range picker
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

			$('#reservation').change(function() {
				let awal = $('#reservation').data('daterangepicker').startDate;
				let akhir = $('#reservation').data('daterangepicker').endDate;
				// format it 
				let start = awal.format('YYYY-MM-DD');
				let end = akhir.format('YYYY-MM-DD');
				$('#start-date').val(start);
				$('#end-date').val(end);
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
								url: "order/" + dataId,
								type: 'DELETE',
							success: function (data) {
								$('#delete-modal').modal('hide');
								Swal.fire(
									'Saved!',
									'Your Data has been Deleted.',
									'success'
								);
								$('#table-data').DataTable().ajax.reload();
							},
							error: function (data) {
								console.log('Error: ', data);
							}
						});
					}
				});
			});

			$(document).on('click', 'input[name="main_checkbox"]', function() {
				if (this.checked) {
					$('input[name="order_checkbox"]').each(function () {
						this.checked = true;
					});
				} else {
					$('input[name="order_checkbox"]').each(function () {
						this.checked = false;
					});	
				}
				toggleDeleteAllBtn();
			});

			$(document).on('change', 'input[name="order_checkbox"]', function() {
				if ($('input[name="order_checkbox"]').length == $('input[name="order_checkbox"]:checked').length) {
					$('input[name="main_checkbox"]').prop('checked', true);
				} else {
					$('input[name="main_checkbox"]').prop('checked', false);
				}
				toggleDeleteAllBtn();
			});

			function toggleDeleteAllBtn() {
				if ($('input[name="order_checkbox"]:checked').length > 0) {
					$('#delete-all-btn').text('Delete ('+ $('input[name="order_checkbox"]:checked').length +')').removeClass('d-none');
				} else {
					$('#delete-all-btn').addClass('d-none');
				}
			}
			// method delete end

			$('#delete-all-btn').click(function () {
				let checkedOrder = [];
				$('input[name="order_checkbox"]:checked').each(function () {
					checkedOrder.push($(this).data('id'));
				});
				
				const url = "{{ route('delete-selected-order') }}";
				if (checkedOrder.length > 0) {
					Swal.fire({
						title: 'Are you sure?',
						html: `You want to delete <b>(${checkedOrder.length})</b> order`,
						icon: 'info',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Yes, Delete!',
						allowOutsideClick: false,
					}).then((result) => {
						if (result.value) {
							$.post(url, {id:checkedOrder}, function (data) {
								if (data.code == 1) {
									Swal.fire(
										'Saved!',
										'Your Data has been Deleted.',
										'success'
									);
									$('#table-data').DataTable().ajax.reload();
								}
							}, 'json');
						}
					});
				}
			});
		});
	</script>

@endpush
