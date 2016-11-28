<?php
return array(
	

    /* 数据库配置 */
    'DB_TYPE'               =>  'mysql',            
    'DB_HOST'               =>  'localhost',                 
    'DB_NAME'               =>  'qianmo',           
    'DB_USER'               =>  'root',                
    'DB_PWD'                =>  '`123456',                 
    'DB_PORT'               =>  '3306',             
    'DB_PREFIX'             =>  'qm_',               


   
    'TMPL_L_DELIM'          =>  '<{',               
    'TMPL_R_DELIM'          =>  '}>',               
    
    'SHOW_PAGE_TRACE'       =>  true,              
     
    'TMPL_PARSE_STRING' =>array(
        '__UPLOAD__' => __ROOT__.'/Upload',
    ),
);
