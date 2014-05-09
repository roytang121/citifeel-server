<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


// Please add your custom error here
// format: $config['error'][__number__] = '__Your Error Message__';

$config['error'][0] = 'UNKNOWN ERROR.'; // do not use this except CORE_Controller
$config['error'][1] = 'Failed credential check.';
$config['error'][2] = 'Standard validation error, you should use validation_error() function to generate exact message back to the app.';

// Login related error message
$config['error'][3] = 'User does not exist';
$config['error'][4] = 'Wrong password';

// FB login related error message
$config['error'][5] = 'FaceBook login failed! Invalid access token';
$config['error'][6] = 'Cannot create an user entry in our db from fb first time login';

// Register_related error message
$config['error'][10] = 'User already exists';
$config['error'][11] = 'DB insert fails';

/* End of file error_message.php */
/* Location: ./application/config/error_message.php */