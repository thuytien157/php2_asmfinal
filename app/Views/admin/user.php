<body>
    <div class="main-content">
        <div class="card-body table-wrapper-scroll-y my-custom-scrollbar">
            <div class="d-flex gap-2 w-75">
                <button type="submit" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Thêm admin</button>
                <form class="d-flex gap-4" action="/php2/ASMC/admin/users" method="post" id="selectRole">
                    <select class="w-100 border p-2 border-dark rounded" name="selectrole" onchange="document.getElementById('selectRole').submit();">
                        <option value="">Tất cả người dùng</option>
                        <option value="user" <?= (isset($selectedRole) && $selectedRole == 'user') ? 'selected' : '' ?>>User</option>
                        <option value="admin" <?= (isset($selectedRole) && $selectedRole == 'admin') ? 'selected' : '' ?>>Admin</option>
                    </select>
                    <input type="search" placeholder="Tìm kiếm" name="key" onchange="document.getElementById('selectRole').submit();">
                </form>

            </div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Fullname</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $key => $user): ?>
                        <?php extract($user); ?>
                        <tr>
                            <td><?= ++$key ?></td>
                            <td><?= $username ?></td>
                            <td><?= $fullname ?></td>
                            <td><?= $address ?></td>
                            <td><?= $email ?></td>
                            <td><?= $phone ?></td>
                            <td>
                                <form method="post" action="/php2/ASMC/admin/users/update/<?= $id ?>" id="roleForm<?= $id ?>">
                                    <select name="role" id="roleSelect" onchange="confirmRoleChange(<?= $id ?>)">
                                        <option value="user" <?= $role == 'user' ? 'selected' : ''; ?>>User</option>
                                        <option value="admin" <?= $role == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                    </select>
                                </form>

                            </td>
                            <td><span class="badge <?= $status == 'active' ? 'bg-success' : 'bg-danger'; ?>"><?= $status ?></span></td>
                            <td>
                                <!-- <button class="btn bg-success text-white"><i class="bi bi-pencil-square"></i></button> -->
                                <div class="d-flex gap-2">
                                    <?php if($role == 'user'): ?>
                                        <button class="btn bg-danger text-white" data-bs-toggle="modal" data-bs-target="#staticBackdrop_delete<?= $id ?>"><i class="bi bi-trash3-fill"></i></button>
                                        <button class="btn bg-secondary text-white" data-bs-toggle="modal" data-bs-target="#staticBackdrop_lock<?= $id ?>"><?= $status == 'active' ? '<i class="bi bi-lock-fill"></i>' : '<i class="bi bi-unlock-fill"></i>'; ?></button>
                                    <?php endif; ?>

                                </div>

                            </td>
                        </tr>

                        <!-- Modal xoá-->
                        <div class="modal fade" id="staticBackdrop_delete<?= $id ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Thông báo</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Bạn chắc chắn muốn xoá người dùng này
                                    </div>
                                    <div class="modal-footer">
                                        <a href="/php2/ASMC/admin/users/delete/<?= $id ?>" class="btn btn-danger">Xác nhận</a>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Modal khoá-->
                        <div class="modal fade" id="staticBackdrop_lock<?= $id ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Thông báo</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Bạn có chắc muốn <?= $status == 'active' ? 'khoá' : 'mở khoá tài khoản'; ?> dùng này?
                                    </div>
                                    <div class="modal-footer">
                                        <form action="/php2/ASMC/admin/users/status/<?= $id ?>" method="post">
                                            <input type="hidden" name="status" value="<?= $status ?>">
                                            <button type="submit" name="lock" class="btn btn-danger">Xác nhận</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal thêm-->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-6" id="staticBackdropLabel">Thêm admin</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/php2/ASMC/admin/users/insert" method="post">
                        <div class="mb-3">
                            <label for="category-name" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username">
                        </div>
                        <div class="mb-3">
                            <label for="category-name" class="form-label">Fullname</label>
                            <input type="text" class="form-control" id="category-name" name="fullname">
                        </div>
                        <div class="mb-3">
                            <label for="category-name" class="form-label">Email</label>
                            <input type="email" class="form-control" id="category-name" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="category-name" class="form-label">Password</label>
                            <input type="pass" class="form-control" id="category-name" name="password">
                        </div>
                        <div class="mb-3">
                            <label for="category-name" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="category-name" name="phone">
                        </div>
                        <div class="mb-3">
                            <label for="category-name" class="form-label">Address</label>
                            <textarea name="address" id="" cols="30" class="form-control"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary" name="insertU">Thêm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>