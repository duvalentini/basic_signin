<?php
//Add middleware closure to authenticate users
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

    //Switch case to determine template to render
    switch ($response->getHeader('Response_code')[0]) {
      case 'all_empty':
        return $this->view->render($response, 'index.html', [
          'blankforms' => 'Please enter your email address and password.'
        ]);
        break;
      case 'good':
        $specialStuff = '';
        if ($response->hasHeader('Admin')) {
          $specialStuff = 'You have admin access.';
        }
        return $this->view->render($response, 'newpage.twig', [
          'email' => $email,
          'admin' => $specialStuff
        ]);
        break;
      case 'bad_creds':
        return $this->view->render($response, 'index.html', [
          'blankforms' => 'Invalid credentials.'
        ]);
        break;
      case 'e_empty':
        return $this->view->render($response, 'index.html', [
          'blankforms' => 'Please enter your email address.'
        ]);
        break;
      case 'p_empty':
        return $this->view->render($response, 'index.html', [
          'blankforms' => 'Please enter your password.'
        ]);
        break;
      default:
        echo 'Error';
    }

})->add($mw);
