# API Documentation

## Table of Contents

#### [User/Student](#UserStudent)
1. [Get Student](#GetStudent)
2. [Add Student](#AddStudent)
3. [Modify Profile](#ModifyProfile)
4. [Modify Professor](#ModifyProfessor)
5. [Request Professor Approval](#ReqProfApproval)

#### [Courses/Classes](#CoursesClasses)
1. [Get Course(s)](#GetCourses)
2. [Get Class(es)](#GetClasses)
3. [Modify Course](#ModifyCourse)
4. [Modify Class](#ModifyClass)
5. [Get Student's Classes](#GetStudClasses)
6. [Enroll Student](#EnrollStudent)
7. [Drop Student](#DropStudent)
8. [Get Favorite Classes](#GetFavoriteClasses)
9. [Favorite Class](#FavoriteClass)
10. [Unfavorite Class](#UnfavoriteClass)
11. [Get Grade](#GetGrade)
12. [Get Waitlist By Class](#WaitlistByClass)
13. [Get Current Semester](#GetCurrentSemester)

<a name="UserStudent"/>

## User/Student

<!-- Start Get Student Info -->
<a name="GetStudent"/>

### Get Student

Gets information about a student.

##### Endpoint

`/GetStudentInfo`

##### Parameters

`student_id` : The ID of the student.

##### Return

TODO

<!-- End Get Student Info -->
<!-- Start Add Student -->

<a name="AddStudent"/>

### Add Student

Adds a student to the database

##### Endpoint

`/AddStudent`

##### Parameters

`student_name` : The name of the student

`date_of_birth` : The student's date of birth (`yyyy-dd-MM`)

`profile_pic` : A link to the student's profile picture

`gender` : The student's gender

`graduation_year` : The student's graduation year

##### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |

<!-- End Add Student -->
<!-- Start Modify Profile -->

<a name="ModifyProfile"/>

### Modify Profile

Modifies information about the user's profile in the database

##### Endpoint

`/ModProfile`

##### Parameters

TODO

##### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |

<!-- End Modify Profile -->
<!-- Start Modify Professor -->

<a name="ModifyProfessor"/>

### Modify Professor

Modifies information about the professor in the database

##### Endpoint

`/ModProfessor`

##### Parameters

TODO

##### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |

<!-- End Modify Professor -->
<!-- Start Request Prof Approval -->

<a name="ReqProfApproval"/>

### Request Professor Approval

Flags the specified account as awaiting approval for becoming a professor

##### Endpoint

`/RequestProfessorApproval`

##### Parameters

TODO

##### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |

<!-- End Request Prof Approval -->
<!-- Start Get Professor by ID -->

<a name="GetProfByID"/>

### Get Professor By ID

Gets the professor name associated with the professor ID

##### Endpoint

`/GetProfessorByID`

##### Parameters

`professor_id` : The ID of the professor

##### Return

```
professor_name
```

<!-- End Get Professor by ID -->

<a name="CoursesClasses"/>

## Courses/Classes

<!-- Start Get Courses -->
<a name="GetCourses"/>

### Get Course(s)

Get all courses that match a given criteria

##### Endpoint

`/GetCourses`

##### Parameters

`course_id` : The ID of the course

##### Return

```
TODO
```

<!-- End Get Courses -->
<!-- Start Get Classes -->

<a name="GetClasses"/>

### Get Class(es)

Gets all classes that match a given criteria

##### Endpoint

`/GetClasses`

##### Parameters

`class_id`

##### Return

TODO

<!-- End Get Classes -->
<!-- Start Modify Course -->

<a name="ModifyCourse"/>

### Modify Course

Changes information about the course in the database

##### Endpoint

`/ModCourse`

##### Parameters

TODO

##### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |

<!-- End Modify Course -->
<!-- Start Modify Class -->

<a name="ModifyClass"/>

### Modify Class

Changes information about the class in the database

##### Endpoint

`/ModClass`

##### Parameters

TODO

##### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |

<!-- End Modify Class -->
<!-- Start Get Students Classes -->

<a name="GetStudClasses"/>

### Get Student's Classes

Gets all classes for a student.

##### Endpoint

`/GetStudentsClasses`

##### Parameters

`student_id` : The ID of the student.

##### Return

```JSON
students_classes
  class1
  class2
  .
  .
  .
  classN
```

<!-- End Get Classes -->
<!-- Start Enroll Student -->

<a name="EnrollStudent"/>

### Enroll Student

Adds a student and class pair to the enrollment db

##### Endpoint

`/EnrollStudent`

##### Parameters

TODO

##### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |

<!-- End Enroll Student -->
<!-- Start Drop Student -->

<a name="DropStudent"/>

### Drop Student

Removed a student from the specified course

##### Endpoint

`/DropStudent`

##### Parameters

TODO

##### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |

<!-- End Drop Student -->
<!-- Start Get Favorited Classes -->

<a name="GetFavoriteClasses"/>

### Get Student's Favorited Classes

Gets all favorited classes for a student

##### Endpoint

`/GetFavoritedClasses`

##### Parameters

`student_id` : The ID of the student.

##### Return

```JSON
favorited_classes
  class1
  class2
  .
  .
  .
  classN
```

<!-- End Get Favorited Classes -->
<!-- Start Favorite Class -->

<a name="FavoriteClass"/>

### Favorite Class

Adds a student and class pair to the favorite classes table

##### Endpoint

`/FavoriteClass`

##### Parameters

TODO

##### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |

<!-- End Favorite Class -->
<!-- Start Unfavorite Class -->
<a name="UnfavoriteClass"/>

### Unfavorite Class

Removed the student class pair from the favorite classes table

##### Endpoint

`/UnfavoriteClass`

##### Parameters

TODO

##### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |


<!-- End Unfavorite Class -->
<!-- Start Get Grade -->
<a name="GetGrade"/>

### Get Grade

Gets the grade for a student in a class

##### Endpoint

`/GetGrade`

##### Parameters

TODO

##### Return

TODO

<!-- End Get Grade -->
<!-- Start Waitlist By Class -->

<a name="WaitlistByClass"/>

### Get Waitlist By Class

Gets a waitlist for a given class

##### Endpoint

`/WaitlistByClass`

##### Parameters

`class_id` : The ID of the class

##### Return

TODO

<!-- End Waitlist By Class -->
<!-- Start Get Current Semester -->

<a name="GetCurrentSemester"/>

### Get Current Semester

Gets the current semester of the system

##### Endpoint

`/GetCurrentSemester`

##### Parameters

None

##### Return

`current_semester`

<!-- End Get Current Semester -->
