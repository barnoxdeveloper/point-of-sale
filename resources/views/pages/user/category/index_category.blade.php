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
					@if (Auth::user()->is_roles_manager)
					<div class="card-header">
						<div class="row">
							<div class="col-6">
								<a href="javascript:void(0)" class="btn btn-sm btn-success" id="btn-create">+ Create Data</a>
							</div>
						</div>
					</div>
					@endif
					<div class="card-body">
						<div class="table-responsive">
							<table id="table-data" class="table table-bordered table-striped w-100">
								<thead>
									<tr class="text-center">
										<th>#</th>
										<th>Name</th>
										<th>Photo</th>
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
					<form action="" enctype="multipart/form-data" method="POST" id="form-post">
						@csrf
						<div class="table-responsive">
							<table class="table table-bordered table-striped w-100" id="dynamic-table">
								<thead>
									<tr class="text-center">
										<th>Name</th>
										<th>Photo</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<tr class="text-center">
										<td>
											<input type="text" name="name[]" required class="form-control" placeholder="Name">
										</td>
										<td>
											<input type="file" accept="image/*" name="photo[]" required class="form-control">
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
									<label for="name-edit">Store</label>
									<input type="text" name="name" id="name-edit" required class="form-control" placeholder="Name">
									<p class="text-danger error-text name_error"></p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="photo">Photo(1mb) <span id="photo-preview"></span></label>
									<input type="file" accept="image/*" name="photo" id="photo" class="form-control">
									<p class="text-danger error-text photo_error"></p>
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
					order: [[3, 'asc']],
					lengthMenu: [
						[10, 25, 50, -1],
						[10, 25, 50, 'All'],
					],
					columnDefs : [ {
						"targets" : [1, 2, 4],
						"orderable" : false,
						"searchable" : false,
					} ],
					ajax : {
						url : "{{ route('category-user.index') }}",
						type : 'GET',
					},
					columns: [
						{ data: 'DT_RowIndex', name: 'DT_RowIndex', className: "text-center" },
						{ data: 'name', name: 'name', className: "text-center" },
						{ data: 'photo', name: 'photo', className: "text-center" },
						{ data: 'status', name: 'status', className: "text-center" },
						{ data: 'action', name: 'action', className: "text-center" },
					],
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
				$("#form-post").attr( "enctype", "multipart/form-data" );
				$(document).ready(function() {
					$(".store_id").select2({
						dropdownParent: $("#modal-post")
					});
				});
				$('.modal-title').text("Create Data (* Required)");
				$('#form-post').trigger("reset");
				$(".modal-body").find("p").hide();
				$('#add').click(function() {
					$(document).ready(function() {
						$(".store_id").select2({
							dropdownParent: $("#modal-post")
						});
					});
					$("#dynamic-table").append(`<tr class="text-center"><td><input type="text" name="name[]" required class="form-control" placeholder="Name"></td><td><input type="file" accept="image/*" name="photo[]" required class="form-control"></td><td><button type="button" class="btn btn-danger remove-tr">-</button></td></tr>`);
				});
				$(document).on('click', '.remove-tr', function(){  
					$(this).parents('tr').remove();
				});
			});
			if ($("#form-post").length > 0) {
				$("#form-post").validate({
					submitHandler: function (form) {
						let formData = new FormData($("#form-post")[0]); 
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
										url: "{{ route('category-user.store') }}",
										type: 'POST',
										dataType: 'json',
										data: formData,
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
				$.get('category-user/' + dataId + '/edit', function (data) {
					$('#modal-edit').modal('show');
					$(document).ready(function() {
						$("#store_id-edit").select2({
							dropdownParent: $("#modal-edit")
						});
					});
					$('.modal-title').text("Edit Data (* Required)");
					// set value masing-masing id berdasarkan data yg diperoleh dari ajax get request diatas
					$('#id').val(data.id);
					$('#name-edit').val(data.name);
					$('#photo-preview').html(`<a href="${data.photo}" title="${data.photo}" target="_blank"><img src="${data.photo}" alt="${data.photo}" style="width: 100px; height: 100px;"></a>`);
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
									let url = "{{ route('category-user.update', ":id") }}";
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


			// method delete start
			// $(document).on('click', '.delete', function () {
			// 	dataId = $(this).data('id');
			// 	Swal.fire({
			// 			title: 'Are you sure?',
			// 			text: "You won't be able to revert this!",
			// 			icon: 'warning',
			// 			showCancelButton: true,
			// 			confirmButtonColor: '#3085d6',
			// 			cancelButtonColor: '#d33',
			// 			confirmButtonText: 'Yes, delete it!'
			// 		}).then((result) => {
			// 			if (result.isConfirmed) {
			// 				$.ajax({
			// 					url: "category-user/" + dataId,
			// 					type: 'DELETE',
			// 				success: function (data) {
			// 					$('#delete-modal').modal('hide');
			// 					Swal.fire(
			// 						'Deleted!',
			// 						'Your Data has been Deleted.',
			// 						'success'
			// 					);
			// 					$('#table-data').DataTable().ajax.reload();
			// 				},
			// 				error: function (data) {
			// 					console.log('Error: ', data);
			// 				}
			// 			});
			// 		}
			// 	});
			// });

		});
	</script>

@endpush
