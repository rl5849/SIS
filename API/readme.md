# API Documentation

## Table of Contents

### [**User/Student**](#UserStudent)

1. [Get Student](#GetStudentInfo)
2. [Get Student's Classes For Semester](#GetStudentsClassesForSemester) Needs Implementation
3. [Add User](#AddUser) Needs Implementation
4. [Check if User Exists](#UserExists) Needs Implementation
5. [Modify Profile](#ModProfile)
6. [Request Professor Approval](#RequestProfessorApproval)
7. [Delete Professor Request](#DeleteProfRequest) Needs Implementation
8. [Get Professor Requests](#GetProfessorRequests) Needs Implementation
9. [Approve Professor Request](#ApproveProfRequest) Needs Implementation
10. [Get Professors](#GetProfs)
11. [Get Professor By ID](#GetProfessorByID)
12. [Get User ID from LinkedIn ID](#GetUserIDFromLinkedInID)
13. [Get User ID from Login](#GetUserIDFromLogin) Needs Implementation
14. [Check if Admin](#CheckIfAdmin) Needs Implementation
15. [Create Login](#CreateLogin) Needs Implementation

### [**Courses/Classes**](#CoursesClasses)

1. [Get Course List](#GetCourseList) Needs Implementation
2. [Get Course(s)](#GetCourses)
3. [Get Class(es)](#GetClasses)
4. [Get Course Info](#GetCourseInfo) Needs Implementation
5. [Get Class Info](#GetClassInfo) Needs Implementation
6. [Modify Course](#ModCourse)
7. [Modify Class](#ModClass)
8. [Add Class](#AddClass) Needs Implementation
9. [Add Course](#AddCourse) Needs Implementation
10. [Delete Class](#DeleteClass) Needs Implementation
11. [Get Student's Classes](#GetStudentsClasses)
12. [Get Students By Class ID](#GetStudentsByClassId) Needs Implementation
13. [Enroll Student](#EnrollStudent)
14. [Ennroll Students From Waitlist](#EnrollFromWaitlist) Needs Implementation
15. [Drop Student](#DropStudent)
16. [Check Enrollment Status](#CheckEnrollmentStatus) Needs Implementation
17. [Get Favorite Classes](#GetFavoritedClasses)
18. [Check Favorite Status](#CheckFavoriteStatus) Needs Implementation
19. [Favorite Class](#FavoriteClass)
20. [Unfavorite Class](#UnfavoriteClass)
21. [Get GPA](#GetGPA) Needs Implementation
22. [Get Waitlist By Class](#WaitlistByClass)
23. [Get Current Semester](#GetCurrentSemester)
24. [Get Semesters](#GetSemesters) Needs Implementation
25. [Add Semester](#AddSemester)
26. [Request Special Access](#RequestSpecialAccess) Needs Implementation

<a name="UserStudent"/>

## User/Student

<!-- Start Get Student Info -->
<a name="GetStudentInfo"/>

### Get Student

Gets information about a student

#### Endpoint

`/GetStudentInfo`

#### Parameters

`student_id` : The ID of the student

#### Return

```
{ student_info : [
    {
        profile_pic : url
        major : string
        gender : string
        graduation_year : string
        date_of_birth : string
        student_id : int
        student_name : string
    }]
}
```

<!-- End Get Student Info -->
<!-- Start Get Student's Classes for Semester -->
<a name="GetStudentsClassesForSemester"/>

### Get Student's Classes for Semester

Gets a student's classes for a given semester

#### Endpoint

`/GetStudentsClassesForSemester`

#### Parameters

`user_id` : The ID of the student
`semester_id` : The ID of the semester

#### Return
TODO
```
```

<!-- End Get Student's Classes for Semester -->
<!-- Start Add User-->

<a name="AddUser"/>

### Add User

Adds a user to the system

#### Endpoint

`/AddUser`

#### Parameters

`name` : The name of the user
`linkedin_id` : LinkedIn ID of the user
`profile_pic` : Link to profile picture of the user

#### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'INSERT FAILED'`  |

<!-- End Add User -->
<!-- Start User Exists-->

<a name="UserExists"/>

### User Exists

Adds a user to the system

#### Endpoint

`/UserExists`

#### Parameters

`username` : username of user being checked

#### Return

```
{ "exists" : boolean }
```

<!-- End User Exists -->
<!-- Start Modify Profile -->

<a name="ModProfile"/>

### Modify Profile

Modifies information about the user's profile in the database

#### Endpoint

`/ModProfile`

#### Parameters

None at the moment

#### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |

<!-- End Modify Profile -->
<!-- Start Request Prof Approval -->

<a name="RequestProfessorApproval"/>

### Request Professor Approval

Flags the specified account as awaiting approval for becoming a professor

#### Endpoint

`/RequestProfessorApproval`

#### Parameters

None at the moment

#### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |

<!-- End Request Prof Approval -->
<!-- Start Delete Prof Request -->

<a name="DeleteProfRequest"/>

### Delete Professor Request

Deletes the request for a specific professor

#### Endpoint

`/DeleteProfRequest`

#### Parameters

`user_id` : ID of user to professor request

#### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |

<!-- End Delete Prof Request -->
<!-- Start Get Prof Requests -->

<a name="GetProfessorRequests"/>

### Request Professor Approval

Gets all current professor requests

#### Endpoint

`/GetProfessorRequests`

#### Parameters

None

#### Return

{ "requests" : [
        [
            "professor" : string
            "course_code" : int
        ],
        .
        .
        .
    ]
}


<!-- End Get Prof Request -->
<!-- Start Approve Prof Request -->

<a name="ApproveProfRequest"/>

### Request Professor Approval

Approves the request for a specific professor

#### Approve Professor Request

`/ApproveProfRequest`

#### Parameters

`user_id` : ID of professor to approve request

#### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |

<!-- End Approve Prof Request -->
<!-- Start GetProfs -->

<a name="GetProfs"/>

### Get Professors

Gets a list of all of the professors in the system

#### Endpoint

`/GetProfs`

#### Parameters

None at the moment

#### Return

```
{ "profs" : [
    "professor" : string,
    .
    .
    .
    ]
}
```

<!-- End Request GetProfs -->
<!-- Start Get Professor by ID -->

<a name="GetProfessorByID"/>

### Get Professor By ID

Gets the professor name associated with the professor ID

#### Endpoint

`/GetProfessorByID`

#### Parameters

`professor_id` : The ID of the professor

#### Return

```
{ "professor_name" : string }
```

<!-- End Get Professor by ID -->
<!-- Start Get User Id from LinkedIn ID-->

<a name="GetUserIDFromLinkedInID"/>

### Get User ID From LinlkedIn ID

Gets a user ID based on a provided LinkedIn authenticatiopn ID

#### Endpoint

`/GetUserIDFromLinkedInID`

#### Parameters

`linkedin_id` : The ID passed from LinkedIn

#### Return

```
{ "user_id" : int }
```

<!-- End Get Get User ID From LinkedIn ID-->
<!-- Start Get User Id from Login-->

<a name="GetUserIDFromLogin"/>

### Get User ID From Login

Gets a user ID based on provided login credentials

#### Endpoint

`/GetUserIDFromLogin`

#### Parameters

`user_name` : The user's user name
`password` : The user's passwor

#### Return

```
{ "user_id" : int }
```

<!-- End Get Get User ID From Login-->
<!-- Start Check If Admin-->

<a name="CheckIfAdmin"/>

### Check if Admin

Gets a user ID based on provided login credentials

#### Endpoint

`/CheckIfAdmin`

#### Parameters

`id` : The ID of the user to be checked

#### Return

```
{ "is_admin" : boolean }
```

<!-- End Check If Admin-->
<!-- Start Create Login-->

<a name="CreateLogin"/>

### Create Login

Create user id with login credentials

#### Endpoint

`/CreateLogin`

#### Parameters

`username` : Account user name
`password` : Account password

#### Return

```
| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |
| `'NEW USER ID'`    |
```

<!-- End Create Login-->
<!-- Start Courses Classes -->
<a name="CoursesClasses"/>

## Courses/Classes

<!-- Start Get Courses -->
<a name="GetCourses"/>

### Get Course(s)

Get all courses that match a given criteria

#### Endpoint

`/GetCourses`

#### Parameters

`course_id` : The ID of the course

#### Return

```
{
    "courses": [
        {
            "course_id": int,
            "course_description": string,
            "course_name": string
        },
        .
        .
        .
    ]
}
```

<!-- End Get Courses -->
<!-- Start Get Classes -->

<a name="GetClasses"/>

### Get Class(es)

Gets all classes that match a given criteria

#### Endpoint

`/GetClasses`

#### Parameters

`class_id`

#### Return

```
{
    "classs": [
        {
            "num_enrolled": int,
            "capacity": int,
            "name": string,
            "class_id": int,
            "professor_id": int,
            "section": int,
            "room_number": int,
            "credits": int,
            "time": string,
            "course_id": int
        },
        .
        .
        .
    ]
}
```

<!-- End Get Classes -->
<!-- Start Modify Course -->

<a name="ModCourse"/>

### Modify Course

Changes information about the course in the database

#### Endpoint

`/ModCourse`

#### Parameters

None at the moment

#### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |

<!-- End Modify Course -->
<!-- Start Modify Class -->

<a name="ModClass"/>

### Modify Class

Changes information about the class in the database

#### Endpoint

`/ModClass`

#### Parameters

None at the moment

#### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |

<!-- End Modify Class -->
<!-- Start Get Students Classes -->

<a name="GetStudentsClasses"/>

### Get Student's Classes

Gets all classes for a student.

#### Endpoint

`/GetStudentsClasses`

#### Parameters

`student_id` : The ID of the student.

#### Return

```
{ students_classes : [
    class1,
    class2,
    .
    .
    .
    classN
    ]
}
```

<!-- End Get Classes -->
<!-- Start Enroll Student -->

<a name="EnrollStudent"/>

### Enroll Student

Adds a student and class pair to the enrollment db

#### Endpoint

`/EnrollStudent`

#### Parameters

`class_id` : The id of the class
`user_id` : The uid of the student being enrolled

#### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |

<!-- End Enroll Student -->
<!-- Start Drop Student -->

<a name="DropStudent"/>

### Drop Student

Removed a student from the specified course

#### Endpoint

`/DropStudent`

#### Parameters

None at the moment

#### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |

<!-- End Drop Student -->
<!-- Start Get Favorited Classes -->

<a name="GetFavoritedClasses"/>

### Get Student's Favorited Classes

Gets all favorited classes for a student

#### Endpoint

`/GetFavoritedClasses`

#### Parameters

`student_id` : The ID of the student.

#### Return

```
{ favorited_classes : [
    class1
    class2
    .
    .
    .
    classN
    ]
}
```

<!-- End Get Favorited Classes -->
<!-- Start Favorite Class -->

<a name="FavoriteClass"/>

### Favorite Class

Adds a student and class pair to the favorite classes table

#### Endpoint

`/FavoriteClass`

#### Parameters

None at the moment

#### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |

<!-- End Favorite Class -->
<!-- Start Unfavorite Class -->
<a name="UnfavoriteClass"/>

### Unfavorite Class

Removed the student class pair from the favorite classes table

#### Endpoint

`/UnfavoriteClass`

#### Parameters

None at the moment

#### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |

<!-- End Unfavorite Class -->
<!-- Start Waitlist By Class -->

<a name="WaitlistByClass"/>

### Get Waitlist By Class

Gets a waitlist for a given class

#### Endpoint

`/WaitlistByClass`

#### Parameters

`class_id` : The ID of the class

#### Return

```
{ waitlist : [
    student_id1,
    student_id2,
    .
    .
    .
    student_idN
    ]
}
```

<!-- End Waitlist By Class -->
<!-- Start Get Current Semester -->

<a name="GetCurrentSemester"/>

### Get Current Semester

Gets the current semester of the system

#### Endpoint

`/GetCurrentSemester`

#### Parameters

None

#### Return

```
{current_semester : string }
```

<!-- End Get Current Semester -->
