<?php

$fullName = $_POST['full_name'];
$departmentId = $_POST['department_id'];
$employees = DB::select("
    SELECT
        employee_id,
        first_name,
        last_name,
        email,
        phone_number,
        departments.department_name as department_name,
        countries.country_name as department_name,
        jobs.job_title as job_title,
    FROM employees AS employees
    WHERE manager_id = (
        SELECT
            employee_id
        FROM employees
        WHERE
            first_name LIKE '%$fullName%'
            AND department_id = '$departmentId'
            OR last_name LIKE '%$fullName%'
            AND department_id = '$departmentId'
    )
")
    ->join('jobs', 'jobs.job_id', '=', 'employees.job_id')
    ->join('departments', 'departments.department_id', '=', 'employees.department_id')
    ->join('locations', 'locations.location_id', '=', 'departments.location_id')
    ->join('countries', 'countries.country_id', '=', 'locations.country_id')
    ->join('regions', 'regions.region_id', '=', 'countries.region_id')
;

return view('employee.index', ['employees' => $employees]);

//class DragonBall {
//    var $ballCount = 0;
//
//    function iFoundaBall() {
//        $this->ballCount += 1;
//    }
//}
