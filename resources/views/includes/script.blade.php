
		<!-- jQuery -->
		<script src="{{ url('backend/plugins/jquery/jquery.min.js') }}"></script>
		<script src="{{ url('backend/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
		<!-- jQuery UI 1.11.4 -->
		<script src="{{ url('backend/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
		<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
		<script>$.widget.bridge('uibutton', $.ui.button)</script>
		<!-- Bootstrap 5 -->
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
		{{-- Data Table --}}
		@stack('script-table')
		{{-- Select2 --}}
		@stack('script-select2')
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		{{-- @stack('chart-js') --}}
		<!-- daterangepicker -->
		{{-- <script src="{{ url('backend/plugins/moment/moment.min.js') }}"></script> --}}
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		@stack('script-daterange')
		<!-- Tempusdominus Bootstrap 4 -->
		<script src="{{ url('backend/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
		<!-- overlayScrollbars -->
		<script src="{{ url('backend/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
		<!-- AdminLTE App -->
		<script src="{{ url('backend/dist/js/adminlte.js') }}"></script>
		@stack('script-dashboard-bottom')
		
		<script>
			$(function() {
				$.fn.modal.Constructor.prototype.enforceFocus = function() {};
				const myModal = new bootstrap.Modal(document.getElementById('modal-logout'));
				$('#btn-logout').click(function () {
					myModal.show();
					$('.modal-title').text("Logout");
					$('.modal-body').append(`<p>Select "Logout" below if you are ready to end your current session.</p>`);
				});
			});
		</script>
		@stack('modal-post')