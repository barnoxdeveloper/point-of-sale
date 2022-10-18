@extends('layouts.admin_layout')
@section('title', $title)
@section('admin_content')

	<div class="content-wrapper">
		<section class="content-header">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6">
						<h1>{{ $title }}</h1>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
							<li class="breadcrumb-item active">{{ $title }}</li>
						</ol>
					</div>
				</div>
			</div>
		</section>
		<section class="content">
			<div class="container-fluid p-3">
				<div class="card" data-aos="fade-up">
					<div class="card-header">
						<div class="row">
							<div class="col-6">
								<a href="javascript:void(0)" class="btn btn-sm btn-success" id="btn-create">+ Create Data</a>
								<button class="btn btn-sm btn-danger d-none deleteAllBtn" id="delete-all-btn">Delete All</button>
							</div>
							<div class="col-6">
								<a href="javascript:void(0)" class="btn btn-sm btn-success" id="btn-modal-updated-all-attendance">
									<i class="bi bi-check-all"></i> Updated Status
								</a>
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
										<th>NIP</th>
										<th>Name</th>
										<th>Period</th>
										<th>Document</th>
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
						<div class="table-responsive">
							<table class="table table-bordered table-striped w-100" id="dynamicTable">
								<thead>
									<tr class="text-center">
										<th width="30%">NIP</th>
										<th width="10%">Period</th>
										<th>Document</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<tr class="text-center">
										<td>
											<select class="form-control select2 nip" required name="nip[]" style="width: 100%;">
												<option value="0" selected disabled>Empty</option>
												@foreach($users as $item)
												<option {{ old('nip') == $item->nip ? "selected" : "" }} value="{{ $item->nip }}">{{ $item->nip }} | {{ $item->name }}</option>
												@endforeach
											</select>
										</td>
										<td>
											<input type="month" name="period[]" required class="form-control" placeholder="Period">
										</td>
										<td>
											<input type="file" accept="application/pdf" name="document[]" required class="form-control">
										</td>
										<td>
											<button type="button" name="add" id="add" class="btn btn-success">+</button>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="form-group text-center">
							<button type="submit" class="btn btn-primary" id="btn-save">
								<i class="bi bi-send-check"></i>
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

	{{-- modal edit --}}
	<div class="modal fade" id="modal-edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-edit-label" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modal-edit-label"></h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form action="" enctype="multipart/form-data" id="form-edit">
						@csrf
						@method('PUT')
						<input type="hidden" readonly name="id" id="id">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="nip-edit">Name*</label>
									<select class="form-control select2" required id="nip-edit" name="nip" style="width: 100%;">
										<option value="0" selected disabled>Empty</option>
										@foreach($users as $item)
										<option {{ old('nip') == $item->nip ? "selected" : "" }} value="{{ $item->nip }}">{{ $item->nip }} | {{ $item->name }}</option>
										@endforeach
									</select>
									<p class="text-danger error-text nip_error"></p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="period">Period</label>
									<input type="month" name="period" id="period-edit" required class="form-control" placeholder="Period">
									<p class="text-danger error-text period_error"></p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="document">Document <span id="document-preview"></span></label>
									<input type="file" accept="application/pdf" name="document" id="document" class="form-control">
									<p class="text-danger error-text document_error"></p>
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
							<button type="submit" class="btn btn-primary" id="btn-save">
								<i class="bi bi-send-check"></i>
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

	{{-- modal Activated All Attendance --}}
	<div class="modal fade" id="modal-updated-all-attendance" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-updated-all-attendance-label" aria-hidden="true">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modal-updated-all-attendance-label"></h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form action="" id="form-updated-all-attendance">
						@csrf
						{{-- @method('PUT') --}}
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="period">Period*</label>
									<input type="month" name="period" id="period-updated-all-attendance" required class="form-control" placeholder="Period">
									<p class="text-danger error-text period_error"></p>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Status*</label> <br>
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" id="active-activated-all" name="status" class="custom-control-input status" value="ACTIVE">
										<label class="custom-control-label" for="active-activated-all">ACTIVE</label>
									</div>
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" id="non-active-activated-all" name="status" class="custom-control-input status" value="NON-ACTIVE">
										<label class="custom-control-label" for="non-active-activated-all">NON-ACTIVE</label>
									</div>
									<p class="text-danger error-text status_error"></p>
								</div>
							</div>
						</div>
						<div class="form-group text-center">
							<button type="submit" class="btn btn-primary" id="btn-save">
								<i class="bi bi-send-check"></i>
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
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.12.1/rr-1.2.8/datatables.min.css"/>
	<style>
		.table th, td{
			font-size: 12px;
		}
	</style>
@endpush

@push('data-table')
    
	<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.12.1/rr-1.2.8/datatables.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function (){
			$('#table-data').DataTable({
				processing : true,
				serverSide : true,
				pageLength : 25,
				columnDefs : [ {
					"targets" : [0, 5],
					"orderable" : false,
					"searchable" : false,
				} ],
				ajax : {
					url : "{{ route('attendance.index') }}",
					type : 'GET',
				},
				columns: [
					{ data: 'checkbox', name: 'checkbox', className: "text-center"},
					{ data: 'DT_RowIndex', name: 'DT_RowIndex', className: "text-center" },
					{ data: 'nip', name: 'nip', className: "text-center" },
					{ data: 'name', name: 'name', className: "text-center" },
					{ data: 'period', name: 'period', className: "text-center" },
					{ data: 'document', name: 'document', className: "text-center" },
					{ data: 'status', name: 'status', className: "text-center" },
					{ data: 'action', name: 'action', className: "text-center" },
				],
			}).on('draw', function () {
				$('input[name="attendance_checkbox"]').each(function (){
					this.checked = false;
				});
				$('input[name="main_checkbox"]').prop('checked', false);
				$('#delete-all-btn').addClass('d-none');
			});
		});
	</script>
@endpush

@push('style-form')
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	
@endpush

@push('script-form')
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@endpush

@push('modal-post')
	<script>
		$(document).ready(function () {
			$.ajaxSetup({
				headers : {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
				}
			});

			// method create start
			$.fn.modal.Constructor.prototype.enforceFocus = function() {};
			const myModal = new bootstrap.Modal(document.getElementById('modal-post'));
			$('#btn-create').click(function () {
				myModal.show();
				$(document).ready(function() {
					$(".nip").select2({
						dropdownParent: $("#modal-post")
					});
				});
				$('.modal-title').text("Create Data (* Required)");
				$('#form-post').trigger("reset");
				$('#id').val('');
				$(".modal-body").find("p").hide();

				$('#add').click(function() {
					$(document).ready(function() {
						$(".nip").select2({
							dropdownParent: $("#modal-post")
						});
					});
					$("#dynamicTable").append(`<tr class="text-center"><td><select class="form-control select2 nip" required name="nip[]" style="width: 100%;"><option value="0" selected disabled>Empty</option>@foreach($users as $item)<option {{ old('nip') == $item->nip ? "selected" : "" }} value="{{ $item->nip }}">{{ $item->nip }} | {{ $item->name }}</option>@endforeach</select></td><td><input type="month" name="period[]" required class="form-control" placeholder="Period"></td><td><input type="file" accept="application/pdf" name="document[]" required class="form-control"></td><td><button type="button" class="btn btn-danger remove-tr">-</button></td></tr>`);
				});
			
				$(document).on('click', '.remove-tr', function(){  
					$(this).parents('tr').remove();
				});
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
										url: "{{ route('attendance.store') }}",
										data: formData,
										type: 'POST',
										dataType: 'json',
										cache: false,
										contentType: false,
										processData: false,
									beforeSend: function(){
										$(document).find('p.error-text').text('');
									},
									success: function (data) {
										if (data.code == 0) {
											$.each(data.messages, function(prefix, val) {
												Swal.fire(
													`Error!`,
													`${val}`,
													'error'
												);
											});
										} else if (data.code == 200){
											$('#form-post').trigger("reset");
											$('#modal-post').modal('hide');
											$('#table-data').DataTable().ajax.reload();
											Swal.fire(
												`${data.notif}`,
												`${data.messages}`,
												'success'
											);
										}
									},
									error: function (data) {
										$.each(data.messages, function(prefix, val) {
											Swal.fire(
												`Error!`,
												`${val}`,
												'error'
											);
										});
									}
								});
							}
						});
					}
				});
			}
			// method create end

			// method edit start
			$(document).on('click', '.editPost', function () {
				let dataId = $(this).data('id');
				$(".modal-body").find("p").hide();
				$.get('attendance/' + dataId + '/edit', function (data) {
					$('#modal-edit').modal('show');
					$(document).ready(function() {
						$("#nip-edit").select2({
							dropdownParent: $("#modal-edit")
						});
					});
					$('.modal-title').text("Edit Data (* Required)");
					// set value masing-masing id berdasarkan data yg diperoleh dari ajax get request diatas
					$('#id').val(data.id);
					$('#nip-edit').val(data.nip);
					$('#period-edit').val(data.period);
					$('#document-preview').html(`<a href="${data.document}" title="${data.document}" target="_blank"> : Preview Document</a>`);
					// status
					if (data.status == "ACTIVE") {
						$('#active').prop('checked', true);
					} else {
						$('#non-active').prop('checked', true);
					}
				});
			});

			if ($("#form-edit").length > 0) {
				$("#form-edit").validate({
					submitHandler: function (form) {
						let formData = new FormData(document.getElementById('form-edit'));
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
									let id = $("input[name=id]").val();
									let url = "{{ route('attendance.update', ":id") }}";
									url = url.replace(':id', id);
									$.ajax({
										type: 'POST',
										enctype: 'multipart/form-data',
										url: url,
										data: formData,
										dataType: 'json',
										cache: false,
										contentType: false,
										processData: false,
									beforeSend: function(){
										$(document).find('p.error-text').text('');
									},
									success: function (data) {
										if (data.code == 0) {
											$.each(data.messages, function(prefix, val) {
												Swal.fire(
													`Error!`,
													`${val}`,
													'error'
												);
											});
										} else if (data.code == 200){
											$('#form-post').trigger("reset");
											$('#modal-edit').modal('hide');
											$('#table-data').DataTable().ajax.reload();
											Swal.fire(
												`${data.notif}`,
												`${data.messages}`,
												'success'
											);
										}
									},
									error: function (data) {
										$.each(data.messages, function(prefix, val) {
											Swal.fire(
												`Error!`,
												`${val}`,
												'error'
											);
										});
									}
								});
							}
						});
					}
				});
			}
			// method edit end

			// method update status start
			$('#btn-modal-updated-all-attendance').click(function () {
				$('#modal-updated-all-attendance').modal('show');
				$('.modal-title').text("Update Data (* Required)");
				$('#form-updated-all-attendance').trigger("reset");
				$(".modal-body").find("p").hide();
			});

			if ($("#form-updated-all-attendance").length > 0) {
				$("#form-updated-all-attendance").validate({
					submitHandler: function (form) {
						let formData = new FormData(document.getElementById('form-updated-all-attendance'));
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
										type: 'POST',
										url: "{{ route('activated-all-attendance') }}",
										data: formData,
										dataType: 'json',
										cache: false,
										contentType: false,
										processData: false,
									beforeSend: function(){
										$(document).find('p.error-text').text('');
									},
									success: function (data) {
										if (data.code == 0) {
											$.each(data.messages, function(prefix, val) {
												Swal.fire(
													`Error!`,
													`${val}`,
													'error'
												);
											});
										} else if (data.code == 200){
											$('#form-updated-all-attendance').trigger("reset");
											$('#modal-updated-all-attendance').modal('hide');
											$('#table-data').DataTable().ajax.reload();
											Swal.fire(
												`${data.notif}`,
												`${data.messages}`,
												'success'
											);
										}
									},
									error: function (data) {
										$.each(data.messages, function(prefix, val) {
											Swal.fire(
												`Error!`,
												`${val}`,
												'error'
											);
										});
									}
								});
							}
						});
					}
				});
			}
			// method update status end

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
								url: "attendance/" + dataId,
								type: 'DELETE',
							success: function (data) {
								$('#delete-modal').modal('hide');
								Swal.fire(
									'Deleted!',
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
					$('input[name="attendance_checkbox"]').each(function () {
						this.checked = true;
					});
				} else {
					$('input[name="attendance_checkbox"]').each(function () {
						this.checked = false;
					});	
				}
				toggleDeleteAllBtn();
			});

			$(document).on('change', 'input[name="attendance_checkbox"]', function() {
				if ($('input[name="attendance_checkbox"]').length == $('input[name="attendance_checkbox"]:checked').length) {
					$('input[name="main_checkbox"]').prop('checked', true);
				} else {
					$('input[name="main_checkbox"]').prop('checked', false);
				}
				toggleDeleteAllBtn();
			});

			function toggleDeleteAllBtn() {
				if ($('input[name="attendance_checkbox"]:checked').length > 0) {
					$('#delete-all-btn').text('Delete ('+ $('input[name="attendance_checkbox"]:checked').length +')').removeClass('d-none');
				} else {
					$('#delete-all-btn').addClass('d-none');
				}
			}

			$('#delete-all-btn').click(function () {
				let checkedAttendance = [];
				$('input[name="attendance_checkbox"]:checked').each(function () {
					checkedAttendance.push($(this).data('id'));
				});
				
				const url = "{{ route('delete-selected-attendance') }}";
				if (checkedAttendance.length > 0) {
					Swal.fire({
						title: 'Are you sure?',
						html: `You want to delete <b>(${checkedAttendance.length})</b> attendance`,
						icon: 'info',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Yes, Delete!',
						allowOutsideClick: false,
					}).then((result) => {
						if (result.value) {
							$.post(url, {id:checkedAttendance}, function (data) {
								if (data.code == 1) {
									Swal.fire(
										'Deleted!',
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
			// method delete end
		});
	</script>

@endpush
