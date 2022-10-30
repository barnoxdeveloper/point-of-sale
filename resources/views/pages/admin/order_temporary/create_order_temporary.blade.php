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
							<form action="#" method="POST">
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
									<table class="table table-bordered table-striped w-100">
										<thead>
											<tr class="text-center">
												<th>#</th>
												<th>Product</th>
												<th>Harga</th>
												<th width="15%">QTY</th>
												<th>Sub Total</th>
												<th>Hapus</th>
											</tr>
										</thead>
										<tbody>
											<tr class="text-center">
												<td>1</td>
												<td>ABC</td>
												<td>Rp. 1000</td>
												<td>
													<form action="">
														@csrf
														@method('PUT')
														<div class="row">
															<div class="col-8">
																<input type="number" name="quantity" value="2" class="form-control">
															</div>
															<div class="col-2">
																<button type="submit" class="btn btn-success">
																	<i class="fas fa-plus"></i>
																</button>
															</div>
														</div>
													</form>
												</td>
												<td>Rp. 2000</td>
												<td><a href="#" class="btn btn-danger">X</a></td>
											</tr>
										</tbody>
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
											<label for="totalBayar">Total Bayar</label>
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
		<style>
			.table th, td{
				font-size: 12px;
			}
		</style>
@endpush

@push('script-table')
		<script type="text/javascript">
		</script>
@endpush