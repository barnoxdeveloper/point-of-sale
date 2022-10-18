	<footer class="main-footer">
		<strong>Copyright &copy; Barnox Dev
			<script>document.write(new Date().getFullYear());</script>
		</strong>
			All rights reserved.
	</footer>
	<aside class="control-sidebar control-sidebar-dark">
	</aside>
	<div class="modal fade" id="modal-logout" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-logout" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title"></h4>
					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body"></div>
				<div class="modal-footer justify-content-between">
					<form action="{{ route('logout') }}" method="POST">
						@csrf
						<button type="submit" class="btn btn-danger btn-block"><i class="fas fa-sign-out-alt"></i> Logout</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	{{-- <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
		<i class="bi bi-arrow-up"></i>
	</a> --}}
	