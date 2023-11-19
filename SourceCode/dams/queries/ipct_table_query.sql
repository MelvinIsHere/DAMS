##ipcr table
SELECT * FROM ipcr_table WHERE user_id = 48;
SELECT
	it.major_output,
	it.`success_indicator`,
	it.`actual_accomplishment`,
	it.`remarks`,
	it.`description`,
	it.`category`,
	it.quality,
	it.efficiency,
	it.timeliness,
	it.average,
	tt.`task_id`,
	t.`term`,
	t.`status`
	
FROM ipcr_table it 
LEFT JOIN tasks tt ON tt.task_id = it.task_id
LEFT JOIN terms t ON t.`term_id` = tt.`term_id`






 SELECT
                                                it.ipcr_id, 
                                                it.major_output,
                                                it.`success_indicator`,
                                                it.`actual_accomplishment`,
                                                it.`remarks`,
                                                it.`description`,
                                                it.`category`,
                                                it.quality,
                                                it.efficiency,
                                                it.timeliness,
                                                it.average,
                                                tt.`task_id`,
                                                t.`term`,
                                                t.`status`
                                                
                                            FROM ipcr_table it 
                                            LEFT JOIN tasks tt ON tt.task_id = it.task_id
                                            LEFT JOIN terms t ON t.`term_id` = tt.`term_id`

                                            WHERE it.user_id = '$user_id' AND tt.term_id = '$term_id' AND tt.cate
                                            
                                            
