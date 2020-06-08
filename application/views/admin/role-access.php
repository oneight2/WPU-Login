<!-- Begin Page Content -->
<div class="container-fluid">
	<h2><?= $title ?></h2>
	<div class="row">
		<div class="col-lg-6">
			<div class="card">
				<div class="card-body">
					
					<?= $this->session->flashdata('message'); ?>
					<h5>Role : <?= $role['role'] ?></h5>
					<table class="table table-hover">
						<thead>
							<tr>
								<th scope="col">No</th>
								<th scope="col">Menu</th>
								<th scope="col">Access</th>
							</tr>
						</thead>
						<tbody>
							<?php $no = 1 ?>
							<?php foreach ($menu as $row ): ?>
							<tr>
								<th scope="row"><?= $no ?></th>
								<td><?= $row['menu'] ?></td>
								<td>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" <?= check_access($role['id'], $row['id']); ?> data-role="<?= $role['id'] ?>" data-menu="<?= $row['id'] ?>">
									</div>
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