# API Documentation

## Student

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


##### Endpoint

##### Parameters

##### Return


## Courses/Classes

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