<!DOCTYPE html>
<html lang="en">

<?php require_once './includes/head.php'; ?>

<body>
	<div class="wrapper">

		<?php require_once './includes/sidebar.php'; ?>

		<div class="main">

			<?php require_once './includes/navbar.php'; ?>

			<main class="content">
				<div class="container-fluid p-0">
					<div class="row">
						<div class="col-6">
							<h1 class="h3 mb-3">Users</h1>
						</div>
						<div class="col-6 text-end">
							<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUser" id="btn-add">
								Add User
							</button>
						</div>
					</div>


					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-body">
									<table class="table">
										<thead>
											<tr>
												<th>Name</th>
												<th>Email</th>
												<th>Created at</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody id="tbody">
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>

				</div>
			</main>

			<?php require_once './includes/footer.php'; ?>

		</div>
	</div>

	<?php require_once './includes/modals.php'; ?>

	<?php require_once './includes/script.php'; ?>

	<script>
		const errorAdd = document.getElementById('error-add');
		const successAdd = document.getElementById('success-add');

		const addUserForm = document.getElementById('add-user-form');

		addUserForm.addEventListener('submit', function(e) {
			e.preventDefault();
			// alert('Hi Here');

			const nameElementAdd = document.getElementById('name-add');
			const emailElementAdd = document.getElementById('email-add');
			const nameValueAdd = nameElementAdd.value;
			const emailValueAdd = emailElementAdd.value;


			errorAdd.innerText = "";
			nameElementAdd.classList.remove('is-invalid');
			emailElementAdd.classList.remove('is-invalid');


			if (nameValueAdd == "" || nameValueAdd === undefined) {
				errorAdd.innerText = "please Provide your name!";
				nameElementAdd.classList.add('is-invalid');

			} else if (emailValueAdd == "" || emailValueAdd === undefined) {
				errorAdd.innerText = "please Provide your email!";
				emailElementAdd.classList.add('is-invalid');
			} else {
				// console.log('done');
				const data = {
					name: nameValueAdd,
					email: emailValueAdd,
					submit: 1
				}
				fetch('./add_user.php', {
					method: 'POST',
					body: JSON.stringify(data),
					headers: {
						'Content-Type': 'application.json'
					}
				}).then(function(response) {
					return response.json();
				}).then(function(result) {
					if (result.emptyName) {
						errorAdd.innerText = result.emptyName;
						nameElementAdd.classList.add('is-invalid');
					} else if (result.emptyEmail) {
						errorAdd.innerText = result.emptyEmail;
						emailElementAdd.classList.add('is-invalid');
					} else if (result.success) {
						successAdd.innerText = result.success;
						addUserForm.reset();
						showUsers()
					} else if (result.failed) {
						errorAdd.innerText = result.failed;
					}
				})
				// console.log(data);
			}

		})

		function showUsers() {

			fetch('./show_users.php', {
				headers: {
					"Content-Type": "application.json"
				}
			}).then(function(response) {
				return response.json();
			}).then(function(result) {
				const tbody = document.getElementById('tbody');
				let row = "";
				result.forEach(function(value) {
					row += `<tr><td>${value['name']}</td><td>${value['email']}</td><td>${value['Created_at']}</td><td><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editUser" onclick="editUser(${value['id']})">Edit User</button> <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUser"onclick="deleteUser(${value['id']})">Delete User</button></td></tr>`;
				});
				tbody.innerHTML += row;
			})

		}
		showUsers();


		function editUser(id) {
			const errorEdit = document.getElementById('error-edit');
			const successEdit = document.getElementById('success-edit');

			const editUserForm = document.getElementById('edit-user-form');


				// alert('Hello me Here');


				data = {
					id: id,
					submit: 1
				}

				fetch('./show_single_user.php', {
					method: "POST",
					body: JSON.stringify(data),
					headers: {
						"Content-Type": "application.json"
					}
				}).then(function(response){
					return response.json();
				}).then(function(result){
					const nameElementedit = document.getElementById('name-edit');
					const emailElementedit = document.getElementById('email-edit');
					nameElementedit.setAttribute("value", result.name);
					emailElementedit.setAttribute("value", result.email);
					// console.log(result);
				})


				editUserForm.addEventListener("submit", function(e) {
				e.preventDefault();

				const nameElementEdit = document.getElementById('name-edit');
				const emailElementEdit = document.getElementById('email-edit');

				let nameValueEdit = nameElementEdit.value;
				let emailValueEdit = emailElementEdit.value;

				errorEdit.innerText = "";
				errorEdit.innerText = "";
				nameElementEdit.classList.remove('is-invalid');
				emailElementEdit.classList.remove('is-invalid');


				if (nameValueEdit == "" || nameValueEdit === undefined) {
					errorEdit.innerText = "Please enter your name!";
					nameElementEdit.classList.add('is-invalid');
				} else if (emailValueEdit == "" || emailValueEdit === undefined) {
					errorEdit.innerText = "Please enter your email!";
					emailElementEdit.classList.add('is-invalid');
				} else {

					data = {
						name: nameValueEdit,
						email: emailValueEdit,
						id:id,
						submit:1
					}
					fetch('./edit_user.php', {
						method:"POST",
						body: JSON.stringify(data),
						headers: {
						"Content-Type": "application.json"
					}

					}).then(function(response){
						return response.json();
					}).then(function(result){
						if(result.emptyName){
							errorEdit.innerText = result.emptyName;
						}else if(result.emptyEmail){
							errorEdit.innerText = result.emptyEmail;
						}else if(result.error){
							errorEdit.innerText = result.error;
						}else if(result.success){
							successEdit.innerText = result.success;
							showUsers();
						}else {
							errorEdit.innerText = result.error;         //show error if query doesn't works;
						}
					})



				}



			})
		}






		function deleteUser(id) {
			const errorDelete = document.getElementById('error-delete');
			const successDelete = document.getElementById('success-delete');

			const deleteUserForm = document.getElementById('delete-user-form');
			deleteUserForm.addEventListener('submit', function(e) {
				e.preventDefault();

				// console.log(deleteUserForm);


				const data = {
					id: id,
					submit: 1
				}
				fetch('./delete_user.php', {
					method: 'POST',
					body: JSON.stringify(data),
					headers: {
						"Content-Type": "application.json"
					}
				}).then(function(response) {
					return response.json();
				}).then(function(result) {
					if (result.success) {
						successDelete.innerText = result.success;
						showUsers();
					} else {
						errorDelete.innerText = result.error;

					}
				})

				// showUsers();

				// const tbody = document.getElementById('tbody');
				// 	let row  = "";
				// 	result.forEach(function(value){
				// 		row += `<tr><td>${value['name']}</td><td>${value['email']}</td><td>${value['Created_at']}</td><td><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editUser" onclick="editUser(${value['id']})">Edit User</button> <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUser"onclick="deleteUser(${value['id']})">Delete User</button></td></tr>`;
				// 	});
				// 	tbody.innerHTML += row;
			})
		}


		// deleteUser();
	</script>

</body>

</html>