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
								<a href="javascript:void(0)" class="btn btn-sm btn-success" id="btn-create">+ Create Data</a>
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
										<th>Catrgory</th>
										<th>Price</th>
										<th>Stock</th>
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
					<form action="" enctype="multipart/form-data" id="form-post">
						@csrf
						<div class="row">
							<div class="col-md-6">
								<input type="hidden" readonly name="id" id="id">
								<input type="hidden" readonly name="metode" id="metode">
								<div class="form-group">
									<label for="product-code">Product Code</label>
									<input type="text" autofocus name="product_code" id="product-code" class="form-control" maxlength="255" placeholder="Product Code" value="{{ old('product_code') }}">
									<p class="text-danger error-text product_code_error"></p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="name">Name*</label>
									<input type="text" name="name" id="name" required class="form-control" maxlength="255" placeholder="Name" value="{{ old('name') }}">
									<p class="text-danger error-text name_error"></p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="category-id">Category*</label>
									<select class="form-control select2" name="category_id" id="category-id" required style="width: 100%;">
										<option value="" selected disabled>Select Category</option>
										@foreach($categories as $item)
										<option {{ old('category_id') == $item->id ? "selected" : "" }} value="{{ $item->id }}">{{ $item->name }}</option>
										@endforeach
									</select>
									<p class="text-danger error-text category_id_error"></p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="old-price">Old Price*</label>
									<input type="number" name="old_price" id="old-price" required class="form-control" placeholder="Old Price" value="{{ old('old_price') }}">
									<p class="text-danger error-text old_price_error"></p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="new-price">New Price*</label>
									<input type="number" name="new_price" id="new-price" required class="form-control" placeholder="New Price" value="{{ old('new_price') }}">
									<p class="text-danger error-text new_price_error"></p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="limit-stock">Limit Stock*</label>
									<input type="number" name="limit_stock" id="limit-stock" required class="form-control" placeholder="Limit Stock" value="{{ old('limit_stock') }}">
									<p class="text-danger error-text limit_stock_error"></p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="stock">Stock*</label>
									<input type="number" name="stock" id="stock" required class="form-control" placeholder="Stock" value="{{ old('stock') }}">
									<p class="text-danger error-text stock_error"></p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="type">Type*</label>
									<select class="form-control" name="type" id="type" required style="width: 100%;">
										<option value="" selected disabled>Select Type</option>
										<option value="PCS" id="pcs">PCS</option>
										<option value="PACK" id="pack">PACK</option>
										<option value="KILOGRAM" id="kilogram">KILOGRAM</option>
										<option value="LITER" id="liter">LITER</option>
										<option value="ROLL" id="roll">ROLL</option>
										<option value="METER" id="meter">METER</option>
									</select>
									<p class="text-danger error-text type_error"></p>
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
									<label for="photo">Photo(1mb) : <span id="photo-preview"></span></label>
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
					order: [[3, 'asc']],
					lengthMenu: [
						[10, 25, 50, -1],
						[10, 25, 50, 'All'],
					],
					columnDefs : [ {
						"targets" : [0, 4, 6],
						"orderable" : false,
						"searchable" : false,
					} ],
					ajax : {
						url : "{{ route('product.index') }}",
						type : 'GET',
					},
					columns: [
						{ data: 'checkbox', name: 'checkbox', className: "text-center"},
						{ data: 'DT_RowIndex', name: 'DT_RowIndex', className: "text-center" },
						{ data: 'name', name: 'name', className: "text-center" },
						{ data: 'category', name: 'category', className: "text-center" },
						{ data: 'price', name: 'price', className: "text-center" },
						{ data: 'stock', name: 'stock', className: "text-center" },
						{ data: 'photo', name: 'photo', className: "text-center" },
						{ data: 'status', name: 'status', className: "text-center" },
						{ data: 'action', name: 'action', className: "text-center" },
					],
				}).on('draw', function () {
					$('input[name="product_checkbox"]').each(function (){
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
			const myModal = new bootstrap.Modal($('#modal-post'));
			$('#btn-create').click(function () {
				myModal.show();
				$(document).ready(function() {
					$("#category-id").select2({
						dropdownParent: $("#modal-post")
					});
				});
				$('.modal-title').text("Create Data (* Required)");
				$('#form-post').trigger("reset");
				$('#id').val('');
				$('#metode').val('create');
				$(".modal-body").find("p").hide();
				$('#name').focus();
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
										url: "{{ route('product.store') }}",
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
												data.notif,
												data.messages,
												data.icon,
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
				$('#metode').val('edit');
				$('.modal-title').text("Edit Data (* Required)");
				$.get('product/' + dataId + '/edit', function (data) {
					$('#modal-post').modal('show');
					$(document).ready(function() {
						$("#category-id").select2({
							dropdownParent: $("#modal-post")
						});
					});
					// set value masing-masing id berdasarkan data yg diperoleh dari ajax get request diatas               
					$('#id').val(data.id);
					$('#product-code').val(data.product_code);
					$('#name').val(data.name);
					$('#category-id').val(data.category_id);
					$('#old-price').val(data.old_price);
					$('#new-price').val(data.new_price);
					$('#limit-stock').val(data.limit_stock);
					$('#stock').val(data.stock);
					$('#type').val(data.type);
					$('#description').val(data.description);
					if (data.photo == window.location.protocol+"//"+window.location.hostname+":"+window.location.port+"/storage") {
						$('#photo-preview').html('Photo Not Found');
					} else {
						$('#photo-preview').html(`<a href="${data.photo}" title="${data.photo}" target="_blank"><img src="${data.photo}" alt="${data.photo}" style="width: 100px; height: 100px;"></a>`);
					}

					console.info("ini photo :" + data.photo);
					console.info("ini adalah : "+window.location.protocol+"//"+window.location.hostname+":"+window.location.port+"/storage");
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
								url: "product/" + dataId,
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
					$('input[name="product_checkbox"]').each(function () {
						this.checked = true;
					});
				} else {
					$('input[name="product_checkbox"]').each(function () {
						this.checked = false;
					});	
				}
				toggleDeleteAllBtn();
			});

			$(document).on('change', 'input[name="product_checkbox"]', function() {
				if ($('input[name="product_checkbox"]').length == $('input[name="product_checkbox"]:checked').length) {
					$('input[name="main_checkbox"]').prop('checked', true);
				} else {
					$('input[name="main_checkbox"]').prop('checked', false);
				}
				toggleDeleteAllBtn();
			});

			function toggleDeleteAllBtn() {
				if ($('input[name="product_checkbox"]:checked').length > 0) {
					$('#delete-all-btn').text('Delete ('+ $('input[name="product_checkbox"]:checked').length +')').removeClass('d-none');
				} else {
					$('#delete-all-btn').addClass('d-none');
				}
			}
			// method delete end

			$('#delete-all-btn').click(function () {
				let checkedProduct = [];
				$('input[name="product_checkbox"]:checked').each(function () {
					checkedProduct.push($(this).data('id'));
				});
				
				const url = "{{ route('delete-selected-product') }}";
				if (checkedProduct.length > 0) {
					Swal.fire({
						title: 'Are you sure?',
						html: `You want to delete <b>(${checkedProduct.length})</b> product`,
						icon: 'info',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Yes, Delete!',
						allowOutsideClick: false,
					}).then((result) => {
						if (result.value) {
							$.post(url, {id:checkedProduct}, function (data) {
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
