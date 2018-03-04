# API Documentation

## Student

<!-- Start Get Student Info -->
### Get Student

Gets information about a student.

##### Endpoint

`/GetStudentInfo`

##### Parameters

`student_id` : The ID of the student.

##### Return



<!-- End Get Student Info -->
<!-- Start Add Student -->

### Add Student

Adds a student to the database

##### Endpoint

`/add_student`

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

## Courses/Classes

<!-- Start Get Classes -->

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
<!-- Start Get Favorited Classes -->

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


##### Endpoint


##### Parameters


##### Return

