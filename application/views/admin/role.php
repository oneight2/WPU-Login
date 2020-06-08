<!-- Begin Page Content -->
<div class="container-fluid">
	<h2><?= $title ?></h2>
	<div class="row">
		<div class="col-lg-6">
			<div class="card">
				<div class="card-body">
					<?= form_error('menu','<div class="alert alert-danger" role="alert">','</div>') ?>
					<?= $this->session->flashdata('message'); ?>
					<a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#role">Add new role</a>
					<table class="table table-hover">
						<thead>
							<tr>
								<th scope="col">No</th>
								<th scope="col">Role</th>
								<th scope="col">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php $no = 1 ?>
							<?php foreach ($role as $row ): ?>
							<tr>
								<th scope="row"><?= $no ?></th>
								<td><?= $row['role'] ?></td>
								<td>
									<a href="<?= base_url('admin/roleaccess/'). $row['id'] ?>" class="badge badge-warning">Access</a>
									<a href="" class="badge badge-primary">Edit</a>
									<a href="" class="badge badge-danger">Delete</a>
								</td>
							</tr>
							<?php $no++;  ?>
							<?php endforeach ?>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	
</div>
<!-- /.container-fluid -->
<!-- Modal -->
<div class="modal fade" id="role" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Add new menu</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="<?= base_url() ?>admin/role" method="post">
				<div class="modal-body">
					<div class="form-group">
						<input type="text" class="form-control" id="role" placeholder="Role Name" name="role">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Add</button>
				</div>
			</form>
		</div>
	</div>
</div>