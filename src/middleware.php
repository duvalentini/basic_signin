<?php
// Route middleware
$mw = function ($request, $response, $next) {
  $data = $request->getParsedBody();
  $email = filter_var($data['email'], FILTER_SANITIZE_STRING);
  $password = filter_var($data['password'], FILTER_SANITIZE_STRING);

  if (!empty($email) && !empty($password)) {
    //Validate credentials
    $json_data = json_decode(file_get_contents('../docs/users.json'), true);
    if (validateUser($email, $password, $json_data)) {
      $newResponse = $response->withAddedHeader('Response_code', 'good');
      if (isAdmin($email, $json_data)) {
        $newResponse = $newResponse->withAddedHeader('Admin', 'yes');
      }
    } else {
      $newResponse = $response->withAddedHeader('Response_code', 'bad_creds');
    }
  } elseif (!empty($password)) {
      $newResponse = $response->withAddedHeader('Response_code', 'e_empty');
  } elseif (!empty($email)) {
      $newResponse = $response->withAddedHeader('Response_code', 'p_empty');
  } else {
      $newResponse = $response->withAddedHeader('Response_code', 'all_empty');
  }
  $response = $next($request, $newResponse);
  return $response;
};

//Helper function to validate credentials
function validateUser($email, $password, $json_data) {
    foreach ($json_data as $user) {
      if ($user['email'] == $email && $user['password'] == $password) {
        return true;
      }
    }
    return false;
}

//Helper function to determine if user is admin
function isAdmin($email, $json_data) {
    foreach ($json_data as $user) {
      if ($user['email'] == $email && $user['admin'] == true) {
        return true;
      }
    }
    return false;
}
