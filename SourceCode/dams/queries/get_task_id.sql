##get task id
SELECT 
	tt.task_id,
	tt.`task_name`
FROM tasks tt 
LEFT JOIN terms t ON t.`term_id` = tt.`term_id`
WHERE tt.`task_name` =  'IPCR' AND t.`status` = 'ACTIVE'