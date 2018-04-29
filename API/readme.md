# API Documentation

## Table of Contents

### [**User/Student**](#UserStudent)

1. [Get Student](#GetStudentInfo)
2. [Get Student's Classes For Semester](#GetStudentsClassesForSemester)
3. [Add User](#AddUser) Needs Implementation
4. [Check if User Exists](#UserExists)
5. [Modify Profile](#ModProfile)
6. [Request Professor Approval](#RequestProfessorApproval)
7. [Delete Professor Request](#DeleteProfRequest)
8. [Get Professor Requests](#GetProfessorRequests)
9. [Approve Professor Request](#ApproveProfRequest)
10. [Get Professors](#GetProfs)
11. [Get Professor By ID](#GetProfessorByID)
12. [Get User ID from LinkedIn ID](#GetUserIDFromLinkedInID)
13. [Get User ID from Login](#GetUserIDFromLogin)
14. [Check if Admin](#CheckIfAdmin)
15. [Check if Professor](#CheckIfProfessor)
16. [Make Admin](#MakeAdmin)
17. [Create Login](#CreateLogin)
18. [Get Users](#GetUsers)

### [**Courses/Classes**](#CoursesClasses)

1. [Get Course List](#GetCourseList)
2. [Get Course(s)](#GetCourses)
3. [Get Class(es)](#GetClasses)
4. [Get Course Info](#GetCourseInfo)
5. [Get Class Info](#GetClassInfo)
6. [Modify Course](#ModCourse)
7. [Modify Class](#ModClass)
8. [Add Class](#AddClass)
9. [Add Course](#AddCourse)
10. [Delete Class](#DeleteClass)
11. [Delete Course](#DeleteCourse)
13. [Get Students By Class ID](#GetStudentsByClassId)
14. [Enroll Student](#EnrollStudent)
15. [Enroll Students From Waitlist](#EnrollFromWaitlist)
16. [Drop Student](#DropStudent)
17. [Check Enrollment Status](#CheckEnrollmentStatus)
18. [Get Favorite Classes](#GetFavoritedClasses) Needs Stuff
19. [Check Favorite Status](#CheckFavoriteStatus)
20. [Favorite Class](#FavoriteClass)
21. [Unfavorite Class](#UnfavoriteClass)
22. [Get GPA](#GetGPA) Needs Implementation
23. [Get Waitlist By Class](#WaitlistByClass)
24. [Get Current Semester](#GetCurrentSemester)
25. [Get Semesters](#GetSemesters)
26. [Add Semester](#AddSemester)
27. [Request Special Access](#RequestSpecialAccess)
28. [Get Access Requests](#GetAccessRequests)
29. [Get Student Access](#GetStudentAccess)
30. [Get Majors](#GetMajors)
31. [Get Prerequisites](#GetPrereqs)
32. [Check Prerequisite](#CheckPrereq)
33. [Add Prerequisites](#AddPrereqs)
34. [Delete Prerequisite](#DeletePrereq)

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
{ "student_info" : [
    {
        "profile_pic" : url,
        "major" : str,
        "gender" : str,
        "GPA" : int,
        "prof_requested" : int,
        "date_of_birth" : str,
        "major_name" : str,
        "graduation_year" : str,
        "student_name" : str,
        "student_id" : int
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

```
{
    "classes": [
        {
            "name": str,
            "class_id": int,
            "section": int,
            "room_number": int,
            "professor_name": str,
            "grade": str,
            "time": str,
            "course_id": int
        },
        .
        .
        .
    ]
}
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

`user_id` : ID of account awaiting approval

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

`user_id` : ID of user to delete professor request

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
```
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
```

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

Checks if a user is an admin

#### Endpoint

`/CheckIfAdmin`

#### Parameters

`id` : The ID of the user to be checked

#### Return

```
{ "is_admin" : boolean }
```

<!-- End Check If Admin-->
<!-- Start Check If Professor-->

<a name="CheckIfProfessor"/>

### Check if Professor

Checks if a user is a professor

#### Endpoint

`/CheckIfProfessor`

#### Parameters

`id` : The ID of the user to be checked

#### Return

```
{ "is_prof" : boolean }
```

<!-- End Check If Professor-->
<!-- Start Make Admin-->

<a name="MakeAdmin"/>

### Make Admin

Makes a user an admin

#### Endpoint

`/MakeAdmin`

#### Parameters

`id` : The ID of the user to be made admin

#### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |

<!-- End Make Admin-->
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

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |
| `'NEW USER ID'`    |

<!-- End Create Login-->
<!-- Start Get Users-->

<a name="GetUsers"/>

### Get Users

Gets all users currently in the system

#### Endpoint

`/GetUsers`

#### Parameters

None

#### Return

```
{
    "users": [
        {
            "username": string,
            "user_status": int,
            "user_id": int,
            "name": string
        },
        .
        .
        .
    ]
}
```

<!-- End Get Users-->
<!-- Start Courses Classes -->
<a name="CoursesClasses"/>

## Courses/Classes

<!-- Start Get Course List -->
<a name="GetCourseList"/>

### Get Course List

Get all courses in the system

#### Endpoint

`/GetCourseList`

#### Parameters

None

#### Return

```
{
    "classes": [
        {
            "name": string,
            "class_id": int,
            "section": int,
            "room_number": int,
            "professor_name": string,
            "time": string
        },
        .
        .
        .
    ]
}
```

<!-- End Get Course List -->
<!-- Start Get Courses -->
<a name="GetCourses"/>

### Get Course

Get course of a specified ID

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
            "credits": int,
            "course_code": string,
            "course_name": string,
            "course_description": string
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

### Get Classes

Returns all classes in the system

#### Endpoint

`/GetClasses`

#### Parameters

None

#### Return

```
{
    "classes": [
        {
            "name": string,
            "class_id": int,
            "section": int,
            "room_number": int,
            "professor_name": string,
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
<!-- Start Get Course Info -->

<a name="GetCourseInfo"/>

### Get Course Info

Get course of a specified ID

#### Endpoint

`/GetCourseInfo`

#### Parameters

`course_id` : The ID of the course

#### Return

```
{
    "course_info": [
        {
            "course_id": int,
            "credits": int,
            "course_code": string,
            "course_name": string,
            "course_description": string
        },
        .
        .
        .
    ]
}
```

<!-- End Get Course Info -->
<!-- Start Get Class Info -->

<a name="GetClassInfo"/>

### Get Class Info

Returns information for a specified class

#### Endpoint

`/GetClassInfo`

#### Parameters

`class_id` : ID of class to get info for

#### Return

```
{
    "class_info": [
        {
            "num_enrolled": int,
            "semester_id": int,
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

<!-- End Get Class Info -->
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
<!-- Start Add Class -->

<a name="AddClass"/>

### Add Class

Adds a new class

#### Endpoint

`/AddClass`

#### Parameters

`time` : Time of class  
`room_number` : Room class is in  
`course_id` : Course ID for class  
`prof_id` : Professor ID for class  
`capacity` : Max capacity for class  

#### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |

<!-- End Add Class -->
<!-- Start Add Course -->

<a name="AddCourse"/>

### Add Course

Adds a new class

#### Endpoint

`/AddCourse`

#### Parameters

`course_name` : Name of course  
`course_code` : Unique code for course  
`course_credits` : Number of credits that course is worth  
`course_description` : Description of course  

#### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |

<!-- End Add Course -->
<!-- Start Delete Class -->

<a name="DeleteClass"/>

### Delete Class

Deletes a class

#### Endpoint

`/DeleteClass`

#### Parameters

`class_id` : ID of class to be deleted

#### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |

<!-- End Delete Class -->
<!-- Start Delete Course -->

<a name="DeleteCourse"/>

### Delete Course

Deletes a course

#### Endpoint

`/DeleteCourse`

#### Parameters

`course_id` : ID of course to be deleted

#### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |

<!-- End Delete Course -->
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
{ "students_classes" : [
    "class" : string,
    .
    .
    .
    ]
}
```

<!-- End Get Classes -->
<!-- Start Get Students by Class ID -->

<a name="GetStudentsByClassId"/>

### Get Students by Class ID

Gets all students in a given class

#### Endpoint

`/GetStudentsByClassId`

#### Parameters

`class_id` : The ID of the class

#### Return

```
{
    "waitlisted": [
        {
            "user_name" : string,
            "user_id" : string
        },
        .
        .
        .
    ],
    "enrolled": [
        {
            "grade": int,
            "user_name": string,
            "user_id": int
        },
        .
        .
        .
    ]
}

```

<!-- End Get Students by Class ID -->
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
<!-- Start Enroll From Waitlist -->

<a name="EnrollFromWaitlist"/>

### Enroll From Waitlist

Goes through each class and enrolls students from the waitlist if there are open spots

#### Endpoint

`/EnrollFromWaitlist`

#### Parameters

None

#### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |

<!-- End Enroll From Waitlist -->
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
<!-- Start Check Enrollment Status -->

<a name="CheckEnrollmentStatus"/>

### Check Enrollment Status

Checks if a user is enrolled in a class

#### Endpoint

`/CheckEnrollmentStatus`

#### Parameters

`user_id` : ID of the user to be checked  
`class_id` : ID of the class to be checked

#### Return

```
{ "enrollment_status" : string }
```

<!-- End Check Enrollment Status -->
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
{ "favorited_classes" : [
    "class" : string
    .
    .
    .
    ]
}
```

<!-- End Get Favorited Classes -->
<!-- Start Check Favorite Status -->

<a name="CheckFavoriteStatus"/>

### Check Favorite Status

Checks if a user has favorited a given class

#### Endpoint

`/CheckFavoriteStatus`

#### Parameters

`user_id` : ID of the user to be checked  
`class_id` : ID of the class to be checked

#### Return

```
{ "favorite_status" : boolean }
```

<!-- End Check Favorite Status -->
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
<!-- Start Set GPA -->
<a name="SetGPA"/>

### Set GPA

Calculates a user's current GPA

#### Endpoint

`/SetGPA`

#### Parameters

`user_id` : ID of the user to be checked

#### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |

<!-- End Set GPA -->
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
{ "current_semester" : string }
```

<!-- End Get Current Semester -->
<!-- Start Get Semesters -->

<a name="GetSemesters"/>

### Get Semesters

Gets all the semesters in the system

#### Endpoint

`/GetSemesters`

#### Parameters

None

#### Return

```
{
    "semesters": [
        [
            "semester_ID" : int,
            "semester_code" : string
        ],
        .
        .
        .
    ]
}
```

<!-- End Get Semesters -->
<!-- Start Add Semester -->

<a name="AddSemester"/>

### Add Semester

Adds a new semester to the system

#### Endpoint

`/AddSemester`

#### Parameters

`semester_code` : Name of the semester (i.e. "Spring 2018")

#### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |

<!-- End Add Semester -->
<!-- Start Request Special Access -->

<a name="RequestSpecialAccess"/>

### Request Special Access

Allows a user to request special access for a class, such as notetaking or interpreting

#### Endpoint

`/RequestSpecialAccess`

#### Parameters

`class_id` : ID of class for request  
`user_id` : ID of user making request
`requests` : What the nature of the request is

#### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`        |

<!-- End Request Special Access -->
<!-- Start Get Access Requests -->

<a name="GetAccessRequests"/>

### Get Access Requests

Gets all special access requests currently in the system

#### Endpoint

`/GetAccessRequests`

#### Parameters

None

#### Return

{
    "requests": [
        {
            "class_name": string,
            "section": int,
            "request": string,
            "time": string,
            "course_code": string,
            "user_name": string
        },
        .
        .
        .
    ]
}

<!-- End Get Access Requests -->
<!-- Start Get Student Access -->

<a name="GetStudentAccess"/>

### Get Student Access

Gets a student's access requests for a class

#### Endpoint

`/GetStudentAccess`

#### Parameters

`user_id` : ID of user to get requests for
`class_id` : ID of class to get requests for

#### Return

{
    "requests": [
        {
            "request": string
        },
        .
        .
        .
    ]
}

<!-- End Get Student Access -->
<!-- Start Get Majors -->

<a name="GetMajors"/>

### Get Majors

Gets all majors in the system

#### Endpoint

`/GetMajors`

#### Parameters

None

#### Return

{
     "majors": [
        {
            "major_id": int,
            "major_name": string
        },

        .
        .
        .
    ]
}

<!-- End Get Majors -->
<!-- Start Get Prerequisites-->

<a name="GetPrereqs"/>

### Get Prerequisites

Gets all prerequisites for a given course

#### Endpoint

`/GetPrereqs`

#### Parameters

`course_id` : ID of course to get prerequisites for

#### Return

```
{
    "prereqs": [
        {
            "program_of_enrollment": string,
            "year_level": int,
            "prereq_id": int,
            "prereq_course": string,
            "major_name": string,
            "prereq_course_id": int,
            "type": int
        },
        .
        .
        .
    ]
}
```

<!-- End Get Prerequisites-->
<!-- Start Check Prerequisite-->

<a name="CheckPrereq"/>

### Check Prerequisite

Checks if a student fulfills a specific prerequisite

#### Endpoint

`/CheckPrereq`

#### Parameters

`student_id` : ID of student to be checked  
`prereq_id` : ID of prerequisite to be checked

#### Return

```
{ "meets_prereq" : boolean }
```

<!-- End Check Prerequisite-->
<!-- Start Add Prerequisites-->

<a name="AddPrereqs"/>

### Add Prerequisites

Adds prerequisites to a course

#### Endpoint

`/AddPrereqs`

#### Parameters
  
`course_id` : ID of course to add prerequisite to  
`type` : Type of prerequisite  
`program` : Program that prerequisite is for  
`year_level` : Required year level  
`prereq_course` : ID of prerequisite course

#### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`  |

<!-- End Add Prerequisites-->
<!-- Start Delete Prerequisite-->

<a name="DeletePrereq"/>

### Delete Prerequisite

Checks if a student fulfills a specific prerequisite

#### Endpoint

`/DeletePrereq`

#### Parameters
  
`prereq_id` : ID of prerequisite to be deleted

#### Return

| Success            | Failure            |
| ------------------ | ------------------ |
| `'SUCCESS'`        | `'FAILURE'`  |

<!-- End Delete Prerequisite-->
