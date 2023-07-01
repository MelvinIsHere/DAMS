SELECT
tt.task_name,
ts.`is_completed`
FROM tasks tt
LEFT JOIN task_status ts ON tt.task_id=ts.`task_id`
LEFT JOIN departments dp ON ts.`office_id`=dp.`department_id`
WHERE tt.for_deans = 1 AND dp.`department_abbrv`="CTE"