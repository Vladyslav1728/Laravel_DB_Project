<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

---

## Images (Cvicenie 8) - Authorize

```Task 1: Note and Task authorize```

--

---

```Task 2: CommentController```

The method was tested in Postman:

• POST /api/notes/1/comments - ```ADD COMM```
![1.png](images/comments/1.png)

• GET /api/notes/1/comments - ```CHECK ALL COMM```
![2.png](images/comments/2.png)

• PATCH /api/comments/5 - ```UPDATE COMM (USER vs ADMIN)```
![3.png](images/comments/3.png)
![4.png](images/comments/4.png)

• DELETE /api/comments/5 - ```DELETE COMM```
![5.png](images/comments/5.png)

---

```Task 3: AuthController - Photo / Files / Links```

The method was tested in Postman:

• POST /api/auth/me/profile-photo - ```ADD PHOTO```
![1.png](images/upload_files/1.png)

• POST /api/notes/{note}/attachments - ```ADD FILES```
![2.png](images/upload_files/2.png)

• GET /api/notes/{note}/attachments - ```CHECK FILES```
![3.png](images/upload_files/3.png)

• GET /api/attachments/{note}/link - ```GET A TEMPORARY LINK```
![4.png](images/upload_files/4.png)

---

## Images (Cvicenie 7) - Sanctum

```Task 1: AuthController Test```

The method was tested in Postman:

• POST /api/auth/login

• POST /api/auth/register

• POST /api/auth/me

• POST /api/auth/logout

![1.png](images/auth/1.png)
![2.png](images/auth/2.png)
![3.png](images/auth/3.png)
![4.png](images/auth/4.png)

---

```Task 2: AuthController Test - New methods```

The method was tested in Postman:

• POST /api/auth/logout-all

• POST /api/auth/change-password

• PATCH /api/auth/update-profile

![5.png](images/auth/5.png)
![6.png](images/auth/6.png)
![7.png](images/auth/7.png)

---

```Task 3: Middleware - For Admin```

Here I showed that after I changed my role to a regular ```User```, I ```can't delete the category```.

![8.png](images/auth/8.png)
![9.png](images/auth/9.png)

---

## Images (Cvicenie 6) - Connections and Factories

```Task 1: NoteController CRUD``` 

The method was tested in Postman:

• successful request ```(existing ID)```

• unsuccessful request ```(non-existent ID ➔ 404 JSON)```

![1-404.png](images/note_methods/1-404.png)
![2-404.png](images/note_methods/2-404.png)

---

```Task 2: CategoryController CRUD```

The method was tested in Postman:

• GET /api/categories

• PUT /api/categories/1

• POST /api/categories - ```Check for duplicate name```

![6.1.png](images/categories_crud/6.1.png)
![6.2.png](images/categories_crud/6.2.png)
![6.3.png](images/categories_crud/6.3.png)

---

```Task 3: TaskController CRUD```

The method was tested in Postman:

• POST /api/notes/4/tasks

• POST /api/notes/4/tasks - ```failed validation```

• GET /api/notes/111/tasks/111 - ```non-existent ID (404 JSON)```

![1.png](images/tasks_crud/1.png)
![2.png](images/tasks_crud/2.png)
![3.png](images/tasks_crud/3.png)

---

## Images (Cvicenie 5) - Eloquent ORM / QB

New methods are created that are then used in the NoteController:

```pin, unpin, publish, archive``` and ```latest-notes -``` this method sorts notes from newest to oldest (of a specific person)

![1.png](images/note_methods/1.png)
![2.png](images/note_methods/2.png)
![3.png](images/note_methods/3.png)
![4.png](images/note_methods/4.png)
![5.png](images/note_methods/5.png)

---

## Images (Cvicenie 4)

```CRUD```

![1.png](images/common/1.png)
![2.png](images/common/2.png)
![3.png](images/common/3.png)
![4.png](images/common/4.png)
![5.png](images/common/5.png)

```CUSTOM ENDPOINTS (CVICENIE)```

![1.png](images/custom_endpoints/1.png)
![2.png](images/custom_endpoints/2.png)
![3.png](images/custom_endpoints/3.png)
![4.png](images/custom_endpoints/4.png)

```ONE CUSTOM METHOD (MY)```

![my_custom.png](images/my_custom/my_custom.png)

```CRUD CATEGORIES```

![1.png](images/categories_crud/1.png)
![2.png](images/categories_crud/2.png)
![3.png](images/categories_crud/3.png)
![4.png](images/categories_crud/4.png)
![5.png](images/categories_crud/5.png)
