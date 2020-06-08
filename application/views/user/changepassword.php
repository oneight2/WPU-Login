<!-- Begin Page Content -->
<div class="container-fluid">
	<h2><?= $title ?></h2>
	<div class="row">
		<div class="col-lg-6">
			<?= $this->session->flashdata('message'); ?>
			<div class="card">
				<div class="card-body">
					<form action="<?= base_url('user/changepassword') ?>" method="post">
						<div class="form-group row">
							<label for="current_password" class="col-sm-4 col-form-label">Current Password</label>
							<div class="col-sm-8">
								<input type="password" class="form-control" id="current_password" name="current_password">
								<?= form_error('current_password','<small class="text-danger pl-3">','</small>') ?>
							</div>
						</div>
						<div class="form-group row">
							<label for="new_password1" class="col-sm-4 col-form-label">New Password</label>
							<div class="col-sm-8">
								<input type="password" class="form-control" id="new_password1" name="new_password1">
								<?= form_error('new_password1','<small class="text-danger pl-3">','</small>') ?>
							</div>
						</div>
						<div class="form-group row">
							<label for="new_password2" class="col-sm-4 col-form-label">Repeat Password</label>
							<div class="col-sm-8">
								<input type="password" class="form-control" id="new_password2" name="new_password2">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-10">
								<button class="btn btn-primary" type="submit">Edit</button>
							</div>
						</div>
					</form>
					
				</div>
			</div>
		</div>
	</div>
</div>