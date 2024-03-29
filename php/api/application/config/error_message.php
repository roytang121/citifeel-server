<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


// Please add your custom error here
// format: $config['error'][__number__] = '__Your Error Message__';

$config['error'][0] = 'UNKNOWN ERROR.'; // do not use this except CORE_Controller
$config['error'][1] = 'Failed credential check.';
$config['error'][2] = 'Standard validation error, you should use validation_error() function to generate exact message back to the app.';

// Login related error message
$config['error'][3] = '使用者不存在！';
$config['error'][4] = '密碼不正確！';

// FB login related error message
$config['error'][5] = 'Facebook 登入失敗！';


// Register_related error message
$config['error'][10] = '使用者已存在！';
$config['error'][11] = 'DB insert fails';
$config['error'][12] = '頭像上傳失敗！';


// all other error messages
$config['error'][100000001] = 'Not yet implemented.';
$config['error'][100000002] = 'Database error.';

/* End of file error_message.php */
/* Location: ./application/config/error_message.php */