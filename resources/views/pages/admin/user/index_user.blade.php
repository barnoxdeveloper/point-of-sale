@extends('layouts.admin_layout')
@section('title', $title)
@section('admin_content')

	<div class="content-wrapper">
		<section class="content-header">
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-6">
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
							<div class="col-md-4">
								<input type="hidden" readonly name="id" id="id">
								<input type="hidden" readonly name="type" id="type">
								<div class="form-group">
									<label for="name">Name*</label>
									<input type="text" autofocus name="name" id="name" required class="form-control" maxlength="50" placeholder="Name" value="{{ old('name') }}">
									<p class="text-danger error-text name_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="email">Email*</label>
									<input type="email" name="email" id="email" required class="form-control" maxlength="50" placeholder="Email" value="{{ old('email') }}">
									<p class="text-danger error-text email_error"></p>
								</div>
							</div>
							<div class="col-md-4">
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
							<div class="col-md-4">
								<div class="form-group">
									<label for="roles">Roles*</label>
									<select name="roles" class="form-control" id="roles" required>
										<option value="0" selected disabled>Select Roles</option>
										<option value="EMPLOYEE" id="employee">EMPLOYEE</option>
										<option value="LEADER" id="leader">LEADER</option>
										<option value="MANAGER" id="manager">MANAGER</option>
									</select>
									<p class="text-danger error-text roles_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Department</label>
									<select class="form-control select2" id="id-department" name="id_department" style="width: 100%;">
										<option value="" selected>Empty</option>
										@foreach($departments as $item)
										<option {{ old('id_department') == $item->id_department ? "selected" : "" }} value="{{ $item->id_department }}">{{ $item->name }}</option>
										@endforeach
									</select>
									<p class="text-danger error-text id_department_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Sub Department</label>
									<select class="form-control select2" id="id-sub-department" name="id_sub_department" style="width: 100%;">
										{{-- <option value="" selected>Empty</option>
										@foreach($subDepartments as $item)
										<option {{ old('id_sub_department') == $item->id ? "selected" : "" }} value="{{ $item->id }}">{{ $item->name }}</option>
										@endforeach --}}
									</select>
									<p class="text-danger error-text id_sub_department_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Leader</label>
									<select class="form-control select2" id="id-leader" name="id_leader" style="width: 100%;">
										<option value="" selected>Empty</option>
										@foreach($leaders as $item)
										<option {{ old('id_leader') == $item->id ? "selected" : "" }} value="{{ $item->id }}">{{ $item->name }}</option>
										@endforeach
									</select>
									<p class="text-danger error-text id_leader_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="nip">NIP*</label>
									<input type="number" name="nip" id="nip" required class="form-control" placeholder="NIP" value="{{ old('nip') }}">
									<p class="text-danger error-text nip_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="nik">NIK*</label>
									<input type="number" name="nik" id="nik" required class="form-control" placeholder="NIK" value="{{ old('nik') }}">
									<p class="text-danger error-text nik_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="kk">KK*</label>
									<input type="number" name="kk" id="kk" required class="form-control" placeholder="KK" value="{{ old('kk') }}">
									<p class="text-danger error-text kk_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="place-of-birth">Place of Birth*</label>
									<input type="text" name="place_of_birth" id="place-of-birth" required class="form-control" maxlength="30" placeholder="Place of Birth" value="{{ old('place_of_birth') }}">
									<p class="text-danger error-text place_of_birth_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="date-of-birth">Date of Birth*</label>
									<input type="date" name="date_of_birth" id="date-of-birth" required class="form-control" placeholder="Date of Birth" value="{{ old('date_of_birth') }}">
									<p class="text-danger error-text date_of_birth_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="contact">Contact*</label>
									<input type="text" name="contact" id="contact" required class="form-control" maxlength="30" placeholder="Contact" value="{{ old('contact') }}">
									<p class="text-danger error-text contact_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="address">Address*</label>
									<input type="text" name="address" id="address" required class="form-control" placeholder="Address" value="{{ old('address') }}">
									<p class="text-danger error-text address_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="npwp">NPWP*</label>
									<input type="text" name="npwp" id="npwp" required class="form-control" maxlength="30" placeholder="NPWP" value="{{ old('npwp') }}">
									<p class="text-danger error-text npwp_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Gender*</label> <br>
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" id="laki-laki" name="gender" class="custom-control-input gender" value="LAKI-LAKI">
										<label class="custom-control-label" for="laki-laki">LAKI-LAKI</label>
									</div>
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" id="perempuan" name="gender" class="custom-control-input gender" value="PEREMPUAN">
										<label class="custom-control-label" for="perempuan">PEREMPUAN</label>
									</div>
									<p class="text-danger error-text gender_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Status Kawin*</label> <br>
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" id="kawin" name="marital_status" class="custom-control-input gender" value="KAWIN">
										<label class="custom-control-label" for="kawin">KAWIN</label>
									</div>
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" id="tidak-kawin" name="marital_status" class="custom-control-input gender" value="TIDAK-KAWIN">
										<label class="custom-control-label" for="tidak-kawin">TIDAK-KAWIN</label>
									</div>
									<p class="text-danger error-text marital_status_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Status Employee*</label> <br>
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" id="contract" name="status_employee" class="custom-control-input status_employee" value="CONTRACT">
										<label class="custom-control-label" for="contract">CONTRACT</label>
									</div>
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" id="permanent" name="status_employee" class="custom-control-input status_employee" value="PERMANENT">
										<label class="custom-control-label" for="permanent">PERMANENT</label>
									</div>
									<p class="text-danger error-text status_employee_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="date-of-entry">Date of Entry*</label>
									<input type="date" name="date_of_entry" id="date-of-entry" required class="form-control" placeholder="Date of Entry" value="{{ old('date_of_entry') }}">
									<p class="text-danger error-text date_of_entry_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="date-of-ended">Date of Ended</label>
									<input type="date" name="date_of_ended" id="date-of-ended" class="form-control" placeholder="Date of Ended" value="{{ old('date_of_ended') }}">
									<p class="text-danger error-text date_of_ended_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="last_education">Last Education*</label>
									<select name="last_education" class="form-control" id="last_education" required>
										<option value="0" selected disabled>Select Last Education</option>
										<option value="SD" id="sd">SD</option>
										<option value="SMP-SEDERAJAT" id="smp-sederajat">SMP-SEDERAJAT</option>
										<option value="SMA-SEDERAJAT" id="sma-sederajat">SMA-SEDERAJAT</option>
										<option value="D1" id="d1">D1</option>
										<option value="D2" id="d2">D2</option>
										<option value="D3" id="d3">D3</option>
										<option value="D4" id="d4">D4</option>
										<option value="S1" id="s1">S1</option>
										<option value="S2" id="s2">S2</option>
										<option value="S3" id="s3">S3</option>
									</select>
									<p class="text-danger error-text last_education_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="school-origin">School Origin*</label>
									<input type="text" name="school_origin" id="school-origin" required class="form-control" maxlength="255" placeholder="School Origin" value="{{ old('school_origin') }}">
									<p class="text-danger error-text school_origin_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="gaji-pokok">Gaji Pokok*</label>
									<input type="number" name="gaji_pokok" id="gaji-pokok" required class="form-control" placeholder="Gaji Pokok" value="{{ old('gaji_pokok') }}" onkeyup="sumPotongan();">
									<p class="text-danger error-text gaji_pokok_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="tunjangan-jabatan">Tunjangan Jabatan*</label>
									<input type="number" name="tunjangan_jabatan" id="tunjangan-jabatan" required class="form-control" placeholder="Tunjangan Jabatan" value="{{ old('tunjangan_jabatan') }}" onkeyup="sumPotongan();">
									<p class="text-danger error-text tunjangan_jabatan_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="tunjangan-makan">Tunjangan Makan*</label>
									<input type="number" name="tunjangan_makan" id="tunjangan-makan" required class="form-control" placeholder="Tunjangan Makan" value="{{ old('tunjangan_makan') }}" onkeyup="sumPotongan();">
									<p class="text-danger error-text tunjangan_makan_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="tunjangan-transport">Tunjangan Transport*</label>
									<input type="number" name="tunjangan_transport" id="tunjangan-transport" required class="form-control" placeholder="Tunjangan Transport" value="{{ old('tunjangan_transport') }}" onkeyup="sumPotongan();">
									<p class="text-danger error-text tunjangan_transport_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="jht">JHT*</label>
									<input type="number" name="jht" id="jht" required readonly class="form-control" placeholder="JHT" value="{{ old('jht') }}">
									<p class="text-danger error-text jht_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="jp">JP*</label>
									<input type="number" name="jp" id="jp" required readonly class="form-control" placeholder="JP" value="{{ old('jp') }}">
									<p class="text-danger error-text jp_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="jkk">JKK*</label>
									<input type="number" name="jkk" id="jkk" required readonly class="form-control" placeholder="JKK" value="{{ old('jkk') }}">
									<p class="text-danger error-text jkk_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="jkm">JKM*</label>
									<input type="number" name="jkm" id="jkm" required readonly class="form-control" placeholder="JKM" value="{{ old('jkm') }}">
									<p class="text-danger error-text jkm_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="jumlah-tanggungan">Jumlah Tanggungan*</label>
									<input type="number" name="jumlah_tanggungan" id="jumlah-tanggungan" required class="form-control" placeholder="Jumlah Tanggungan" value="{{ old('jumlah_tanggungan') }}">
									<p class="text-danger error-text jumlah_tanggungan_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="ptkp">PTKP(Perempuan di isi manual)*</label>
									<input type="number" name="ptkp" id="ptkp" required class="form-control" placeholder="PTKP(Perempuan di isi manual)" value="{{ old('ptkp') }}">
									<p class="text-danger error-text ptkp_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="sisa-cuti">Sisa Cuti*</label>
									<input type="number" name="sisa_cuti" id="sisa-cuti" required class="form-control" placeholder="Sisa Cuti" value="{{ old('sisa_cuti') }}">
									<p class="text-danger error-text sisa_cuti_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="account-bank-1">Account bank 1*</label>
									<input type="text" name="account_bank_1" id="account-bank-1" required class="form-control" maxlength="15" placeholder="Account bank 1" value="{{ old('account_bank_1') }}">
									<p class="text-danger error-text account_bank_1_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="account-bank-2">Account Bank 2</label>
									<input type="text" name="account_bank_2" id="account-bank-2" class="form-control" maxlength="15" placeholder="Account Bank 2" value="{{ old('account_bank_2') }}">
									<p class="text-danger error-text account_bank_2_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="account-bank-number-1">Account Bank Number 1*</label>
									<input type="number" name="account_bank_number_1" id="account-bank-number-1" required class="form-control" placeholder="Account Bank Number 1" value="{{ old('account_bank_number_1') }}">
									<p class="text-danger error-text account_bank_number_1_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="account-bank-number-2">Account Bank Number 2</label>
									<input type="number" name="account_bank_number_2" id="account-bank-number-2" class="form-control" placeholder="Account Bank Number 2" value="{{ old('account_bank_number_2') }}">
									<p class="text-danger error-text account_bank_number_2_error"></p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="cost-payroll">Cost Payroll</label>
									<input type="number" name="cost_payroll" id="cost-payroll" class="form-control" placeholder="Cost Payroll" value="{{ old('cost_payroll') }}">
									<p class="text-danger error-text cost_payroll_error"></p>
								</div>
							</div>
							<div class="col-md-4">
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
							<div class="col-md-4">
								<div class="form-group">
									<label for="note">Note</label>
									<input type="text" name="note" id="note" class="form-control" placeholder="Note" value="{{ old('note') }}">
									<p class="text-danger error-text note_error"></p>
								</div>
							</div>
						</div>

						<div class="form-group text-center">
							<button type="submit" class="btn btn-primary" id="btn-save" value="create">
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
				pageLength : 50,
				lengthMenu: [
					[10, 25, 50, -1],
					[10, 25, 50, 'All'],
				],
				columnDefs: [ {
					"targets" : [0, 3, 6],
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

@push('style-form')
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	
@endpush

@push('script-form')
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush

@push('modal-post')
	<script>
		// method create
		$(document).ready(function () {
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
					$("#id-department").select2({
						dropdownParent: $("#modal-post")
					});
					$("#id-sub-department").select2({
						dropdownParent: $("#modal-post")
					});
					$("#id-leader").select2({
						dropdownParent: $("#modal-post")
					});
				});
				$('.modal-title').text("Create Data (* Required)");
				$('#form-post').trigger("reset");
				$('#id').val('');
				$('#password').attr('disabled', false);
				$('#type').val('create');
				$(".modal-body").find("p").hide();
				$('#ptkp').prop("readonly", true );
			});

			$('#roles').on('change', function () {
				let roles = this.value;
				if (roles == "EMPLOYEE") {
					$("#id-sub-department").attr({disabled : false});
					$('#id-department').on('change', function () {
						let idDepartment = this.value;
						if (idDepartment) {
							$.ajax({
								url: "{{ url('fetch-sub-deparment') }}",
								type: 'POST',
								data: {
									id_department: idDepartment,
									_token: '{{ csrf_token() }}'
								},
								dataType: 'json',
								success: function (result) {
									if (result) {
										$('#id-sub-department').html('<option value="" selected >Empty</option>');
										$.each(result.subDepartment, function (key, value) {
											$("#id-sub-department").append('<option value="' + value.id_department + '">' + value.name + '</option>');
										});
									} else {
										$("#id-sub-department").html('<option value="" selected disabled>Empty</option>');
									}
								}
							});
						} else if (idDepartment == "") {
							$("#id-sub-department").html('<option value="" selected disabled>Empty</option>');
						}
					});	
				} else {
					$("#id-sub-department").html('<option value="" selected disabled>Empty</option>');
					$("#id-sub-department").attr({disabled : true});
				}
			});

			// function ptkp perempuan isi manual
			$('.gender').change(function () {
				if (this.value == "LAKI-LAKI") {
					$('#ptkp').attr({readonly: true, required: false} );
					$('#ptkp').val("");
				} else if (this.value == "PEREMPUAN") {
					$('#ptkp').attr({readonly: false, required: true} );
				}
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
										if (data.status == 0) {
											$.each(data.errors, function(prefix, val) {
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
										$.each(data.errors, function(prefix, val) {
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
				$('#password').attr("disabled", "disabled");
				$(".modal-body").find("p").hide();
				$.get('user/' + dataId + '/edit', function (data) {
					$('#modal-post').modal('show');
					$(document).ready(function() {
						$("#id-department").select2({
							dropdownParent: $("#modal-post")
						});
						$("#id-sub-department").select2({
							dropdownParent: $("#modal-post")
						});
						$("#id-leader").select2({
							dropdownParent: $("#modal-post")
						});
					});
					$('.modal-title').text("Edit Data (* Required)");
					// set value masing-masing id berdasarkan data yg diperoleh dari ajax get request diatas               
					$('#type').val('edit');
					$('#id').val(data.id);
					$('#name').val(data.name);
					$('#email').val(data.email);
					$('#password').val(data.password);
					$('#id-department').val(data.id_department);
					$('#id-sub-department').val(data.id_sub_department);
					$('#id-leader').val(data.id_leader);
					$('#nip').val(data.nip);
					$('#nik').val(data.nik);
					$('#kk').val(data.kk);
					$('#npwp').val(data.npwp);
					$('#date-of-entry').val(data.date_of_entry);
					$('#date-of-ended').val(data.date_of_ended);
					$('#address').val(data.address);
					$('#place-of-birth').val(data.place_of_birth);
					$('#date-of-birth').val(data.date_of_birth);
					$('#school-origin').val(data.school_origin);
					$('#contact').val(data.contact);
					$('#jumlah-tanggungan').val(data.jumlah_tanggungan);
					$('#ptkp').val(data.ptkp);
					$('#sisa-cuti').val(data.sisa_cuti);
					$('#gaji-pokok').val(data.gaji_pokok);
					$('#tunjangan-jabatan').val(data.tunjangan_jabatan);
					$('#tunjangan-makan').val(data.tunjangan_makan);
					$('#tunjangan-transport').val(data.tunjangan_transport);
					$('#jht').val(data.jht);
					$('#jp').val(data.jp);
					$('#jkk').val(data.jkkt);
					$('#jkm').val(data.jkm);
					$('#account-bank-1').val(data.account_bank_1);
					$('#account-bank-2').val(data.account_bank_2);
					$('#account-bank-number-1').val(data.account_bank_number_1);
					$('#account-bank-number-2').val(data.account_bank_number_2);
					$('#cost-payroll').val(data.cost_payroll);

					// status employee
					if (data.status_employee == "PERMANENT") {
						$('#permanent').prop('checked', true);
					} else {
						$('#contract').prop('checked', true);
					}
					// gender
					if (data.gender == "LAKI-LAKI") {
						$('#laki_laki').prop('checked', true);
						$('#ptkp').attr({readonly: true, required: false} );
						$('#ptkp').val(data.gender);
					} else {
						$('#perempuan').prop('checked', true);
						$('#ptkp').prop({readonly: false, required: true} );
					}
					// last education
					if (data.last_education == "SD") {
						$('#sd').prop('selected', true);
					} else if (data.last_education == "SMP-SEDERAJAT") {
						$('#smp-sederajat').prop('selected', true);
					} else if (data.last_education == "SMA-SEDERAJAT") {
						$('#sma-sederajat').prop('selected', true);
					} else if (data.last_education == "D1") {
						$('#d1').prop('selected', true);
					} else if (data.last_education == "D2") {
						$('#d2').prop('selected', true);
					} else if (data.last_education == "D4") {
						$('#d4').prop('selected', true);
					} else if (data.last_education == "S1") {
						$('#s1').prop('selected', true);
					} else if (data.last_education == "S2") {
						$('#s2').prop('selected', true);
					} else if (data.last_education == "S3") {
						$('#s3').prop('selected', true);
					}
					// marital status
					if (data.gender == "KAWIN") {
						$('#kawin').prop('checked', true);
					} else {
						$('#tidak-kawin').prop('checked', true);
					}
					// roles
					if (data.roles == "MANAGER") {
						$('#manager').prop('selected', true);
					} else if (data.roles == "LEADER") {
						$('#leader').prop('selected', true);
					} else if (data.roles == "EMPLOYEE") {
						$('#employee').prop('selected', true);
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
					$('#password').attr({disabled : false, required : true, minlength: "6"});
					$('#checkbox-show-password').show();
				} else {
					$('#password').val('');
					$('#password').attr({disabled : true, required : false});
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

		function sumPotongan() {
			const gapok = $('#gaji-pokok').val();
			const tunjangan_jabatan = $('#tunjangan-jabatan').val();
			const tunjangan_makan = $('#tunjangan-makan').val();
			const tunjangan_transport = $('#tunjangan-transport').val();
			let total = (parseInt(gapok) + parseInt(tunjangan_jabatan) + parseInt(tunjangan_makan) + parseInt(tunjangan_transport));
			let jht = total * 0.02 ; 
			let jp = 0;
			if (total >= 9077654) {
				jp = 9077654 * 0.01;
			} else {
				jp = total * 0.01;
			}
			let jkk = total * 0.0024;
			let jkm = total * 0.003;
			// set hasilnya
			if (!isNaN(jht) && !isNaN(jp) && !isNaN(jkk) && !isNaN(jkm) ) {
				$('#jht').val(Math.ceil(parseInt(jht)));
				$('#jp').val(Math.ceil(parseInt(jp)));
				$('#jkk').val(Math.ceil(parseInt(jkk)));
				$('#jkm').val(Math.ceil(parseInt(jkm)));
			}
		}
	</script>

@endpush
