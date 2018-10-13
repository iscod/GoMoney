<?php

class Controller extends GoMoneyController
{
    /**
     * @param mixed $data
     */
    public function returnSuccess($data)
    {
        echo OutPut::returnSuccess($data);
        exit(0);
    }

    /**
     * @param string $message
     * @param int $error_code
     */
    public function returnError($message, $error_code = 102)
    {
        echo OutPut::returnError($message, $error_code);
        exit(0);
    }
}
