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
										<th>Name</th>
										<th>Email</th>
										<th>Roles</th>
										<th>Store</th>
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
								<div class="form-group">
									<label for="name">Name*</label>
									<input type="text" autofocus name="name" id="name" required class="form-control" maxlength="50" placeholder="Name" value="{{ old('name') }}">
									<p class="text-danger error-text name_error"></p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="email">Email*</label>
									<input type="email" name="email" id="email" required class="form-control" maxlength="50" placeholder="Email" value="{{ old('email') }}">
									<p class="text-danger error-text email_error"></p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="password">Password*</label>
									<input type="password" name="password" id="password" class="form-control" maxlength="50" placeholder="Password" value="{{ old('password') }}">
									<p class="text-danger error-text password_error"></p>
									<div id="checkbox-show-password">
										<input type="checkbox" id="show-password" onclick="showPassword();"> <label for="show-password">Show Password</label>
									</div>
									<div style="display: none;" id="checkbox-password">
										<input type="checkbox" value="ACTIVE" id="change-password"> <label for="change-password">Change Password</label>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="roles">Roles*</label>
									<select name="roles" class="form-control" id="roles" required>
										<option value="0" selected disabled>Select Roles</option>
										<option value="CASHIER" id="cashier">CASHIER</option>
										<option value="MANAGER" id="manager">MANAGER</option>
									</select>
									<p class="text-danger error-text roles_error"></p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="store-id">Store*</label>
									<select class="form-control select2" id="store-id" name="store_id" style="width: 100%;">
										<option value="" selected disabled>Select Store</option>
										@foreach($stores as $item)
										<option {{ old('store_id') == $item->id ? "selected" : "" }} value="{{ $item->id }}">{{ $item->name }}</option>
										@endforeach
									</select>
									<p class="text-danger error-text store_id_error"></p>
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
						"targets" : [0, 3, 7],
						"orderable" : false,
						"searchable" : false,
					} ],
					ajax : {
						url : "{{ route('user.index') }}",
						type : 'GET',
					},
					columns: [
						{ data: 'checkbox', name: 'checkbox', className: "text-center"},
						{ data: 'DT_RowIndex', name: 'DT_RowIndex', className: "text-center" },
						{ data: 'name', name: 'name', className: "text-center" },
						{ data: 'email', name: 'email', className: "text-center" },
						{ data: 'roles', name: 'roles', className: "text-center" },
						{ data: 'store', name: 'store', className: "text-center" },
						{ data: 'status', name: 'status', className: "text-center" },
						{ data: 'action', name: 'action', className: "text-center" },
					],
				}).on('draw', function () {
					$('input[name="user_checkbox"]').each(function (){
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

			$.fn.modal.Constructor.prototype.enforceFocus = function() {};
			const myModal = new bootstrap.Modal(document.getElementById('modal-post'));
			$('#btn-create').click(function () {
				myModal.show();
				$(document).ready(function() {
					$("#store-id").select2({
						dropdownParent: $("#modal-post")
					});
				});
				$('.modal-title').text("Create Data (* Required)");
				$('#form-post').trigger("reset");
				$('#id').val('');
				$('#password').prop('readonly', false);
				$(".modal-body").find("p").hide();
				$('#checkbox-password').attr("style", "display: none;");
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
										url: "{{ route('user.store') }}",
										data: formData,
										type: 'POST',
										dataType: 'json',
										cache:false,
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
				$('#checkbox-password').attr("style", "display: auto;");
				$('#password').prop('readonly', true);
				$(".modal-body").find("p").hide();
				$.get('user/' + dataId + '/edit', function (data) {
					$('#modal-post').modal('show');
					$(document).ready(function() {
						$("#store-id").select2({
							dropdownParent: $("#modal-post")
						});
					});
					$('.modal-title').text("Edit Data (* Required)");
					// set value masing-masing id berdasarkan data yg diperoleh dari ajax get request diatas               
					$('#id').val(data.id);
					$('#name').val(data.name);
					$('#email').val(data.email);
					$('#password').val(data.password);
					$('#store-id').val(data.store_id);
					// roles
					if (data.roles == "MANAGER") {
						$('#manager').prop('selected', true);
					} else if (data.roles == "CASHIER") {
						$('#cashier').prop('selected', true);
					}
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
								url: "user/" + dataId,
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
					$('input[name="user_checkbox"]').each(function () {
						this.checked = true;
					});
				} else {
					$('input[name="user_checkbox"]').each(function () {
						this.checked = false;
					});	
				}
				toggleDeleteAllBtn();
			});

			$(document).on('change', 'input[name="user_checkbox"]', function() {
				if ($('input[name="user_checkbox"]').length == $('input[name="user_checkbox"]:checked').length) {
					$('input[name="main_checkbox"]').prop('checked', true);
				} else {
					$('input[name="main_checkbox"]').prop('checked', false);
				}
				toggleDeleteAllBtn();
			});

			function toggleDeleteAllBtn() {
				if ($('input[name="user_checkbox"]:checked').length > 0) {
					$('#delete-all-btn').text('Delete ('+ $('input[name="user_checkbox"]:checked').length +')').removeClass('d-none');
				} else {
					$('#delete-all-btn').addClass('d-none');
				}
			}
			// method delete end

			$('#delete-all-btn').click(function () {
				let checkedUser = [];
				$('input[name="user_checkbox"]:checked').each(function () {
					checkedUser.push($(this).data('id'));
				});
				
				const url = "{{ route('delete-selected-user') }}";
				if (checkedUser.length > 0) {
					Swal.fire({
						title: 'Are you sure?',
						html: `You want to delete <b>(${checkedUser.length})</b> user`,
						icon: 'info',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Yes, Delete!',
						allowOutsideClick: false,
					}).then((result) => {
						if (result.value) {
							$.post(url, {id:checkedUser}, function (data) {
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

			$('#change-password').click(function() {
				if( $(this).is(':checked')) {
					$('#password').prop({readonly : false, required : true, minlength: "6"});
					$('#checkbox-show-password').show();
				} else {
					$('#password').val('');
					$('#password').prop({readonly : true, required : false});
					$('#password').removeAttr("minlength", "6");
					$('#checkbox-show-password').hide();
				}
			});
		});

		function showPassword() {
			let x = document.getElementById("password");
			if (x.type === "password") {
				x.type = "text";
			} else {
				x.type = "password";
			}
		}
	</script>

@endpush
