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
							<div class="col-6">
								<a href="javascript:void(0)" class="btn btn-sm btn-success" id="btn-create">
									+ Create Data
								</a>
								<button class="btn btn-sm btn-danger d-none deleteAllBtn" id="delete-all-btn">Delete All</button>
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
										<th>Code</th>
										<th>Name</th>
										<th>Location</th>
										<th>Discount</th>
										<th>Status</th>
										<th>Actions</th>
									</tr>
								</thead>
                                <tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>

	{{-- modal post --}}
	<div class="modal fade" id="modal-post" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="static-backdrop-label" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="static-backdrop-label"></h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form action="" enctype="multipart/form-data" id="form-post">
						@csrf
						<div class="row">
							<div class="col-md-6">
								<input type="hidden" readonly name="id" id="id">
								<input type="hidden" readonly name="type" id="type">
								<div class="form-group">
									<label for="name">Name*</label>
									<input type="text" autofocus name="name" id="name" required class="form-control" maxlength="255" placeholder="Name" value="{{ old('name') }}">
									<p class="text-danger error-text name_error"></p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="store-code">Store Code*</label>
									<input type="text" name="store_code" id="store-code" required class="form-control" maxlength="50" placeholder="Store Code" value="{{ old('store_code') }}">
									<p class="text-danger error-text store_code_error"></p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="location">Location*</label>
									<input type="text" name="location" id="location" required class="form-control" maxlength="255" placeholder="Location" value="{{ old('location') }}">
									<p class="text-danger error-text location_error"></p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="discount">Discount</label>
									<input type="text" name="discount" id="discount" class="form-control" maxlength="11" placeholder="Discount" value="{{ old('discount') }}">
									<p class="text-danger error-text discount_error"></p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="description">Description</label>
									<input type="text" name="description" id="description" class="form-control" maxlength="255" placeholder="Description" value="{{ old('description') }}">
									<p class="text-danger error-text description_error"></p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Status*</label> <br>
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" id="active" name="status" class="custom-control-input status" value="ACTIVE">
										<label class="custom-control-label" for="active">ACTIVE</label>
									</div>
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" id="non-active" name="status" class="custom-control-input status" value="NON-ACTIVE">
										<label class="custom-control-label" for="non-active">NON-ACTIVE</label>
									</div>
									<p class="text-danger error-text status_error"></p>
								</div>
							</div>
						</div>

						<div class="form-group text-center">
							<button type="submit" class="btn btn-primary" id="btn-save" value="create">
								<i class="far fa-paper-plane"></i>
								Save
							</button>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">X</button>
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
						"targets" : [0, 4, 5, 7],
						"orderable" : false,
						"searchable" : false,
					} ],
					ajax : {
						url : "{{ route('store.index') }}",
						type : 'GET',
					},
					columns: [
						{ data: 'checkbox', name: 'checkbox', className: "text-center"},
						{ data: 'DT_RowIndex', name: 'DT_RowIndex', className: "text-center" },
						{ data: 'storeCode', name: 'storeCode', className: "text-center" },
						{ data: 'name', name: 'name', className: "text-center" },
						{ data: 'location', name: 'location', className: "text-center" },
						{ data: 'discount', name: 'discount', className: "text-center" },
						{ data: 'status', name: 'status', className: "text-center" },
						{ data: 'action', name: 'action', className: "text-center" },
					],
				}).on('draw', function () {
					$('input[name="store_checkbox"]').each(function (){
						this.checked = false;
					});
					$('input[name="main_checkbox"]').prop('checked', false);
					$('#delete-all-btn').addClass('d-none');
				});
			});
		</script>
@endpush

@push('style-select2')

		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	
@endpush

@push('script-select2')

		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

			$.fn.modal.Constructor.prototype.enforceFocus = function() {};
			const myModal = new bootstrap.Modal(document.getElementById('modal-post'));
			$('#btn-create').click(function () {
				myModal.show();
				$('.modal-title').text("Create Data (* Required)");
				$('#form-post').trigger("reset");
				$('#id').val('');
				$('#type').val('create');
				$(".modal-body").find("p").hide();
			});

			if ($("#form-post").length > 0) {
				$("#form-post").validate({
					submitHandler: function (form) {
						let formData = new FormData(document.getElementById('form-post')); 
						Swal.fire({
							title: 'Are you sure?',
							icon: 'info',
							showCancelButton: true,
							confirmButtonColor: '#3085d6',
							cancelButtonColor: '#d33',
							confirmButtonText: 'Yes, Save it!'
							}).then((result) => {
								if (result.isConfirmed) {
									$(".modal-body").find("p").show();
									$.ajax({
										url: "{{ route('store.store') }}",
										data: formData,
										type: 'POST',
										dataType: 'json',
										cache: false,
										contentType: false,
										processData: false,
									beforeSend:function(){
										$(document).find('p.error-text').text('');
									},
									success: function (data) {
										if (data.code == 0) {
											$.each(data.messages, function(prefix, val) {
												$('p.'+prefix+'_error').text(val[0]);
											});
										} else {
											$('#form-post').trigger("reset");
											$('#modal-post').modal('hide');
											$('#table-data').DataTable().ajax.reload();
											Swal.fire(
												'Saved!',
												'Your Data has been Saved.',
												'success'
											);
										}
									},
									error: function (data) {
										$.each(data.messages, function(prefix, val) {
											$('p.'+prefix+'_error').text(val[0]);
										});
									}
								});
							}
						});
					}
				});
			}

			// method edit data
			$(document).on('click', '.editPost', function () {
				let dataId = $(this).data('id');
				$(".modal-body").find("p").hide();
				$.get('store/' + dataId + '/edit', function (data) {
					$('#modal-post').modal('show');
					$('.modal-title').text("Edit Data (* Required)");
					// set value masing-masing id berdasarkan data yg diperoleh dari ajax get request diatas               
					$('#id').val(data.id);
					$('#type').val('edit');
					$('#name').val(data.name);
					$('#store-code').val(data.store_code);
					$('#location').val(data.location);
					$('#discount').val(data.discount);
					$('#description').val(data.description);
					// status
					if (data.status == "ACTIVE") {
						$('#active').prop('checked', true);
					} else {
						$('#non-active').prop('checked', true);
					}
				})
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
								url: "store/" + dataId,
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
					$('input[name="store_checkbox"]').each(function () {
						this.checked = true;
					});
				} else {
					$('input[name="store_checkbox"]').each(function () {
						this.checked = false;
					});	
				}
				toggleDeleteAllBtn();
			});

			$(document).on('change', 'input[name="store_checkbox"]', function() {
				if ($('input[name="store_checkbox"]').length == $('input[name="store_checkbox"]:checked').length) {
					$('input[name="main_checkbox"]').prop('checked', true);
				} else {
					$('input[name="main_checkbox"]').prop('checked', false);
				}
				toggleDeleteAllBtn();
			});

			function toggleDeleteAllBtn() {
				if ($('input[name="store_checkbox"]:checked').length > 0) {
					$('#delete-all-btn').text('Delete ('+ $('input[name="store_checkbox"]:checked').length +')').removeClass('d-none');
				} else {
					$('#delete-all-btn').addClass('d-none');
				}
			}
			// method delete end

			$('#delete-all-btn').click(function () {
				let checkedStore = [];
				$('input[name="store_checkbox"]:checked').each(function () {
					checkedStore.push($(this).data('id'));
				});
				
				const url = "{{ route('delete-selected-store') }}";
				if (checkedStore.length > 0) {
					Swal.fire({
						title: 'Are you sure?',
						html: `You want to delete <b>(${checkedStore.length})</b> store`,
						icon: 'info',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Yes, Delete!',
						allowOutsideClick: false,
					}).then((result) => {
						if (result.value) {
							$.post(url, {id:checkedStore}, function (data) {
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
