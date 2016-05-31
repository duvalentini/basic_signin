<?php
//Add middleware closure
//$app->add(function ($request, $response, ))

// Routes
$app->get('/signin', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->view->render($response, 'index.html');
});

$app->post('/signin', function ($request, $response, $args) {
    //Get form data
    $data = $request->getParsedBody();
    $email = filter_var($data['email'], FILTER_SANITIZE_STRING);
    $password = filter_var($data['password'], FILTER_SANITIZE_STRING);
    if (!empty($email) && !empty($password)) {

      //Validate credentials
      $json_data = json_decode(file_get_contents('../docs/users.json'), true);
      if (validateUser($email, $password, $json_data)) {
        return $this->view->render($response, 'newpage.twig', [
          'email' => $email,
          'router' => $this->router
        ]);
      } else {
          return $this->view->render($response, 'index.html', [
            'blankforms' => 'Invalid credentials.'
          ]);
      }
    } elseif (empty($email) && !empty($password)) {
      $blankform = 'Please enter your email address.';

      return $this->view->render($response, 'index.html', [
      'blankforms' => $blankform
      ]);
    } elseif (empty($password) && !empty($email)) {
      $blankform = 'Please enter your password.';

      return $this->view->render($response, 'index.html', [
      'blankforms' => $blankform
      ]);
    } else {
      $blankform = 'Please enter your email address and password.';

      return $this->view->render($response, 'index.html', [
      'blankforms' => $blankform
      ]);
    }

})->setName("welcome-page");

//Function to validate credentials
function validateUser($email, $password, $json_data) {
    foreach ($json_data as $user) {
      if ($user['email'] == $email && $user['password'] == $password) {
        return true;
      }
    }
    return false;
}
