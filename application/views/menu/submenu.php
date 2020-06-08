<!-- Begin Page Content -->
<div class="container-fluid">
	<h2><?= $title ?></h2>
	<div class="row">
		<div class="col-lg-10">
			<div class="card">
				<div class="card-body">
					<?php if (validation_errors()): ?>
						<div class="alert alert-danger" role="alert">
							<?= validation_errors() ?>
						</div>
					<?php endif ?>
					<?= form_error('menu','<div class="alert alert-danger" role="alert">','</div>') ?>
					<?= $this->session->flashdata('message'); ?>
					<a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#subMenu">Add new submenu</a>
					<table class="table table-hover">
						<thead>
							<tr>
								<th scope="col">No</th>
								<th scope="col">SubMenu</th>
								<th scope="col">Menu Id	</th>
								<th scope="col">Url</th>
								<th scope="col">Icon</th>
								<th scope="col">Active</th>
								<th scope="col">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php $no = 1 ?>
							<?php foreach ($submenu as $row ): ?>
							<tr>
								<th scope="row"><?= $no ?></th>
								<td><?= $row['title'] ?></td>
								<td><?= $row['menu'] ?></td>
								<td><?= $row['url'] ?></td>
								<td><?= $row['icon'] ?></td>
								<td><?= $row['is_active'] ?></td>

								<td>
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
<div class="modal fade" id="subMenu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Add new menu</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="<?= base_url() ?>menu/submenu" method="post">
				<div class="modal-body">
					<div class="form-group">
						<input type="text" class="form-control" id="menu" placeholder="submenu title" name="title">

					</div>
					<div class="form-group">
						<select name="menu_id" id="menu_id" class="form-control">
							<option value="">Select Menu</option>
							<?php foreach ($menu as $row): ?>
								<option value="<?= $row['id'] ?>">
									<?= $row['menu'] ?>
								</option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" id="url" placeholder="submenu Url" name="url">
					</div>
					<div class="form-group">
						<input type="text" class="form-control" id="icon" placeholder="submenu icon" name="icon">
					</div>
					<div class="form-group">
						<div class="form-check">
							<input type="checkbox" class="form-check-input" value="1" name="is_active" id="is_active" checked>
							<label for="is_active" class="form-check-label">Active Submenu</label>
						</div>
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