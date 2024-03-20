<?php

namespace Service\Exception;

/**
 * Exception class for WooCooService.
 *
 * Wraps all of them into one Exception class for easier handling.
 */
class WooCooServiceException extends \Exception {
  public function message() : string
  {
    return "There was an error within the communications to DK-API. The error was : " .
      $this->getMessage() . " The error code was " . $this->getCode();
  }
}
