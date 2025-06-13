<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content-header">					
		<div class="container-fluid my-2">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Create User</h1>
				</div>
				<div class="col-sm-6 text-right">
					<a href="users.php" class="btn btn-primary">Back</a>
				</div>
			</div>
		</div>
	</section>

	<section class="content">
		<div class="container-fluid">
			<form method="post" action="?page=create_user_controller" novalidate>
				<div class="card">
					<div class="card-body">								
						<div class="row">
							<div class="col-md-6 mb-3">
								<label for="name">Name</label>
								<input type="text" name="name" id="name" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                                value="<?= htmlspecialchars($old['name'] ?? '') ?>"required>
                                <?php if (isset($errors['name'])): ?>
                                <?php foreach ((array)$errors['name'] as $error): ?>
                                <div class="invalid-feedback d-block" style="color:red;">
                                <?= htmlspecialchars($error) ?>
                                </div>
                                <?php endforeach; ?>
                                <?php endif; ?>
							</div>
							<div class="col-md-6 mb-3">
								<label for="email">Email</label>
								<input type="email" name="email" id="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                                value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                                <?php if (isset($errors['email'])): ?>
                                <?php foreach ((array)$errors['email'] as $error): ?>
                                <div class="invalid-feedback d-block" style="color:red;">
                                <?= htmlspecialchars($error) ?>
                                </div>
                                <?php endforeach; ?>
                                <?php endif; ?>
							</div>
							<div class="col-md-6 mb-3">
								<label for="phone">Phone</label>
								<input type="text" name="phone" id="phone" class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>"
                                value="<?= htmlspecialchars($old['phone'] ?? '') ?>" required>
                                <?php if (isset($errors['phone'])): ?>
                                <?php foreach ((array)$errors['phone'] as $error): ?>
                                <div class="invalid-feedback d-block" style="color:red;">
                                <?= htmlspecialchars($error) ?>
                                </div>
                                <?php endforeach; ?>
                                <?php endif; ?>
							</div>
							<div class="col-md-6 mb-3">
								<label for="password">Password</label>
								<input type="password" name="password" id="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                                value="<?= htmlspecialchars($old['password'] ?? '') ?>"required>
                                <?php if (isset($errors['password'])): ?>
                                <?php foreach ((array)$errors['password'] as $error): ?>
                                <div class="invalid-feedback d-block" style="color:red;">
                                <?= htmlspecialchars($error) ?>
                                </div>
                                <?php endforeach; ?>
                                <?php endif; ?>
							</div>
							<div class="col-md-6 mb-3">
								<label for="gender">Gender</label>
								<select name="gender" id="gender" class="form-control <?= isset($errors['gender']) ? 'is-invalid' : '' ?>"> 
                                <option value="">Select Gender</option>
                                <option value="male" <?= (isset($old['gender']) && $old['gender'] === 'Male') ? 'selected' : '' ?>>Male</option>
                                <option value="female" <?= (isset($old['gender']) && $old['gender'] === 'Female') ? 'selected' : '' ?>>Female</option>
                                </select>
                                <?php if (isset($errors['gender'])): ?>
                                <?php foreach ((array)$errors['gender'] as $error): ?>
                                <div class="invalid-feedback d-block" style="color:red;">
                                <?= htmlspecialchars($error) ?>
                                </div>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
							<div class="col-md-6 mb-3">
								<label for="dob">Date of Birth</label>
								<input type="date" name="birth_date" id="dob" class="form-control <?= isset($errors['birth_date']) ? 'is-invalid' : '' ?>"
                                value="<?= htmlspecialchars($old['birth_date'] ?? '') ?>" required>
                                <?php if (isset($errors['birth_date'])): ?>
                                <?php foreach ((array)$errors['birth_date'] as $error): ?>
                                <div class="invalid-feedback d-block" style="color:red;">
                                <?= htmlspecialchars($error) ?>
                                </div>
                                <?php endforeach; ?>
                                <?php endif; ?>
							</div>
							<div class="col-md-6 mb-3">
								<label for="role">Role</label>
								<select name="role" id="role"class="form-control <?= isset($errors['role']) ? 'is-invalid' : '' ?>">
									<option value="user" selected>User</option>
									<option value="admin">Admin</option>
									<option value="doctor">SubAdmin</option>
								</select>
                                <?php if (isset($errors['role'])): ?>
                                <?php foreach ((array)$errors['role'] as $error): ?>
                                <div class="invalid-feedback d-block" style="color:red;">
                                <?= htmlspecialchars($error) ?>
                                </div>
                                <?php endforeach; ?>
                                <?php endif; ?>
							</div>
						</div>
					</div>							
				</div>
				<div class="pb-5 pt-3">
					<button class="btn btn-primary">Create</button>
					<a href="?page=manage_users" class="btn btn-outline-dark ml-3">Cancel</a>
				</div>
			</form>
		</div>
	</section>
</div>
