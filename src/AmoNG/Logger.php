<?php

namespace AmoNG;

class Logger
{
 
  const HTTP_CODES = [
    'errors' => [
      400 =>           'Bad request',
      401 =>          'Unauthorized',
      403 =>             'Forbidden',
      404 =>             'Not found',
      409 =>              'Conflict',
      500 => 'Internal Server Error',
      502 =>           'Bad gateway',
      503 =>   'Service unavailable',
    ],
    'success' => [
      200 =>         'OK',
      201 =>    'Created',
      204 => 'No content',
    ]

  ];
}
